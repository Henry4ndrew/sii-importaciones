<?php

// Mapa de rutas: 'MÉTODO ruta' => [Controlador, método]
return [
    // ============================================
    // RUTAS PÚBLICAS (HomeController)
    // ============================================
    'GET /conocenos'       => ['HomeController', 'conocenos'],
    'GET /servicios'       => ['HomeController', 'servicios'],
    'GET /contactos'       => ['HomeController', 'contactos'],

    // ============================================
    // RUTAS DEL DASHBOARD (ProductoController)
    // ============================================
    'GET /dashboard'       => ['ProductoController', 'index'],
    'GET /dashboard/'      => ['ProductoController', 'index'], // <-- AGREGAR ESTA LÍNEA
    'GET /ranking'         => ['ProductoController', 'ranking'],
    'GET /productos/crear' => ['ProductoController', 'crear'],
    'POST /productos'      => ['ProductoController', 'store'],
    'GET /productos/capturar' => ['ProductoController', 'capturar'],
    'POST /votar'          => ['VotoController', 'votar'],

    // ============================================
    // RUTAS DE AUTENTICACIÓN (AuthController)
    // ============================================
    'GET /login'           => ['AuthController', 'showLogin'],
    'POST /login'          => ['AuthController', 'login'],
    'GET /logout'          => ['AuthController', 'logout'],
    
    // ============================================
    // RUTAS DE RECUPERACIÓN DE CONTRASEÑA
    // ============================================
    'GET /auth/recuperar'  => ['RecuperacionController', 'showSolicitar'],
    'POST /auth/recuperar' => ['RecuperacionController', 'solicitar'],
    'GET /auth/restablecer' => ['RecuperacionController', 'showRestablecer'],
    'POST /auth/restablecer' => ['RecuperacionController', 'restablecer'],

    // ============================================
    // RUTAS DE ADMINISTRADOR (AdministradorController)
    // ============================================
    'GET /admin/dashboard' => ['AdministradorController', 'dashboard'],
    'GET /admin/usuarios'  => ['AdministradorController', 'usuarios'],

    // ============================================
    // RUTAS DE ADMINISTRADORES (AdminController - CRUD)
    // ============================================
    'GET /admin/administradores'        => ['AdminController', 'index'],
    'GET /admin/administradores/crear'  => ['AdminController', 'crear'],
    'POST /admin/administradores/guardar' => ['AdminController', 'store'],
    'GET /admin/administradores/editar' => ['AdminController', 'editar'],
    'POST /admin/administradores/actualizar' => ['AdminController', 'update'],
    'GET /admin/administradores/eliminar' => ['AdminController', 'delete'],

 
];