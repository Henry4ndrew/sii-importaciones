<?php

// Mapa de rutas: 'MÉTODO ruta' => [Controlador, método]
return [
    'GET /'                => [ProductoController::class, 'index'],
    'GET /ranking'         => [ProductoController::class, 'ranking'],
    'GET /productos/crear' => [ProductoController::class, 'crear'],
    'POST /productos'      => [ProductoController::class, 'store'],
    'GET /productos/capturar' => [ProductoController::class, 'capturar'],
    'POST /votar'          => [VotoController::class, 'votar'],

    'GET /login'           => [AuthController::class, 'showLogin'],
    'POST /login'          => [AuthController::class, 'login'],
    'GET /register'        => [AuthController::class, 'showRegister'],
    'POST /register'       => [AuthController::class, 'register'],
    'GET /logout'          => [AuthController::class, 'logout'],
];
