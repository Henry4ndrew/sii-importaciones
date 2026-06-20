<?php

/**
 * Extrae automáticamente nombre, imagen y precio de la página del producto.
 * Funciona con Alibaba y con cualquier sitio que use metaetiquetas Open Graph.
 */
class Extractor
{
    public static function extraer(string $url): array
    {
        $datos = ['nombre' => null, 'imagen' => null, 'imagenes' => null, 'precio' => null, 'precio_original' => null, 'oferta' => null, 'pedido_minimo' => null];

        // 1) Intento directo (rápido pero a menudo bloqueado)
        $html = self::descargar($url);

        // 2) Si el sitio bloqueó, usar ScraperAPI con render primero (más efectivo)
        if ($html === null || self::esHtmlDeBloqueo($html)) {
            // Primero intentar con render=true (más efectivo para Alibaba)
            $htmlApi = self::descargarViaScraperApi($url, true);
            
            // Si falla con render, intentar sin render como último recurso
            if ($htmlApi === null) {
                $htmlApi = self::descargarViaScraperApi($url, false);
            }
            
            if ($htmlApi !== null) {
                $html = $htmlApi;
            }
        }

        if ($html === null) {
            $datos['nombre'] = self::nombreDesdeUrl($url);
            return $datos;
        }

        // Quitar bytes inválidos que rompen las expresiones regulares con UTF-8
        $html = mb_convert_encoding($html, 'UTF-8', 'UTF-8');

        // ========== NOMBRE ==========
        if (preg_match('/<meta[^>]+property=["\']og:title["\'][^>]+content=["\']([^"\']+)["\']/i', $html, $m)
            || preg_match('/<meta[^>]+content=["\']([^"\']+)["\'][^>]+property=["\']og:title["\']/i', $html, $m)) {
            $datos['nombre'] = html_entity_decode(trim($m[1]), ENT_QUOTES, 'UTF-8');
        } elseif (preg_match('/<title[^>]*>(.*?)<\/title>/is', $html, $m)) {
            $datos['nombre'] = html_entity_decode(trim($m[1]), ENT_QUOTES, 'UTF-8');
        }
        if ($datos['nombre'] !== null) {
            $datos['nombre'] = trim(preg_replace('/\s+/', ' ', explode(' - Buy ', $datos['nombre'])[0]));
        }

        // ========== IMAGEN PRINCIPAL ==========
        // Buscar en og:image
        if (preg_match('/<meta[^>]+property=["\']og:image["\'][^>]+content=["\']([^"\']+)["\']/i', $html, $m)
            || preg_match('/<meta[^>]+content=["\']([^"\']+)["\'][^>]+property=["\']og:image["\']/i', $html, $m)) {
            $datos['imagen'] = trim($m[1]);
        }
        
        // Si no hay og:image, buscar en el HTML
        if ($datos['imagen'] === null) {
            // Buscar en el JSON de Alibaba
            if (preg_match('/"imageUrl":\{"big":"(https?:\/\/[^"]+)"/i', $html, $m)) {
                $datos['imagen'] = trim($m[1]);
            }
            // Buscar imágenes de alicdn
            elseif (preg_match('/<img[^>]+src=["\'](https?:\/\/[^"\']+\.alicdn\.com[^"\']+\.(?:jpg|jpeg|png|webp))["\']/i', $html, $m)) {
                $datos['imagen'] = trim($m[1]);
            }
            // Buscar cualquier imagen
            elseif (preg_match('/<img[^>]+src=["\'](https?:\/\/[^"\']+\.(?:jpg|jpeg|png|webp)[^"\']*)["\']/i', $html, $m)) {
                $datos['imagen'] = trim($m[1]);
            }
        }

        // ========== GALERÍA DE IMÁGENES ==========
        if (preg_match_all('/"imageUrl":\{"big":"(https?:\/\/[^"]+?)"/', str_replace('\\u002F', '/', $html), $mm)) {
            $galeria = array_values(array_unique($mm[1]));
            if ($galeria) {
                if ($datos['imagen'] && !in_array($datos['imagen'], $galeria)) {
                    array_unshift($galeria, $datos['imagen']);
                    $galeria = array_values(array_unique($galeria));
                }
                $datos['imagenes'] = array_slice($galeria, 0, 8);
                if ($datos['imagen'] === null) {
                    $datos['imagen'] = $galeria[0];
                }
            }
        }
        
        // Si no hay galería JSON, buscar imágenes en HTML
        if (empty($datos['imagenes'])) {
            if (preg_match_all('/<img[^>]+src=["\'](https?:\/\/[^"\']+\.alicdn\.com[^"\']+\.(?:jpg|jpeg|png|webp))["\']/i', $html, $matches)) {
                $datos['imagenes'] = array_slice(array_values(array_unique($matches[1])), 0, 8);
                if ($datos['imagen'] === null && !empty($datos['imagenes'])) {
                    $datos['imagen'] = $datos['imagenes'][0];
                }
            }
        }

        // ========== PRECIO ==========
        // 1. Buscar en JSON de Alibaba (más confiable)
        if ($datos['precio'] === null && preg_match_all('/"formatPrice":"([^"]{1,25})"/', $html, $mm)) {
            $porValor = [];
            foreach (array_unique($mm[1]) as $v) {
                if (preg_match('/([\d.,]+)/', $v, $n)) {
                    $porValor[(float) str_replace(',', '', $n[1])] = $v;
                }
            }
            if ($porValor) {
                ksort($porValor);
                $min = reset($porValor);
                $max = end($porValor);
                $datos['precio'] = $min === $max ? $min : $min . ' - ' . $max;
            }
        }
        
        // 2. Buscar en módulo de precio
        if ($datos['precio'] === null && preg_match('/class="[^"]*module_price[^"]*"[^>]*>(.{0,3000}?)(?:Cantidad|Minimum|Min\.|MOQ|<\/section|<\/div>\s*<\/div>\s*<\/div>)/is', $html, $m)) {
            $texto = trim(preg_replace('/\s+/', ' ', strip_tags(preg_replace('/>\s*</', '> <', $m[1]))));
            $texto = preg_replace('/(\d+)\s+(\d{2})(?!\d)/', '$1,$2', $texto);

            if (preg_match_all('/([$€]|Bs|US\$)\s?(\d+(?:[.,]\d+)?)/u', $texto, $mm) && count($mm[2]) > 0) {
                $simbolo = $mm[1][0];
                $valores = array_map(static fn ($v) => (float) str_replace(',', '.', $v), $mm[2]);
                $min = self::formatearPrecio(min($valores));
                $max = self::formatearPrecio(max($valores));
                $datos['precio'] = $min === $max ? "$simbolo $min" : "$simbolo $min - $simbolo $max";
            } elseif ($texto !== '' && preg_match('/\d/', $texto)) {
                $datos['precio'] = mb_substr($texto, 0, 100);
            }
        }
        
        // 3. Buscar en meta tags
        if ($datos['precio'] === null && preg_match('/<meta[^>]+(?:property|itemprop|name)=["\'][^"\']*price[^"\']*["\'][^>]+content=["\']([^"\']+)["\']/i', $html, $m)) {
            $datos['precio'] = trim($m[1]);
        }
        
        // 4. Buscar patrón de precio en texto
        if ($datos['precio'] === null && preg_match('/(US\s?)?\$\s?\d[\d.,]*(\s?-\s?(US\s?)?\$\s?\d[\d.,]*)?/', $html, $m)) {
            $datos['precio'] = trim($m[0]);
        }

        // ========== PRECIO ORIGINAL Y OFERTA ==========
        if ($datos['precio_original'] === null) {
            // Precio tachado
            if (preg_match('/<del[^>]*>(.*?)<\/del>/is', $html, $m) || preg_match('/<s[^>]*>(.*?)<\/s>/is', $html, $m)) {
                $precioDel = trim(strip_tags($m[1]));
                if (preg_match('/[\d.,]+/', $precioDel)) {
                    $datos['precio_original'] = $precioDel;
                }
            }
            // JSON original price
            if ($datos['precio_original'] === null && preg_match('/"originalPrice":\s*"?([\d.]+)/', $html, $m)) {
                $datos['precio_original'] = '$' . $m[1];
            }
        }
        
        if ($datos['oferta'] === null) {
            // Descuento en texto
            if (preg_match('/(\d{1,3}\s?%\s?(?:off|OFF|Off|dcto|dto\.?|descuento)|descuento\s*:\s*(\d{1,3})%)/i', $html, $m)) {
                $datos['oferta'] = trim($m[0]);
            }
            // JSON discount
            if ($datos['oferta'] === null && preg_match('/"discount":\s*"?([\d.]+)%?/', $html, $m)) {
                $datos['oferta'] = $m[1] . '%';
            }
        }

        // ========== PEDIDO MÍNIMO ==========
        if ($datos['pedido_minimo'] === null && preg_match('/"moq":\s*"?(\d+)/', $html, $m)) {
            $unidad = '';
            if (preg_match('/"(?:odUnit|unit)":"([^"]{1,25})"/', $html, $u)) {
                $unidad = ' ' . $u[1];
            }
            $datos['pedido_minimo'] = $m[1] . $unidad;
        }

        if ($datos['pedido_minimo'] === null) {
            $sinScripts = preg_replace('#<(script|style)\b.*?</\1>#is', ' ', $html);
            $textoPlano = strip_tags(preg_replace('/>\s*</', '> <', $sinScripts));
            if (preg_match('/Cantidad mínima[^:]{0,40}:\s*([^\n<]{1,60})/iu', $textoPlano, $m)
                || preg_match('/Min(?:imum)?\.?\s?order[^:]{0,40}:\s*([\d.,]+\s*[a-záéíóú]+)/i', $textoPlano, $m)) {
                $datos['pedido_minimo'] = trim($m[1]);
            } elseif (preg_match_all('/MOQ:\s*([\d.,]+)\s*([a-záéíóú]+)/iu', $textoPlano, $mm, PREG_SET_ORDER)) {
                usort($mm, static fn ($a, $b) => (float) str_replace(',', '', $a[1]) <=> (float) str_replace(',', '', $b[1]));
                $datos['pedido_minimo'] = $mm[0][1] . ' ' . $mm[0][2];
            }
        }

        // ========== LIMPIEZA FINAL ==========
        if ($datos['precio'] !== null) {
            $datos['precio'] = self::limpiarPrecio($datos['precio']);
        }
        
        if ($datos['precio_original'] !== null) {
            $datos['precio_original'] = self::limpiarPrecio($datos['precio_original']);
        }

        if ($datos['nombre'] === null || self::esPaginaDeBloqueo($datos['nombre'])) {
            $datos['nombre'] = self::nombreDesdeUrl($url);
        }

        $datos['nombre'] = mb_substr($datos['nombre'], 0, 255);

        return $datos;
    }

