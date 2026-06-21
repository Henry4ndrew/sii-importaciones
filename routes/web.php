<?php

// Mapa de rutas: 'MÉTODO ruta' => [Controlador, método]
return [
    // ============================================
    // RUTAS PÚBLICAS (HomeController)
    // ============================================
    'GET /'                => [HomeController::class, 'index'],
    'GET /conocenos'       => [HomeController::class, 'conocenos'],
    'GET /servicios'       => [HomeController::class, 'servicios'],
    'GET /contactos'       => [HomeController::class, 'contactos'],

    // ============================================
    // RUTAS DEL DASHBOARD (ProductoController)
    // ============================================
    'GET /dashboard'       => [ProductoController::class, 'index'],
    'GET /ranking'         => [ProductoController::class, 'ranking'],
    'GET /productos/crear' => [ProductoController::class, 'crear'],
    'POST /productos'      => [ProductoController::class, 'store'],
    'GET /productos/capturar' => [ProductoController::class, 'capturar'],
    'POST /votar'          => [VotoController::class, 'votar'],

    // ============================================
    // RUTAS DE AUTENTICACIÓN (AuthController)
    // ============================================
    'GET /login'           => [AuthController::class, 'showLogin'],
    'POST /login'          => [AuthController::class, 'login'],
    'GET /logout'          => [AuthController::class, 'logout'],
];