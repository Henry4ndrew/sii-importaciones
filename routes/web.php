<?php

// Mapa de rutas: 'MÉTODO ruta' => [Controlador, método]
return [
    // Rutas principales
    'GET /'                => [ProductoController::class, 'index'],
    'GET /dashboard'       => [ProductoController::class, 'index'],
    
    // Productos
    'GET /ranking'         => [ProductoController::class, 'ranking'],
    'GET /productos/crear' => [ProductoController::class, 'crear'],
    'POST /productos'      => [ProductoController::class, 'store'],
    'GET /productos/capturar' => [ProductoController::class, 'capturar'],
    'POST /votar'          => [VotoController::class, 'votar'],

    // Autenticación (solo login y logout)
    'GET /login'           => [AuthController::class, 'showLogin'],
    'POST /login'          => [AuthController::class, 'login'],
    'GET /logout'          => [AuthController::class, 'logout'],
];