    // Quita decimales innecesarios: 265.00 -> "265", 0.49 -> "0.49"
    private static function formatearPrecio(float $valor): string
    {
        return $valor == (int) $valor ? (string) (int) $valor : number_format($valor, 2);
    }

    // Normaliza cualquier precio extraído
    private static function limpiarPrecio(string $texto): string
    {
        $texto = html_entity_decode($texto, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $texto = trim(preg_replace('/\s+/', ' ', str_replace("\xc2\xa0", ' ', $texto)));

        preg_match_all(
            '/([$€£]|US\$|MX\$|Bs)\s?(\d+(?:[.,]\d+)?)|(\d+(?:[.,]\d+)?)\s?(SEK|USD|EUR|MXN|GBP|CNY|kr|€|£)/u',
            $texto,
            $mm,
            PREG_SET_ORDER
        );

        $moneda = null;
        $valores = [];
        foreach ($mm as $m) {
            if ($m[1] !== '') {
                $moneda = $moneda ?? ['prefijo', $m[1]];
                $num = $m[2];
            } else {
                $moneda = $moneda ?? ['sufijo', $m[4]];
                $num = $m[3];
            }
            $num = preg_match('/,\d{1,2}$/', $num)
                ? str_replace(['.', ','], ['', '.'], $num)
                : str_replace(',', '', $num);
            $valores[] = (float) $num;
        }

        if ($valores) {
            $min = self::formatearPrecio(min($valores));
            $max = self::formatearPrecio(max($valores));
            $rango = $min === $max ? $min : "$min - $max";
            return $moneda[0] === 'prefijo'
                ? trim($moneda[1] . ' ' . $rango)
                : trim($rango . ' ' . $moneda[1]);
        }

        return mb_substr($texto, 0, 100);
    }

    // Detecta títulos de páginas de captcha/verificación
    private static function esPaginaDeBloqueo(string $titulo): bool
    {
        return (bool) preg_match('/captcha|verif|robot|access denied|forbidden|punish|security/i', $titulo);
    }

    // Detecta si el HTML recibido es la página anti-bots de Alibaba
    private static function esHtmlDeBloqueo(string $html): bool
    {
        return strpos($html, 'sufei-punish') !== false
            || strpos($html, 'Captcha Interception') !== false
            || strpos($html, 'security_check') !== false
            || stripos($html, 'punish') !== false && stripos($html, 'og:title') === false;
    }

    // Descarga la página a través de ScraperAPI
    private static function descargarViaScraperApi(string $url, bool $render): ?string
    {
        $config = require __DIR__ . '/../../config/config.php';
        $clave = trim($config['scraper_api_key'] ?? '');

        if ($clave === '') {
            return null;
        }

        // Construir URL de la API
        $apiUrl = 'https://api.scraperapi.com/?api_key=' . urlencode($clave)
            . '&url=' . urlencode($url)
            . '&country_code=us';
        
        // Siempre usar render para Alibaba
        if ($render) {
            $apiUrl .= '&render=true';
        }

        $ch = curl_init($apiUrl);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => $render ? 60 : 25,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_USERAGENT      => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36',
        ]);

