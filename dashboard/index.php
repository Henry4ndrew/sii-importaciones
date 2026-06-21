<?php
// Cargar archivos necesarios
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../app/Helpers/functions.php';
require_once __DIR__ . '/../app/Models/Usuario.php';
require_once __DIR__ . '/../app/Models/Producto.php';
require_once __DIR__ . '/../app/Models/Voto.php';
require_once __DIR__ . '/../app/controllers/AuthController.php';
require_once __DIR__ . '/../app/controllers/ProductoController.php';
require_once __DIR__ . '/../app/controllers/VotoController.php';

// Iniciar sesión
session_start();

// ============================================
// VERIFICAR AUTENTICACIÓN
// ============================================
if (!auth()) {
    header('Location: ' . BASE_URL . '/');
    exit;
}

// ============================================
// ROUTER
// ============================================

// Obtener la URL
$requestUri = $_SERVER['REQUEST_URI'] ?? '/';
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

// Remover la base URL
$path = parse_url($requestUri, PHP_URL_PATH);
if (defined('BASE_URL') && BASE_URL !== '') {
    $path = str_replace(BASE_URL, '', $path);
}
$path = trim($path, '/');

// Construir la clave de ruta
$routePath = $path === '' ? '/' : '/' . $path;
$routeKey = $method . ' ' . $routePath;

// Mapeo directo de rutas (sin registro)
$handler = null;

switch ($routeKey) {
    case 'GET /':
    case 'GET /dashboard':
        $handler = ['ProductoController', 'index'];
        break;
    case 'GET /ranking':
        $handler = ['ProductoController', 'ranking'];
        break;
    case 'GET /productos/crear':
        $handler = ['ProductoController', 'crear'];
        break;
    case 'POST /productos':
        $handler = ['ProductoController', 'store'];
        break;
    case 'GET /productos/capturar':
        $handler = ['ProductoController', 'capturar'];
        break;
    case 'POST /votar':
        $handler = ['VotoController', 'votar'];
        break;
    case 'GET /login':
        $handler = ['AuthController', 'showLogin'];
        break;
    case 'POST /login':
        $handler = ['AuthController', 'login'];
        break;
    case 'GET /logout':
        $handler = ['AuthController', 'logout'];
        break;
}

// Si no se encuentra la ruta, mostrar 404
if (!$handler) {
    http_response_code(404);
    view('error404', ['titulo' => 'Página no encontrada']);
    exit;
}

// Ejecutar el controlador
list($controller, $methodName) = $handler;
$controllerInstance = new $controller();
$controllerInstance->$methodName();