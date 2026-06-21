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
// OBTENER LA RUTA SOLICITADA
// ============================================
$requestUri = $_SERVER['REQUEST_URI'] ?? '/';
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

// Remover la base URL
$path = parse_url($requestUri, PHP_URL_PATH);
if (defined('BASE_URL') && BASE_URL !== '') {
    $path = str_replace(BASE_URL, '', $path);
}

// Eliminar slash al final (excepto si es solo '/')
if ($path !== '/' && substr($path, -1) === '/') {
    $path = rtrim($path, '/');
}

// Si la ruta está vacía o es 'dashboard' o 'dashboard/index.php', mostrar productos
if ($path === '' || $path === 'dashboard' || $path === 'dashboard/index.php') {
    $path = '/';
}

$path = trim($path, '/');

// ============================================
// DEFINIR RUTAS DEL DASHBOARD
// ============================================
$routes = [
    'GET /' => ['ProductoController', 'index'],
    'GET /dashboard' => ['ProductoController', 'index'],
    'GET /ranking' => ['ProductoController', 'ranking'],
    'GET /productos/crear' => ['ProductoController', 'crear'],
    'POST /productos' => ['ProductoController', 'store'],
    'GET /productos/capturar' => ['ProductoController', 'capturar'],
    'POST /votar' => ['VotoController', 'votar'],
    'GET /logout' => ['AuthController', 'logout'],
];

// ============================================
// BUSCAR LA RUTA
// ============================================
$routePath = $path === '' ? '/' : '/' . $path;
$routeKey = $method . ' ' . $routePath;
$handler = null;

foreach ($routes as $key => $handlerClass) {
    $normalizedKey = trim(preg_replace('#/+#', '/', $key));
    if ($normalizedKey === $routeKey) {
        $handler = $handlerClass;
        break;
    }
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