        $html = curl_exec($ch);
        $codigo = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);

        if ($html === false) {
            error_log("ScraperAPI error: $error");
            return null;
        }

        if ($codigo >= 400 || self::esHtmlDeBloqueo($html)) {
            return null;
        }

        return $html;
    }

    // Convierte el slug de la URL en un nombre legible
    public static function nombreDesdeUrl(string $url): string
    {
        $ruta = parse_url($url, PHP_URL_PATH) ?? '';
        $slug = pathinfo($ruta, PATHINFO_FILENAME);
        $slug = preg_replace('/_\d+$/', '', $slug);
        $nombre = trim(ucwords(str_replace(['-', '_'], ' ', $slug)));

        if (mb_strlen($nombre) < 4 || preg_match('/^\d+$/', $nombre)) {
            return 'Producto de Alibaba';
        }

        return $nombre;
    }

    private static function descargar(string $url): ?string
    {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS      => 5,
            CURLOPT_TIMEOUT        => 12,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_USERAGENT      => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/125.0.0.0 Safari/537.36',
            CURLOPT_HTTPHEADER     => ['Accept-Language: es,en;q=0.8'],
            CURLOPT_ENCODING       => 'gzip, deflate, br',
        ]);

        $html = curl_exec($ch);
        $codigo = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($html === false || $codigo >= 400) {
            return null;
        }

        return $html;
    }
}