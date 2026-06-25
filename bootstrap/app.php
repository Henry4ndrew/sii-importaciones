<?php

/**
 * Bootstrap de la aplicación
 */

// ============================================
// 1. DETECTAR TIPO DE PETICIÓN POST
// ============================================
function detectarTipoPost(): ?string
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        return null;
    }
    
    if (isset($_POST['recuperar'])) {
        return 'recuperacion';
    }
    
    if (isset($_POST['token']) && isset($_POST['password'])) {
        return 'restablecer';
    }
    
    if (isset($_POST['nombre']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['password_confirm'])) {
        return 'crud_admin';
    }
    
    if (isset($_POST['email']) && isset($_POST['password']) && !isset($_POST['nombre'])) {
        return 'login_admin';
    }
    
    if (isset($_POST['email']) && !isset($_POST['password'])) {
        return 'login_usuario';
    }

    // Portadas
    if (isset($_POST['titulo']) && isset($_POST['ruta_imagen']) && !isset($_POST['password']) && !isset($_POST['nombre'])) {
        return 'crud_portada';
    }
    // Contactos
    if (isset($_POST['ciudad']) && isset($_POST['telefono']) && isset($_POST['correo']) && !isset($_POST['password']) && !isset($_POST['nombre'])) {
        return 'crud_contacto';
    }
    //SERVICIOS
    if (isset($_POST['titulo']) && isset($_POST['subsecciones']) && !isset($_POST['password']) && !isset($_POST['nombre']) && !isset($_POST['ciudad'])) {
        return 'crud_servicio';
    }
    //CONÓCENOS
    if (isset($_POST['encabezado_titulo']) || isset($_POST['mision_texto'])) {
        return 'crud_conocenos';
    }
    //EMPRESA
    if (isset($_POST['descripcion_corporativa']) || isset($_POST['email_principal'])) {
        return 'crud_empresa';
    }
    // DETECTAR CONFIGURACIÓN DEL LOGIN
    if (isset($_POST['login_habilitado']) && isset($_POST['mensaje'])) {
        return 'configuracion_login';
    }
    return null;
}

// ============================================
// 2. PROCESAR ACCIONES POST
// ============================================
function procesarPost(string $tipo): void
{
    switch ($tipo) {
        case 'recuperacion':
            require_once __DIR__ . '/../app/controllers/RecuperacionController.php';
            $controller = new RecuperacionController();
            $controller->solicitar();
            break;
            
        case 'restablecer':
            require_once __DIR__ . '/../app/controllers/RecuperacionController.php';
            $controller = new RecuperacionController();
            $controller->restablecer();
            break;
            
        case 'crud_admin':
            $cleanPath = obtenerCleanPath();
            require_once __DIR__ . '/../app/controllers/AdminController.php';
            $controller = new AdminController();
            
            if (strpos($cleanPath, 'guardar') !== false) {
                $controller->store();
            } elseif (strpos($cleanPath, 'actualizar') !== false) {
                $controller->update();
            }
            break;
            
        case 'login_admin':
            require_once __DIR__ . '/../app/controllers/AdministradorController.php';
            $controller = new AdministradorController();
            $controller->login();
            break;
            
        case 'login_usuario':
            procesarLoginUsuario();
            break;
        
        //PORTADA
        case 'crud_portada':
        $cleanPath = obtenerCleanPath();
        require_once __DIR__ . '/../app/controllers/PortadaController.php';
        $controller = new PortadaController();
        
        if (strpos($cleanPath, 'guardar') !== false) {
            $controller->store();
        } elseif (strpos($cleanPath, 'actualizar') !== false) {
            $controller->update();
        }
        break;

        //CONTACTOS
        case 'crud_contacto':
        $cleanPath = obtenerCleanPath();
        require_once __DIR__ . '/../app/controllers/ContactoController.php';
        $controller = new ContactoController();
        
        if (strpos($cleanPath, 'guardar') !== false) {
            $controller->store();
        } elseif (strpos($cleanPath, 'actualizar') !== false) {
            $controller->update();
        }
        break;

        //SERVICIOS
        case 'crud_servicio':
            $cleanPath = obtenerCleanPath();
            require_once __DIR__ . '/../app/controllers/ServicioController.php';
            $controller = new ServicioController();
            
            if (strpos($cleanPath, 'guardar') !== false) {
                $controller->store();
            } elseif (strpos($cleanPath, 'actualizar') !== false) {
                $controller->update();
            }
            break;
        //CONÓCENOS
        case 'crud_conocenos':
            $cleanPath = obtenerCleanPath();
            require_once __DIR__ . '/../app/controllers/ConocenosController.php';
            $controller = new ConocenosController();
            if (strpos($cleanPath, 'actualizar') !== false) {
                $controller->update();
            }
            break;
        //EMPRESA
        case 'crud_empresa':
            $cleanPath = obtenerCleanPath();
            require_once __DIR__ . '/../app/controllers/EmpresaController.php';
            $controller = new EmpresaController();
            if (strpos($cleanPath, 'actualizar') !== false) {
                $controller->update();
            }
            break;
        //CONFIGURACION LOGIN    
        case 'configuracion_login':
            require_once __DIR__ . '/../app/controllers/PublicacionController.php';
            $controller = new PublicacionController();
            $controller->actualizarConfiguracionLogin();
            break;
        //
    }
    
    exit;
}

