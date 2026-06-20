<?php
echo "<h2>🔍 OBTENER RUTA DE PHP EN CPANEL</h2>";

// 1. Ver PHP_BINARY (la ruta que está ejecutando este script)
echo "<h3>1. Ruta actual de PHP (PHP_BINARY):</h3>";
echo "<pre style='background: #f0f0f0; padding: 10px; border-radius: 5px;'>";
echo PHP_BINARY;
echo "</pre>";

// 2. Probar todas las rutas posibles para PHP 8.2
echo "<h3>2. Probando rutas comunes para PHP 8.2:</h3>";

$rutas_posibles = [
    // Rutas de EasyApache 4 (cPanel moderno)
    '/opt/cpanel/ea-php82/root/usr/bin/php' => 'PHP 8.2 (EA4 - Recomendada)',
    '/opt/cpanel/ea-php81/root/usr/bin/php' => 'PHP 8.1',
    '/opt/cpanel/ea-php80/root/usr/bin/php' => 'PHP 8.0',
    '/opt/cpanel/ea-php74/root/usr/bin/php' => 'PHP 7.4',
    
    // Rutas directas
    '/usr/bin/php82' => 'PHP 8.2 (Directa)',
    '/usr/bin/php8.2' => 'PHP 8.2 (Con punto)',
    '/usr/bin/php81' => 'PHP 8.1',
    '/usr/bin/php80' => 'PHP 8.0',
    '/usr/bin/php74' => 'PHP 7.4',
    '/usr/bin/php' => 'PHP (Genérico)',
    '/usr/local/bin/php82' => 'PHP 8.2 (Local)',
    '/usr/local/bin/php' => 'PHP (Local genérico)',
    
    // CloudLinux
    '/opt/alt/php82/usr/bin/php' => 'PHP 8.2 (CloudLinux)',
    '/opt/alt/php81/usr/bin/php' => 'PHP 8.1 (CloudLinux)',
    '/opt/alt/php80/usr/bin/php' => 'PHP 8.0 (CloudLinux)',
    
    // Otras rutas comunes
    '/usr/bin/php-cli' => 'PHP CLI',
    '/usr/local/php82/bin/php' => 'PHP 8.2 (Alternativa)',
];

echo "<ul>";
$encontrada = false;
foreach ($rutas_posibles as $ruta => $descripcion) {
    if (file_exists($ruta) && is_executable($ruta)) {
        // Intentar obtener la versión
        $version = shell_exec($ruta . ' -v 2>&1 | head -1');
        if ($version && strpos($version, 'PHP 8.2') !== false) {
            echo "<li style='color: green;'>✅ <strong>$ruta</strong> - $descripcion<br>";
            echo "<span style='color: #006600;'>$version</span></li>";
            if (!$encontrada) {
                $encontrada = $ruta;
                echo "<li style='color: blue; font-weight: bold;'>⭐ ¡ESTA ES LA RUTA CORRECTA!</li>";
            }
        } elseif ($version) {
            echo "<li style='color: orange;'>⚠️ <strong>$ruta</strong> - $descripcion<br>";
            echo "<span style='color: #666;'>$version</span></li>";
        }
    } else {
        echo "<li style='color: #ccc;'>❌ $ruta - No existe</li>";
    }
}
echo "</ul>";

// 3. Si se encontró una ruta, dar recomendación
if ($encontrada) {
    echo "<h3>3. ✅ RECOMENDACIÓN PARA CONFIG.PHP:</h3>";
    echo "<pre style='background: #e8f5e9; padding: 15px; border-radius: 5px; border: 2px solid #4CAF50;'>";
    echo "'php_cli' => '" . $encontrada . "',  // Ruta detectada automáticamente";
    echo "</pre>";
} else {
    // 4. Si no se encontró, usar PHP_BINARY
    echo "<h3>3. ⚠️ No se encontró ruta exacta, usa esta:</h3>";
    echo "<pre style='background: #fff3e0; padding: 15px; border-radius: 5px; border: 2px solid #FF9800;'>";
    echo "'php_cli' => '" . PHP_BINARY . "',  // Usando la ruta actual";
    echo "</pre>";
}

// 5. Información adicional
echo "<h3>4. Información del sistema:</h3>";
echo "<ul>";
echo "<li><strong>Versión de PHP:</strong> " . phpversion() . "</li>";
echo "<li><strong>Sistema Operativo:</strong> " . PHP_OS . "</li>";
echo "<li><strong>Document Root:</strong> " . $_SERVER['DOCUMENT_ROOT'] . "</li>";
echo "<li><strong>Script actual:</strong> " . $_SERVER['SCRIPT_FILENAME'] . "</li>";
echo "</ul>";

// 6. Solución de respaldo
echo "<h3>5. Si nada funciona, usa esta configuración:</h3>";
echo "<pre style='background: #f0f0f0; padding: 10px; border-radius: 5px;'>";
echo "'php_cli' => 'php',  // Usar PHP del PATH";
echo "</pre>";
echo "<p><strong>Explicación:</strong> 'php' buscará automáticamente en el PATH del sistema.</p>";
?>