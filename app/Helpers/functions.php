<?php

// Escapar HTML (equivalente a {{ }} de Blade)
function e(?string $valor): string
{
    return htmlspecialchars($valor ?? '', ENT_QUOTES, 'UTF-8');
}

// Renderizar una vista dentro del layout
function view(string $vista, array $datos = []): void
{
    extract($datos);
    $rutaVista = __DIR__ . '/../../resources/views/' . str_replace('.', '/', $vista) . '.php';

    ob_start();
    require $rutaVista;
    $contenido = ob_get_clean();

    require __DIR__ . '/../../resources/views/layout.php';
}

// Redirigir a una ruta interna
function redirect(string $ruta): void
{
    header('Location: ' . url($ruta));
    exit;
}

// Generar URL absoluta respetando la carpeta donde est¨¢ instalado el proyecto
function url(string $ruta): string
{
    $base = defined('BASE_URL') ? BASE_URL : '';
    return $base . '/' . ltrim($ruta, '/');
}

// Usuario autenticado actual (o null)
function auth(): ?array
{
    return $_SESSION['usuario'] ?? null;
}

// Guardar un mensaje flash para la siguiente petici¨®n
function flash(string $tipo, string $mensaje): void
{
    $_SESSION['flash'] = ['tipo' => $tipo, 'mensaje' => $mensaje];
}

// Obtener y limpiar el mensaje flash
function getFlash(): ?array
{
    $flash = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $flash;
}
