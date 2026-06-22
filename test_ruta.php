<?php
echo "<h1>🔍 Test de Ruta - POST Administradores</h1>";
echo "<hr>";

// Cargar configuración
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/app/Helpers/functions.php';

echo "<h2>Información de la solicitud:</h2>";
echo "<ul>";
echo "<li><strong>REQUEST_URI:</strong> " . $_SERVER['REQUEST_URI'] . "</li>";
echo "<li><strong>REQUEST_METHOD:</strong> " . $_SERVER['REQUEST_METHOD'] . "</li>";
echo "<li><strong>SCRIPT_NAME:</strong> " . $_SERVER['SCRIPT_NAME'] . "</li>";
echo "<li><strong>BASE_URL:</strong> " . (defined('BASE_URL') ? BASE_URL : 'No definida') . "</li>";
echo "</ul>";

$uriPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$cleanPath = trim(str_replace(BASE_URL, '', $uriPath), '/');

echo "<h2>Path procesado:</h2>";
echo "<ul>";
echo "<li><strong>uriPath:</strong> " . $uriPath . "</li>";
echo "<li><strong>cleanPath:</strong> " . $cleanPath . "</li>";
echo "</ul>";

echo "<h2>Si estás haciendo POST, aquí están los datos:</h2>";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";
    
    // Verificar si el cleanPath coincide con lo esperado
    if ($cleanPath === 'admin/administradores/guardar') {
        echo "<p style='color:green;font-weight:bold;'>✅ El cleanPath coincide con 'admin/administradores/guardar'</p>";
    } else {
        echo "<p style='color:red;font-weight:bold;'>❌ El cleanPath NO coincide. Esperado: 'admin/administradores/guardar' - Recibido: '$cleanPath'</p>";
    }
} else {
    echo "<p>No es una solicitud POST. <a href='#' onclick='event.preventDefault(); document.getElementById(\"testForm\").submit();'>Haz clic aquí para simular un POST</a></p>";
}

echo "<hr>";

echo "<h2>Formulario de prueba:</h2>";
echo '<form id="testForm" method="POST" action="' . url('admin/administradores/guardar') . '">';
echo '<input type="hidden" name="test" value="1">';
echo '<input type="text" name="nombre" value="Test Ruta" placeholder="Nombre">';
echo '<input type="email" name="email" value="test_ruta_' . time() . '@test.com" placeholder="Email">';
echo '<input type="password" name="password" value="123456" placeholder="Contraseña">';
echo '<input type="password" name="password_confirm" value="123456" placeholder="Confirmar">';
echo '<button type="submit">Enviar POST</button>';
echo '</form>';

echo "<hr>";

echo "<h2>Enlaces útiles:</h2>";
echo "<ul>";
echo "<li><a href='" . url('admin/administradores/crear') . "'>Ir a crear administrador</a></li>";
echo "<li><a href='" . url('admin/administradores') . "'>Lista de administradores</a></li>";
echo "</ul>";