// ============================================
// 3. FUNCIONES AUXILIARES
// ============================================
function obtenerCleanPath(): string
{
    $uri = $_SERVER['REQUEST_URI'] ?? '/';
    $uriPath = parse_url($uri, PHP_URL_PATH);
    return trim(str_replace(BASE_URL, '', $uriPath), '/');
}

function procesarLoginUsuario(): void
{
    $email = trim($_POST['email'] ?? '');
    
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        flash('error', 'Por favor, ingresa un correo electrónico válido.');
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }
    
    $usuario = Usuario::buscarPorEmail($email);
    
    if (!$usuario) {
        $id = Usuario::crear($email);
        $usuario = Usuario::buscarPorEmail($email);
        flash('exito', '¡Bienvenido! Tu cuenta ha sido creada automáticamente.');
    }
    
    if (isset($_SESSION['administrador'])) {
        unset($_SESSION['administrador']);
    }
    
    $_SESSION['usuario'] = [
        'id' => $usuario['id'],
        'email' => $usuario['email'],
    ];
    
    header('Location: ' . BASE_URL . '/dashboard');
    exit;
}

// ============================================
// 4. FUNCIÓN PARA VERIFICAR SI ES RUTA ADMIN
// ============================================
function esRutaAdmin(string $path): bool
{
    // Definir las rutas admin directamente aquí
    $adminRoutes = [
        'admin/dashboard',
        //USUARIOS
        'admin/usuarios',
        'admin/usuarios/eliminar',
        //ADMINISTRADORES
        'admin/administradores',
        'admin/administradores/crear',
        'admin/administradores/editar',
        'admin/administradores/eliminar',
        'admin/administradores/guardar',
        'admin/administradores/actualizar',
        //PORTADAS
        'admin/portadas',
        'admin/portadas/ver',
        'admin/portadas/crear',
        'admin/portadas/editar',
        'admin/portadas/eliminar',
        'admin/portadas/guardar',
        'admin/portadas/actualizar',
        'admin/portadas/verificar-orden',
        'admin/portadas/toggle',
        //CONTACTOS
        'admin/contactos',
        'admin/contactos/ver',
        'admin/contactos/crear',
        'admin/contactos/editar',
        'admin/contactos/eliminar',
        'admin/contactos/guardar',
        'admin/contactos/actualizar',
        'admin/contactos/toggle',
        'admin/contactos/verificar-orden',
        //SERVICIOS
        'admin/servicios',
        'admin/servicios/ver',
        'admin/servicios/crear',
        'admin/servicios/editar',
        'admin/servicios/eliminar',
        'admin/servicios/guardar',
        'admin/servicios/actualizar',
        'admin/servicios/toggle',
        //PUBLICACIONES
        'admin/publicaciones',
        'admin/publicaciones/ver',
        'admin/publicaciones/eliminar',
        'admin/publicaciones/configuracion/actualizar', 
        //CONÓCENOS
        'admin/conocenos',
        'admin/conocenos/actualizar',
        'admin/conocenos/equipo/guardar',
        'admin/conocenos/equipo/actualizar',
        'admin/conocenos/equipo/eliminar',
        //EMPRESA
        'admin/empresa',
        'admin/empresa/actualizar',
    ];
    
    return in_array($path, $adminRoutes);
}