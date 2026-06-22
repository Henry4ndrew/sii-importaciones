<?php
echo "<h1>Test POST - Crear Administrador</h1>";

// Simular un POST
$_POST['nombre'] = 'Test desde POST';
$_POST['email'] = 'test_post_' . time() . '@test.com';
$_POST['password'] = '123456';
$_POST['password_confirm'] = '123456';

echo "<h2>Datos simulados:</h2>";
echo "<pre>";
print_r($_POST);
echo "</pre>";

// Cargar configuración y ejecutar store
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/app/Helpers/functions.php';
require_once __DIR__ . '/app/Models/Administrador.php';
require_once __DIR__ . '/app/controllers/AdminController.php';

session_start();

// Verificar sesión
if (!isset($_SESSION['administrador'])) {
    echo "❌ No hay sesión de administrador. Redirigiendo a login...<br>";
    // Simular sesión para pruebas
    $_SESSION['administrador'] = [
        'id' => 1,
        'email' => 'admin@test.com',
        'nombre' => 'Admin Test'
    ];
    echo "✅ Sesión simulada creada<br>";
}

echo "<h2>Ejecutando AdminController::store()</h2>";
$controller = new AdminController();
$controller->store();

echo "<h2>Resultado:</h2>";
echo "Revisa el mensaje flash en la página anterior.";