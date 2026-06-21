<?php
// Cargar archivos necesarios
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../app/Helpers/functions.php';
require_once __DIR__ . '/../app/Models/Usuario.php';
require_once __DIR__ . '/../app/Models/Producto.php';
require_once __DIR__ . '/../app/Models/Voto.php';
require_once __DIR__ . '/../app/controllers/AuthController.php';
require_once __DIR__ . '/../app/controllers/HomeController.php';
require_once __DIR__ . '/../app/controllers/ProductoController.php';
require_once __DIR__ . '/../app/controllers/VotoController.php';

// Iniciar sesión
session_start();

// ============================================
// OBTENER LA RUTA SOLICITADA
// ============================================
$requestUri = $_SERVER['REQUEST_URI'] ?? '/';
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

// Remover la base URL
$path = parse_url($requestUri, PHP_URL_PATH);
if (defined('BASE_URL') && BASE_URL !== '') {
    $path = str_replace(BASE_URL, '', $path);
}
$path = trim($path, '/');

// ============================================
// RUTAS PÚBLICAS - Redirigir al index.php raíz
// ============================================
$publicRoutes = ['', 'conocenos', 'servicios', 'contactos', 'login'];

if (in_array($path, $publicRoutes)) {
    // Redirigir al index.php raíz para manejar estas rutas
    header('Location: ' . BASE_URL . '/' . ($path ? $path : ''));
    exit;
}

// ============================================
// VERIFICAR AUTENTICACIÓN PARA RUTAS PROTEGIDAS
// ============================================
if (!auth()) {
    header('Location: ' . BASE_URL . '/');
    exit;
}

// ============================================
// ROUTER PARA RUTAS PROTEGIDAS
// ============================================

// Construir la clave de ruta
$routePath = $path === '' ? '/' : '/' . $path;
$routeKey = $method . ' ' . $routePath;

// Mapeo directo de rutas
$handler = null;

switch ($routeKey) {
    // Rutas del dashboard (ProductoController)
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
    
    // Rutas de autenticación
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