<?php

session_start();

require __DIR__ . '/../config/database.php';
require __DIR__ . '/../app/Helpers/functions.php';
require __DIR__ . '/../app/Helpers/Extractor.php';
require __DIR__ . '/../app/Models/Usuario.php';
require __DIR__ . '/../app/Models/Producto.php';
require __DIR__ . '/../app/Models/Voto.php';
require __DIR__ . '/../app/Controllers/AuthController.php';
require __DIR__ . '/../app/Controllers/ProductoController.php';
require __DIR__ . '/../app/Controllers/VotoController.php';

$rutas = require __DIR__ . '/../routes/web.php';

// Calcular la ruta solicitada relativa a la carpeta del proyecto.
// Funciona tanto si se entra por /sii-importaciones/ (Apache reescribe a public/)
// como por /sistema-votacion/public/ o con el servidor embebido de PHP.
$scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$base = '';
foreach ([$scriptDir, dirname($scriptDir)] as $prefijo) {
    $prefijo = rtrim($prefijo, '/');
    if ($prefijo !== '' && strpos($uri, $prefijo) === 0) {
        $base = $prefijo;
        break;
    }
}

define('BASE_URL', $base);
$ruta = '/' . trim(substr($uri, strlen($base)), '/');

$clave = $_SERVER['REQUEST_METHOD'] . ' ' . $ruta;

if (!isset($rutas[$clave])) {
    http_response_code(404);
    view('error404', ['titulo' => 'Página no encontrada']);
    exit;
}

[$controlador, $metodo] = $rutas[$clave];
(new $controlador())->$metodo();
