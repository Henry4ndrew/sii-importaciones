<?php
echo "<h1>🔍 Test de Rutas Admin</h1>";
echo "<hr>";

require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/app/Helpers/functions.php';
require_once __DIR__ . '/bootstrap/app.php';

$path = 'admin/dashboard';
$cleanPath = obtenerCleanPath();

echo "<h2>Ruta actual:</h2>";
echo "cleanPath: " . $cleanPath . "<br>";

echo "<h2>esRutaAdmin() test:</h2>";
$testPaths = [
    'admin/dashboard',
    'admin/usuarios',
    'admin/administradores',
    'admin/administradores/crear',
    'admin/inventario',
    'dashboard',
];

echo "<table border='1' cellpadding='5'>";
echo "<tr><th>Ruta</th><th>esRutaAdmin()</th></tr>";
foreach ($testPaths as $test) {
    $result = esRutaAdmin($test) ? '✅ Sí' : '❌ No';
    echo "<tr><td>$test</td><td>$result</td></tr>";
}
echo "</table>";

echo "<hr>";

echo "<h2>Cargando rutas admin:</h2>";
$adminRoutes = require_once __DIR__ . '/app/Routes/admin.php';
echo "<pre>";
print_r(array_keys($adminRoutes));
echo "</pre>";

echo "<h2>Buscando 'admin/dashboard':</h2>";
if (isset($adminRoutes['dashboard'])) {
    echo "✅ Encontrado: " . $adminRoutes['dashboard']['controller'] . "::" . $adminRoutes['dashboard']['action'] . "<br>";
} else {
    echo "❌ No encontrado<br>";
}

echo "<hr>";

echo "<h2>Enlaces de prueba:</h2>";
echo "<ul>";
echo "<li><a href='" . url('admin/dashboard') . "'>admin/dashboard</a></li>";
echo "<li><a href='" . url('admin/usuarios') . "'>admin/usuarios</a></li>";
echo "<li><a href='" . url('admin/administradores') . "'>admin/administradores</a></li>";
echo "</ul>";