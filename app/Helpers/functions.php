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

    // Usar layout.php (el original)
    require __DIR__ . '/../../resources/views/layout.php';
}

/**
 * Renderizar una vista con el layout de administrador
 */
function adminView(string $vista, array $datos = []): void
{
    extract($datos);
    $rutaVista = __DIR__ . '/../../resources/views/' . str_replace('.', '/', $vista) . '.php';

    // Asegurar que el admin está disponible en la vista
    if (!isset($admin) && isset($_SESSION['administrador'])) {
        $admin = $_SESSION['administrador'];
    }

    ob_start();
    require $rutaVista;
    $contenido = ob_get_clean();

    // Usar admin_layout.php (con sidebar)
    require __DIR__ . '/../../resources/views/layouts/admin_layout.php';
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





/**
 * Cerrar todas las sesiones activas (usuario y administrador)
 */
function cerrarTodasLasSesiones(): void
{
    if (isset($_SESSION['usuario'])) {
        unset($_SESSION['usuario']);
    }
    if (isset($_SESSION['administrador'])) {
        unset($_SESSION['administrador']);
    }
}

/**
 * Verificar si hay una sesión activa (usuario o administrador)
 */
function haySesionActiva(): bool
{
    return isset($_SESSION['usuario']) || isset($_SESSION['administrador']);
}

/**
 * Obtener el tipo de sesión activa
 */
function getTipoSesion(): ?string
{
    if (isset($_SESSION['administrador'])) {
        return 'administrador';
    }
    if (isset($_SESSION['usuario'])) {
        return 'usuario';
    }
    return null;
}
/**
 * Generar URL absoluta completa (para emails)
 */
function urlFull(string $ruta): string
{
    $base = defined('BASE_URL_FULL') ? BASE_URL_FULL : '';
    $ruta = trim($ruta, '/');
    
    if ($ruta === '') {
        return $base . '/';
    }
    
    return $base . '/' . $ruta;
}