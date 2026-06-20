<?php
// scripts/test_extraccion_directa.php
// PRUEBA DIRECTA DE EXTRACCIÓN

require __DIR__ . '/../config/database.php';
require __DIR__ . '/../app/Helpers/Extractor.php';

// Usar una URL real de Alibaba
$url = 'https://www.alibaba.com/product-detail/Hot-Sale-Anime-Figure-Souvenir-3d_1601608735923.html';

echo "========================================\n";
echo "PRUEBA DE EXTRACCIÓN CON RENDER\n";
echo "========================================\n\n";
echo "URL: $url\n\n";

$inicio = microtime(true);
echo "⏳ Extrayendo datos (puede tomar hasta 60 segundos)...\n";

$datos = Extractor::extraer($url);
$tiempo = round(microtime(true) - $inicio, 2);

echo "\n✅ Completado en {$tiempo} segundos\n\n";

echo "RESULTADOS:\n";
echo "-----------\n";
echo "📝 Nombre: " . ($datos['nombre'] ?? '❌') . "\n";
echo "🖼️  Imagen: " . ($datos['imagen'] ? substr($datos['imagen'], 0, 80) . '...' : '❌') . "\n";
echo "💰 Precio: " . ($datos['precio'] ?? '❌') . "\n";
echo "💲 Precio Original: " . ($datos['precio_original'] ?? '❌') . "\n";
echo "🏷️  Oferta: " . ($datos['oferta'] ?? '❌') . "\n";
echo "📦 Pedido mínimo: " . ($datos['pedido_minimo'] ?? '❌') . "\n";

if (!empty($datos['imagenes'])) {
    echo "🖼️  Galería: " . count($datos['imagenes']) . " imágenes\n";
    foreach (array_slice($datos['imagenes'], 0, 3) as $i => $img) {
        echo "   - Imagen " . ($i+1) . ": " . substr($img, 0, 60) . "...\n";
    }
}

echo "\n========================================\n";