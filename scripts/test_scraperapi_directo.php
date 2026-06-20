<?php
// scripts/test_scraperapi_directo.php
// PRUEBA DIRECTA DE SCRAPERAPI

$config = require __DIR__ . '/../config/config.php';
$apiKey = $config['scraper_api_key'] ?? '';

echo "========================================\n";
echo "PRUEBA DE SCRAPERAPI\n";
echo "========================================\n\n";

echo "🔑 Clave API: " . ($apiKey ? substr($apiKey, 0, 4) . '***' : '❌ NO CONFIGURADA') . "\n";

if (empty($apiKey)) {
    echo "❌ ERROR: Clave de ScraperAPI no configurada en config/config.php\n";
    exit(1);
}

// Probar con una URL real
$url = 'https://www.alibaba.com/product-detail/Hot-Sale-Anime-Figure-Souvenir-3d_1601608735923.html';

echo "🌐 URL a probar: $url\n\n";
echo "⏳ Haciendo solicitud a ScraperAPI...\n";

$apiUrl = "https://api.scraperapi.com/?api_key=" . urlencode($apiKey) . 
          "&url=" . urlencode($url) . 
          "&country_code=us&render=false";

$inicio = microtime(true);
$ch = curl_init($apiUrl);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_FOLLOWLOCATION => true,
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
$tiempo = round(microtime(true) - $inicio, 2);

curl_close($ch);

if ($response === false) {
    echo "❌ ERROR CURL: $error\n";
    exit(1);
}

echo "✅ Respuesta en {$tiempo} segundos\n";
echo "📊 Código HTTP: $httpCode\n";
echo "📏 Tamaño: " . strlen($response) . " bytes\n\n";

// Verificar si es HTML de bloqueo
if (strpos($response, 'sufei-punish') !== false || strpos($response, 'Captcha') !== false) {
    echo "⚠️  La respuesta parece ser una página de bloqueo/Captcha\n";
    echo "Se recomienda usar &render=true en ScraperAPI\n\n";
} else {
    echo "✅ La respuesta parece HTML válido\n";
    
    // Buscar imágenes
    preg_match_all('/<img[^>]+src=["\'](https?:\/\/[^"\']+\.(?:jpg|jpeg|png|webp)[^"\']*)["\']/i', $response, $matches);
    echo "🖼️  Imágenes encontradas en HTML: " . count($matches[1]) . "\n";
    
    // Buscar precio
    if (preg_match('/<meta[^>]+(?:property|itemprop)=["\'][^"\']*price[^"\']*["\'][^>]+content=["\']([^"\']+)["\']/i', $response, $m)) {
        echo "💰 Precio en meta: " . $m[1] . "\n";
    }
    
    // Mostrar título
    if (preg_match('/<title[^>]*>(.*?)<\/title>/is', $response, $m)) {
        echo "📝 Título: " . trim($m[1]) . "\n";
    }
}

// Guardar respuesta para depuración
$logDir = __DIR__ . '/../logs';
if (!is_dir($logDir)) {
    mkdir($logDir, 0755, true);
}
file_put_contents($logDir . '/scraperapi_response.html', $response);
echo "\n💾 Respuesta guardada en: logs/scraperapi_response.html\n";

echo "\n========================================\n";