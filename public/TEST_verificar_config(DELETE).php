<?php
$config = require __DIR__ . '/../config/config.php';

echo "<h2>Verificando configuración</h2>";

echo "<h3>1. Configuración actual:</h3>";
echo "<pre>";
print_r($config);
echo "</pre>";

echo "<h3>2. Probando la ruta de PHP CLI:</h3>";
$ruta = $config['php_cli'];
echo "<p><strong>Ruta:</strong> $ruta</p>";

if (file_exists($ruta)) {
    echo "<p style='color: green;'>✅ El archivo existe</p>";
    $test = shell_exec($ruta . ' -v 2>&1 | head -1');
    if ($test) {
        echo "<p style='color: green;'>✅ PHP responde:</p>";
        echo "<pre>$test</pre>";
    }
} else {
    echo "<p style='color: red;'>❌ El archivo NO existe</p>";
}

echo "<h3>3. Prueba de ejecución en segundo plano:</h3>";
// Simular ejecución de un script en segundo plano
$script = __DIR__ . '/../scripts/completar_producto.php';
if (file_exists($script)) {
    echo "<p>Script encontrado: $script</p>";
    $comando = $ruta . ' ' . $script . ' 2>&1 > /dev/null &';
    echo "<p>Comando a ejecutar:</p>";
    echo "<pre>$comando</pre>";
    echo "<p style='color: green;'>✅ Configuración lista para ejecutar scripts en segundo plano</p>";
} else {
    echo "<p>⚠️ El script scripts/completar_producto.php no existe (opcional)</p>";
}
?>