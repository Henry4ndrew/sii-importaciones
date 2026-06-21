<?php

function e(?string $valor): string
{
    return htmlspecialchars($valor ?? '', ENT_QUOTES, 'UTF-8');
}

function view(string $vista, array $datos = []): void
{
    extract($datos);
    $rutaVista = __DIR__ . '/../../resources/views/' . str_replace('.', '/', $vista) . '.php';

    ob_start();
    require $rutaVista;
    $contenido = ob_get_clean();

    require __DIR__ . '/../../resources/views/layout.php';
}

function redirect(string $ruta): void
{
    header('Location: ' . url($ruta));
    exit;
}

function url(string $ruta): string
{
    $base = defined('BASE_URL') ? BASE_URL : '';
    $ruta = trim($ruta, '/');
    
    if ($ruta === '') {
        return $base . '/';
    }
    
    return $base . '/' . $ruta;
}

function auth(): ?array
{
    return $_SESSION['usuario'] ?? null;
}

// Función para obtener solo el email del usuario autenticado
function authEmail(): ?string
{
    return $_SESSION['usuario']['email'] ?? null;
}

function flash(string $tipo, string $mensaje): void
{
    $_SESSION['flash'] = ['tipo' => $tipo, 'mensaje' => $mensaje];
}

function getFlash(): ?array
{
    $flash = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $flash;
}