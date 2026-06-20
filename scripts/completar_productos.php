<?php
// Rellena foto/precio/pedido mínimo de los productos que quedaron incompletos.
// Uso: C:\xampp\php\php.exe scripts\completar_productos.php

require __DIR__ . '/../config/database.php';
require __DIR__ . '/../app/Helpers/Extractor.php';

$productos = db()->query(
    "SELECT id, nombre, url FROM productos
     WHERE imagen IS NULL OR imagenes IS NULL OR precio IS NULL OR pedido_minimo IS NULL
        OR precio LIKE '%similar%' OR precio LIKE '%pieces%' OR precio LIKE '%boxes%' OR nombre LIKE '% - Buy %'"
)->fetchAll();

if (!$productos) {
    echo "No hay productos incompletos.\n";
    exit;
}

foreach ($productos as $p) {
    echo "Producto {$p['id']}: {$p['nombre']}\n";
    $datos = Extractor::extraer($p['url']);

    // Si la extracción vino vacía (render bloqueado), no tocar lo que ya está bien
    if ($datos['imagen'] === null && $datos['precio'] === null) {
        echo "  extracción bloqueada en este intento — se conserva lo existente\n\n";
        continue;
    }

    $stmt = db()->prepare(
        'UPDATE productos SET nombre = COALESCE(?, nombre), imagen = COALESCE(?, imagen),
         imagenes = COALESCE(?, imagenes), precio = COALESCE(?, precio),
         pedido_minimo = COALESCE(?, pedido_minimo) WHERE id = ?'
    );
    $stmt->execute([
        $datos['nombre'] ?: null,
        $datos['imagen'],
        $datos['imagenes'] ? json_encode($datos['imagenes']) : null,
        $datos['precio'],
        $datos['pedido_minimo'],
        $p['id'],
    ]);

    echo '  nombre: ' . mb_substr($datos['nombre'] ?? '-', 0, 70) . "\n";
    echo '  imagen: ' . ($datos['imagen'] ? 'OK' : 'no encontrada') . "\n";
    echo '  precio: ' . ($datos['precio'] ?? '-') . "\n";
    echo '  pedido: ' . ($datos['pedido_minimo'] ?? '-') . "\n\n";
}

echo "Listo.\n";
