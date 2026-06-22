<?php
session_start();

// Cargar autoload de Composer
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
}

// ============================================
// AUTOLOADER PARA LAS CLASES
// ============================================
spl_autoload_register(function ($class) {
    if (strpos($class, 'App\\') === 0) {
        $prefix = 'App\\';
        $base_dir = __DIR__ . '/app/';
        $len = strlen($prefix);
        $relative_class = substr($class, $len);
        $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    } else {
        $file = __DIR__ . '/app/controllers/' . $class . '.php';
        if (!file_exists($file)) {
            $file = __DIR__ . '/app/Models/' . $class . '.php';
        }
        if (!file_exists($file)) {
            $file = __DIR__ . '/app/Helpers/' . $class . '.php';
        }
    }
    
    if (file_exists($file)) {
        require $file;
        return true;
    }
    return false;
});

// ============================================
// CARGAR CONFIGURACIÓN Y FUNCIONES
// ============================================
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/app/Helpers/functions.php';
require_once __DIR__ . '/app/Models/Usuario.php';
require_once __DIR__ . '/app/Models/Administrador.php';

// ============================================
// DETECTAR TIPO DE PETICIÓN POST
// ============================================
// IMPORTANTE: El orden de las condiciones importa
// 1. Recuperación (tiene 'recuperar')
// 2. Restablecer (tiene 'token' y 'password')
// 3. CRUD de administradores (tiene 'nombre' + 'email' + 'password' + 'password_confirm')
// 4. Login de admin (tiene 'email' + 'password' SIN 'nombre')
// 5. Login de usuario (solo 'email')

$isRecuperacion = ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['recuperar']));
$isRestablecer = ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['token']) && isset($_POST['password']));
$isCrudAdmin = ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nombre']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['password_confirm']));
$isLoginAdmin = ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email']) && isset($_POST['password']) && !isset($_POST['nombre']) && !isset($_POST['recuperar']) && !isset($_POST['token']));
$isLoginUsuario = ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email']) && !isset($_POST['password']) && !isset($_POST['recuperar']) && !isset($_POST['token']));

// ============================================
// PROCESAR RECUPERACIÓN DE CONTRASEÑA
// ============================================
if ($isRecuperacion) {
    require_once __DIR__ . '/app/controllers/RecuperacionController.php';
    $recuperacionController = new RecuperacionController();
    $recuperacionController->solicitar();
    exit;
}

if ($isRestablecer) {
    require_once __DIR__ . '/app/controllers/RecuperacionController.php';
    $recuperacionController = new RecuperacionController();
    $recuperacionController->restablecer();
    exit;
}

// ============================================
// PROCESAR CRUD DE ADMINISTRADORES
// ============================================
if ($isCrudAdmin) {
    // Obtener el cleanPath para saber si es guardar o actualizar
    $uri = $_SERVER['REQUEST_URI'] ?? '/';
    $uriPath = parse_url($uri, PHP_URL_PATH);
    $cleanPath = trim(str_replace(BASE_URL, '', $uriPath), '/');
    
    require_once __DIR__ . '/app/controllers/AdminController.php';
    $adminController = new AdminController();
    
    if (strpos($cleanPath, 'guardar') !== false) {
        $adminController->store();
    } elseif (strpos($cleanPath, 'actualizar') !== false) {
        $adminController->update();
    } else {
        flash('error', 'Acción no válida.');
        header('Location: ' . url('admin/administradores'));
    }
    exit;
}

// ============================================
// PROCESAR LOGIN DE ADMINISTRADOR
// ============================================
if ($isLoginAdmin) {
    require_once __DIR__ . '/app/controllers/AdministradorController.php';
    $adminController = new AdministradorController();
    $adminController->login();
    exit;
}

