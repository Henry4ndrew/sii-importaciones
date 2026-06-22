<?php

/**
 * Mapa de rutas del panel administrativo
 * Centraliza todas las rutas de administración para evitar duplicación
 */

// Definir las rutas como variable global
$GLOBALS['adminRoutes'] = [
    // Rutas del panel principal
    'dashboard' => [
        'controller' => 'AdministradorController',
        'action' => 'dashboard',
        'title' => 'Panel de Administración',
    ],
    
    // Gestión de usuarios
    'usuarios' => [
        'controller' => 'AdministradorController',
        'action' => 'usuarios',
        'title' => 'Gestión de Usuarios',
    ],
    
    // Gestión de administradores (CRUD)
    'administradores' => [
        'controller' => 'AdminController',
        'action' => 'index',
        'title' => 'Gestión de Administradores',
    ],
    'administradores/crear' => [
        'controller' => 'AdminController',
        'action' => 'crear',
        'title' => 'Crear Administrador',
    ],
    'administradores/editar' => [
        'controller' => 'AdminController',
        'action' => 'editar',
        'title' => 'Editar Administrador',
    ],
    'administradores/eliminar' => [
        'controller' => 'AdminController',
        'action' => 'delete',
        'title' => 'Eliminar Administrador',
    ],
    'administradores/guardar' => [
        'controller' => 'AdminController',
        'action' => 'store',
        'title' => 'Guardar Administrador',
        'method' => 'POST',
    ],
    'administradores/actualizar' => [
        'controller' => 'AdminController',
        'action' => 'update',
        'title' => 'Actualizar Administrador',
        'method' => 'POST',
    ],
];

// Función para obtener rutas admin
if (!function_exists('obtenerAdminRoute')) {
    function obtenerAdminRoute(string $path): ?array
    {
        global $adminRoutes;
        
        // Buscar coincidencia exacta
        if (isset($adminRoutes[$path])) {
            return $adminRoutes[$path];
        }
        
        // Buscar por prefijo (para rutas con parámetros)
        foreach ($adminRoutes as $route => $config) {
            if (strpos($path, $route) === 0) {
                return $config;
            }
        }
        
        return null;
    }
}

// Función para verificar si es ruta admin
if (!function_exists('esRutaAdmin')) {
    function esRutaAdmin(string $path): bool
    {
        global $adminRoutes;
        
        if (isset($adminRoutes[$path])) {
            return true;
        }
        
        foreach (array_keys($adminRoutes) as $route) {
            if (strpos($path, $route) === 0) {
                return true;
            }
        }
        
        return false;
    }
}

// Retornar las rutas (para compatibilidad)
return $GLOBALS['adminRoutes'];