
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

    if (!isset($admin) && isset($_SESSION['administrador'])) {
        $admin = $_SESSION['administrador'];
    }

    // Calcular total de administradores si no está presente
    if (!isset($totalAdministradores)) {
        try {
            $stmt = db()->query('SELECT COUNT(*) as total FROM administradores');
            $result = $stmt->fetch();
            $totalAdministradores = (int) $result['total'];
        } catch (Exception $e) {
            $totalAdministradores = 0;
        }
    }

    // Calcular total de usuarios si no está presente
    if (!isset($totalUsuarios)) {
        try {
            $stmt = db()->query('SELECT COUNT(*) as total FROM usuarios');
            $result = $stmt->fetch();
            $totalUsuarios = (int) $result['total'];
        } catch (Exception $e) {
            $totalUsuarios = 0;
        }
    }

    // Calcular total de votos si no está presente
    if (!isset($totalVotos)) {
        try {
            $stmt = db()->query('SELECT COUNT(*) as total FROM votos');
            $result = $stmt->fetch();
            $totalVotos = (int) $result['total'];
        } catch (Exception $e) {
            $totalVotos = 0;
        }
    }

    ob_start();
    require $rutaVista;
    $contenido = ob_get_clean();

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


/**
 * Obtener la URL de una imagen de producto
 */
function obtenerImagenProducto(?string $imagen): string
{
    if (empty($imagen)) {
        return ''; // Retorna vacío para mostrar el ícono por defecto
    }
    
    // Limpiar la URL
    $imagen = trim($imagen);
    
    // Si ya es una URL completa (http:// o https://)
    if (filter_var($imagen, FILTER_VALIDATE_URL)) {
        // Verificar si es una URL de Alibaba (sc04.alicdn.com)
        if (strpos($imagen, 'sc04.alicdn.com') !== false) {
            // A veces las imágenes de Alibaba necesitan un parámetro adicional
            // o podemos usar un proxy si es necesario
            return $imagen;
        }
        return $imagen;
    }
    
    // Si es una ruta local, agregar la URL base
    if (strpos($imagen, 'productos/') === 0 || strpos($imagen, 'portadas/') === 0) {
        return url('public/img/' . $imagen);
    }
    
    // Si no tiene carpeta, asumir que está en public/img/
    return url('public/img/' . $imagen);
}


/**
 * Verificar si una imagen es válida (existe y es accesible)
 */
function imagenValida(?string $url): bool
{
    if (empty($url)) {
        return false;
    }
    
    // Si es una URL local, verificar si el archivo existe
    if (strpos($url, $_SERVER['HTTP_HOST']) !== false) {
        $path = str_replace(['http://', 'https://', $_SERVER['HTTP_HOST']], '', $url);
        $fullPath = $_SERVER['DOCUMENT_ROOT'] . $path;
        return file_exists($fullPath);
    }
    
    // Para URLs externas, asumir que son válidas (se manejará con onerror)
    return true;
}