// ============================================
// PROCESAR LOGIN DE USUARIO NORMAL
// ============================================
if ($isLoginUsuario) {
    $email = trim($_POST['email'] ?? '');
    
    if (empty($email)) {
        flash('error', 'Por favor, ingresa tu correo electrónico.');
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
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
// OBTENER LA RUTA SOLICITADA
// ============================================
$uri = $_SERVER['REQUEST_URI'] ?? '/';
$uriPath = parse_url($uri, PHP_URL_PATH);
$cleanPath = trim(str_replace(BASE_URL, '', $uriPath), '/');

// ============================================
// RUTAS DE ADMINISTRADOR
// ============================================
if ($cleanPath === 'auth/login') {
    require_once __DIR__ . '/app/controllers/AdministradorController.php';
    $adminController = new AdministradorController();
    $adminController->showLogin();
    exit;
}

if ($cleanPath === 'auth/logout') {
    require_once __DIR__ . '/app/controllers/AdministradorController.php';
    $adminController = new AdministradorController();
    $adminController->logout();
    exit;
}

if ($cleanPath === 'auth/recuperar') {
    require_once __DIR__ . '/app/controllers/RecuperacionController.php';
    $recuperacionController = new RecuperacionController();
    $recuperacionController->showSolicitar();
    exit;
}

if ($cleanPath === 'auth/restablecer') {
    require_once __DIR__ . '/app/controllers/RecuperacionController.php';
    $recuperacionController = new RecuperacionController();
    $recuperacionController->showRestablecer();
    exit;
}

if ($cleanPath === 'admin/dashboard') {
    require_once __DIR__ . '/app/controllers/AdministradorController.php';
    $adminController = new AdministradorController();
    $adminController->dashboard();
    exit;
}

if ($cleanPath === 'admin/usuarios') {
    require_once __DIR__ . '/app/controllers/AdministradorController.php';
    $adminController = new AdministradorController();
    $adminController->usuarios();
    exit;
}

// ============================================
// RUTAS DE ADMINISTRADORES (CRUD)
// ============================================
if (strpos($cleanPath, 'admin/administradores') === 0) {
    require_once __DIR__ . '/app/controllers/AdminController.php';
    $adminController = new AdminController();
    
    // Si es POST, verificar qué acción
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (strpos($cleanPath, 'guardar') !== false) {
            $adminController->store();
            exit;
        } elseif (strpos($cleanPath, 'actualizar') !== false) {
            $adminController->update();
            exit;
        } else {
            header('Location: ' . url('admin/administradores'));
            exit;
        }
    }
    
    // Si es GET
    if ($cleanPath === 'admin/administradores') {
        $adminController->index();
        exit;
    } elseif ($cleanPath === 'admin/administradores/crear') {
        $adminController->crear();
        exit;
    } elseif (strpos($cleanPath, 'editar') !== false) {
        $adminController->editar();
        exit;
    } elseif (strpos($cleanPath, 'eliminar') !== false) {
        $adminController->delete();
        exit;
    }
    
    header('Location: ' . url('admin/administradores'));
    exit;
}

// ============================================
// SI ES LA RAÍZ, MOSTRAR LA PÁGINA DE INICIO
// ============================================
if ($cleanPath === '' || $cleanPath === 'index.php') {
    $titulo = 'Inicio - SII Importaciones';
    
    ob_start();
    ?>
    <div class="max-w-4xl mx-auto">
        <!-- Contenido de bienvenida -->
        <div class="bg-white rounded-xl shadow p-8 mb-6" style="border-top: 4px solid #2F5A8A;">
            <h1 class="text-3xl font-extrabold mb-4" style="color: #12283D;">
                <?= auth() ? '¡Bienvenido de vuelta!' : 'Bienvenido a SII Importaciones' ?>
            </h1>
            <p class="text-slate-600 leading-relaxed text-lg">
                Sistema de votación para importaciones desde China. Comparte y vota por los mejores productos de Alibaba.
            </p>
        </div>

        <!-- Tarjetas de características -->
        <div class="grid md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow p-6 hover:shadow-lg transition" style="border-top: 3px solid #2F5A8A;">
                <div class="text-4xl mb-4">📦</div>
                <h3 class="text-xl font-bold text-slate-800 mb-2">Importación</h3>
                <p class="text-slate-600">Productos de alta calidad desde China con los mejores precios del mercado.</p>
            </div>
            <div class="bg-white rounded-xl shadow p-6 hover:shadow-lg transition" style="border-top: 3px solid #2F5A8A;">
                <div class="text-4xl mb-4">⭐</div>
                <h3 class="text-xl font-bold text-slate-800 mb-2">Votación</h3>
                <p class="text-slate-600">Vota por tus productos favoritos y ayuda a otros a encontrar los mejores.</p>
            </div>
            <div class="bg-white rounded-xl shadow p-6 hover:shadow-lg transition" style="border-top: 3px solid #2F5A8A;">
                <div class="text-4xl mb-4">🤝</div>
                <h3 class="text-xl font-bold text-slate-800 mb-2">Comunidad</h3>
                <p class="text-slate-600">Únete a nuestra comunidad de importadores y comparte tu experiencia.</p>
            </div>
        </div>

        <!-- Sección de acceso -->
        <?php if (auth()): ?>
            <div id="acceso" class="bg-green-50 border border-green-200 rounded-xl p-6 text-center">
                <h2 class="text-2xl font-bold text-slate-800 mb-2">Explora los productos</h2>
                <p class="text-slate-600 mb-4">Descubre y vota por los mejores productos de Alibaba.</p>
                <div class="flex flex-wrap justify-center gap-4">
                    <a href="<?= url('dashboard') ?>" class="inline-block text-white font-bold px-6 py-3 rounded-lg hover:opacity-90 transition" style="background: #2F5A8A;">
                        📦 Ver Productos
                    </a>
                    <a href="<?= url('productos/crear') ?>" class="inline-block bg-amber-500 text-slate-900 font-bold px-6 py-3 rounded-lg hover:bg-amber-400 transition">
                        ➕ Publicar Producto
                    </a>
                </div>
            </div>
        <?php else: ?>
            <div id="acceso" class="bg-amber-50 border border-amber-200 rounded-xl p-6 text-center scroll-mt-20">
                <h2 class="text-2xl font-bold text-slate-800 mb-2">Accede al Sistema</h2>
                <p class="text-slate-600 mb-4">Ingresa tu correo electrónico para comenzar a votar y publicar productos.</p>
                
                <?php if ($flash = getFlash()): ?>
                    <div class="mb-4 rounded-lg px-4 py-3 text-sm font-semibold <?= $flash['tipo'] === 'exito' ? 'bg-green-100 text-green-800 border border-green-300' : 'bg-red-100 text-red-800 border border-red-300' ?>">
                        <?= e($flash['mensaje']) ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="<?= $_SERVER['PHP_SELF'] ?>" class="max-w-md mx-auto space-y-4">
                    <div>
                        <input type="email" name="email" required
                               placeholder="tu@email.com"
                               class="w-full border border-slate-300 rounded-lg px-4 py-3 text-center focus:outline-none focus:ring-2 transition" 
                               style="border-color: #B8CCE3; outline-color: #2F5A8A;">
                    </div>
                    <button class="w-full text-white font-bold py-3 rounded-lg hover:opacity-90 transition" style="background: #2F5A8A;">
                        Acceder
                    </button>
                </form>
                
                <div class="mt-4 text-xs text-slate-400">
                    <p>💡 Al ingresar tu correo, se creará automáticamente tu cuenta si no existe</p>
                </div>
                
                <div class="mt-6 pt-4 border-t border-amber-200">
                    <a href="<?= url('auth/login') ?>" class="text-sm font-semibold hover:underline" style="color: #2F5A8A;">
                        🔐 ¿Eres administrador? Inicia sesión aquí
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <?php
    $contenido = ob_get_clean();
    require_once __DIR__ . '/resources/views/layout.php';
    exit;
}

// ============================================
// RUTAS PÚBLICAS (conocenos, servicios, contactos)
// ============================================
$publicPages = ['conocenos', 'servicios', 'contactos'];
if (in_array($cleanPath, $publicPages)) {
    require_once __DIR__ . '/app/controllers/HomeController.php';
    $homeController = new HomeController();
    
    switch ($cleanPath) {
        case 'conocenos':
            $homeController->conocenos();
            break;
        case 'servicios':
            $homeController->servicios();
            break;
        case 'contactos':
            $homeController->contactos();
            break;
    }
    exit;
}

// ============================================
// RUTAS DEL DASHBOARD (usar el router)
// ============================================
use App\Core\Router;
$router = new Router();

$routes = require __DIR__ . '/routes/web.php';
foreach ($routes as $routeKey => $handler) {
    list($method, $path) = explode(' ', $routeKey, 2);
    $router->add($method, $path, $handler);
}

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$router->dispatch($uri, $method);