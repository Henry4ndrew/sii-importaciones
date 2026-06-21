<?php
echo "<h1>🔍 DIAGNÓSTICO COMPLETO</h1>";

// 1. Verificar configuración
echo "<h2>1. Configuración</h2>";
require_once __DIR__ . '/config/config.php';
echo "BASE_URL: " . BASE_URL . "<br>";
echo "Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "<br>";
echo "Script Filename: " . $_SERVER['SCRIPT_FILENAME'] . "<br>";
echo "Request URI: " . $_SERVER['REQUEST_URI'] . "<br>";

// 2. Verificar sesión
echo "<h2>2. Estado de la sesión</h2>";
session_start();
echo "Session ID: " . session_id() . "<br>";
echo "Contenido de sesión: <pre>";
print_r($_SESSION);
echo "</pre>";

// 3. Verificar autenticación
echo "<h2>3. Autenticación</h2>";
require_once __DIR__ . '/app/Helpers/functions.php';
echo "auth(): " . (auth() ? "✅ Autenticado como " . auth()['nombre'] : "❌ No autenticado") . "<br>";

// 4. Verificar conexión a BD
echo "<h2>4. Conexión a Base de Datos</h2>";
require_once __DIR__ . '/config/database.php';
try {
    $stmt = db()->query("SELECT 1");
    echo "✅ Conexión a BD exitosa<br>";
} catch (Exception $e) {
    echo "❌ Error de BD: " . $e->getMessage() . "<br>";
}

// 5. Verificar archivos necesarios
echo "<h2>5. Archivos necesarios</h2>";
$archivos = [
    'index.php' => __DIR__ . '/index.php',
    'dashboard/index.php' => __DIR__ . '/dashboard/index.php',
    'app/Helpers/functions.php' => __DIR__ . '/app/Helpers/functions.php',
    'config/config.php' => __DIR__ . '/config/config.php',
    'config/database.php' => __DIR__ . '/config/database.php',
];

foreach ($archivos as $nombre => $ruta) {
    echo $nombre . ": " . (file_exists($ruta) ? "✅ Existe" : "❌ No existe") . "<br>";
}

// 6. Enlaces para probar
echo "<h2>6. Enlaces de prueba</h2>";
echo "<a href='" . url('/') . "'>Ir a la raíz</a><br>";
echo "<a href='" . url('dashboard') . "'>Ir a dashboard</a><br>";
echo "<a href='test_login.php'>Ir a test_login.php</a><br>";
?>