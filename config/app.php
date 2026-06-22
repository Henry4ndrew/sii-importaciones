<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Configuración de la Aplicación
    |--------------------------------------------------------------------------
    */

    // URL base de la aplicación
    'url' => defined('BASE_URL') ? BASE_URL : '/sii-importaciones',

    // Configuración de rutas
    'routes' => [
        // Rutas públicas (no requieren autenticación)
        'public' => [
            'conocenos',
            'servicios', 
            'contactos',
        ],
        
        // Rutas de autenticación
        'auth' => [
            'login',
            'logout',
            'auth/login',
            'auth/logout',
            'auth/recuperar',
            'auth/restablecer',
        ],
        
        // Rutas del panel de administración
        'admin' => [
            'admin/dashboard',
            'admin/usuarios',
            'admin/administradores',
            'admin/administradores/crear',
            'admin/administradores/editar',
            'admin/administradores/eliminar',
            'admin/administradores/guardar',
            'admin/administradores/actualizar',
        ],
        
        // Rutas del dashboard (requieren autenticación de usuario)
        'dashboard' => [
            'dashboard',
            'ranking',
            'productos',
            'votar',
        ],
    ],

    // Configuración de sesión
    'session' => [
        'usuario' => 'usuario',
        'administrador' => 'administrador',
    ],
];