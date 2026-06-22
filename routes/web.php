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
    // RUTAS DE ADMINISTRADOR (AdministradorController)
    // ============================================
    'GET /login'           => ['AuthController', 'showLogin'],
    'POST /login'          => ['AuthController', 'login'],
    'GET /logout'          => ['AuthController', 'logout'],
    'GET /admin/dashboard' => ['AdministradorController', 'dashboard'],
    'GET /admin/usuarios'  => ['AdministradorController', 'usuarios'],
];