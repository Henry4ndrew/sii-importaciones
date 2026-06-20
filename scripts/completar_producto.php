<?php
// scripts/completar_producto.php
// Completa foto/precio/pedido mínimo de UN producto, en segundo plano.
// Uso: php scripts/completar_producto.php <id>

// Aumentar tiempo de ejecución para renderizado
set_time_limit(120);

// Configurar logging
$logFile = __DIR__ . '/../logs/completar_producto.log';
$logDir = dirname($logFile);
if (!is_dir($logDir)) {
    mkdir($logDir, 0755, true);
}

function logMessage($msg) {
    global $logFile;
    file_put_contents($logFile, date('[Y-m-d H:i:s] ') . $msg . "\n", FILE_APPEND);
}

logMessage("=== INICIO completar_producto.php ===");

// Cargar dependencias
require __DIR__ . '/../config/database.php';
require __DIR__ . '/../app/Helpers/Extractor.php';

$id = (int) ($argv[1] ?? 0);
logMessage("ID recibido: $id");

if ($id <= 0) {
    logMessage("ERROR: Falta el id del producto");
    exit("Falta el id del producto\n");
}

// Obtener producto
try {
    $stmt = db()->prepare('SELECT id, url, nombre FROM productos WHERE id = ?');
    $stmt->execute([$id]);
    $p = $stmt->fetch();
    
    if (!$p) {
        logMessage("ERROR: Producto no encontrado ID: $id");
        exit("Producto no encontrado\n");
    }
    
    logMessage("Producto encontrado: ID $id, URL: " . $p['url']);
    
    // Intentar hasta 3 veces con espera entre intentos
    $exito = false;
    for ($i = 0; $i < 3; $i++) {
        logMessage("Intento " . ($i+1) . " de 3");
        
        try {
            $inicio = microtime(true);
            $datos = Extractor::extraer($p['url']);
            $tiempo = round(microtime(true) - $inicio, 2);
            
            logMessage("Extracción completada en {$tiempo}s");
            logMessage("Resultado - Imagen: " . ($datos['imagen'] ? 'OK' : 'NULL') . 
                      ", Precio: " . ($datos['precio'] ? 'OK' : 'NULL'));
            
            if ($datos['imagen'] !== null || $datos['precio'] !== null) {
                // Actualizar todos los campos
                $upd = db()->prepare('
                    UPDATE productos SET 
                        nombre = COALESCE(?, nombre), 
                        imagen = COALESCE(?, imagen),
                        imagenes = COALESCE(?, imagenes), 
                        precio = COALESCE(?, precio),
                        precio_original = COALESCE(?, precio_original),
                        oferta = COALESCE(?, oferta),
                        pedido_minimo = COALESCE(?, pedido_minimo)
                    WHERE id = ?
                ');
                
                $result = $upd->execute([
                    $datos['nombre'] ?: null,
                    $datos['imagen'],
                    $datos['imagenes'] ? json_encode($datos['imagenes']) : null,
                    $datos['precio'],
                    $datos['precio_original'] ?? null,
                    $datos['oferta'] ?? null,
                    $datos['pedido_minimo'],
                    $id
                ]);
                
                if ($result) {
                    logMessage("✅ Producto $id actualizado exitosamente");
                    echo "Completado\n";
                    $exito = true;
                    break;
                } else {
                    logMessage("❌ Falló la actualización en la BD");
                }
            } else {
                logMessage("⚠️ No se obtuvieron datos en intento " . ($i+1));
            }
        } catch (Exception $e) {
            logMessage("❌ Error en intento " . ($i+1) . ": " . $e->getMessage());
            logMessage("Trace: " . $e->getTraceAsString());
        }
        
        if ($i < 2 && !$exito) {
            logMessage("Esperando 3 segundos antes de reintentar...");
            sleep(3);
        }
    }
    
    if (!$exito) {
        logMessage("❌ No se pudieron obtener los detalles después de 3 intentos");
        echo "No se pudieron obtener los detalles\n";
    }
    
} catch (Exception $e) {
    logMessage("❌ Error general: " . $e->getMessage());
    echo "Error: " . $e->getMessage() . "\n";
}

logMessage("=== FIN completar_producto.php ===\n");