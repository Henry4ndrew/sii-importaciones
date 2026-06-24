<?php
/**
 * Autoloader manual para SII Importaciones
 * Versión mejorada con detección de archivos
 */

spl_autoload_register(function ($class) {
    // Limpiar el nombre de la clase
    $class = trim($class, '\\');
    
    // Mapeo de clases a archivos (con paths absolutos)
    $baseDir = __DIR__ . '/';
    
    $map = [
        // Controllers (NOTA: carpeta se llama 'controllers' en minúscula)
        'HomeController' => $baseDir . 'controllers/HomeController.php',
        'AdministradorController' => $baseDir . 'controllers/AdministradorController.php',
        'RecuperacionController' => $baseDir . 'controllers/RecuperacionController.php',
        'AdminController' => $baseDir . 'controllers/AdminController.php',
        
        // Models
        'Portada' => $baseDir . 'Models/Portada.php',
        'Usuario' => $baseDir . 'Models/Usuario.php',
        'Administrador' => $baseDir . 'Models/Administrador.php',
        'Empresa' => $baseDir . 'Models/Empresa.php',
        'Conocenos' => $baseDir . 'Models/Conocenos.php',
        'Servicio' => $baseDir . 'Models/Servicio.php',
        'Contacto' => $baseDir . 'Models/Contacto.php',
        
        // Core (con namespace)
        'App\Core\Router' => $baseDir . 'Core/Router.php',
    ];
    
    // 1. Buscar en el mapa exacto
    if (isset($map[$class])) {
        if (file_exists($map[$class])) {
            require_once $map[$class];
            return true;
        }
    }
    
    // 2. Si tiene namespace App\, buscar en app/
    if (strpos($class, 'App\\') === 0) {
        $classPath = substr($class, 4); // Quitar 'App\'
        $file = $baseDir . str_replace('\\', '/', $classPath) . '.php';
        if (file_exists($file)) {
            require_once $file;
            return true;
        }
    }
    
    // 3. Buscar en directorios comunes
    $directories = [
        'controllers',
        'Controllers', 
        'Models',
        'Core',
        'Helpers'
    ];
    
    foreach ($directories as $dir) {
        $file = $baseDir . $dir . '/' . $class . '.php';
        if (file_exists($file)) {
            require_once $file;
            return true;
        }
        
        // Intentar con namespace
        $file = $baseDir . $dir . '/' . str_replace('\\', '/', $class) . '.php';
        if (file_exists($file)) {
            require_once $file;
            return true;
        }
    }
    
    // 4. Si no se encuentra, registrar en log
    error_log("Autoload: Clase '$class' no encontrada");
    
    return false;
});

// Cargar funciones helper inmediatamente
$helperFile = __DIR__ . '/Helpers/functions.php';
if (file_exists($helperFile)) {
    require_once $helperFile;
} else {
    error_log("Archivo de helpers no encontrado: $helperFile");
}