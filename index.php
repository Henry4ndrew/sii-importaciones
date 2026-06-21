<?php
session_start();

// ============================================
// AUTOLOADER PARA LAS CLASES
// ============================================
spl_autoload_register(function ($class) {
    // Si la clase no usa el namespace App\, intentar cargarla directamente
    if (strpos($class, 'App\\') === 0) {
        // Clases con namespace App\ (ej: App\Core\Router)
        $prefix = 'App\\';
        $base_dir = __DIR__ . '/app/';
        $len = strlen($prefix);
        $relative_class = substr($class, $len);
        $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    } else {
        // Clases sin namespace (ej: HomeController, ProductoController)
        // Buscar en app/controllers/
        $file = __DIR__ . '/app/controllers/' . $class . '.php';
        
        // Si no existe en controllers, buscar en app/Models/
        if (!file_exists($file)) {
            $file = __DIR__ . '/app/Models/' . $class . '.php';
        }
        
        // Si no existe en Models, buscar en app/Helpers/
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

use App\Core\Router;

// ============================================
// PROCESAR LOGIN (si es POST)
// ============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    $email = trim($_POST['email'] ?? '');
    
    // Validar email
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
    
    // Buscar usuario por email
    $usuario = Usuario::buscarPorEmail($email);
    
    // Si no existe, crear el usuario automáticamente
    if (!$usuario) {
        $id = Usuario::crear($email);
        $usuario = Usuario::buscarPorEmail($email);
        flash('exito', '¡Bienvenido! Tu cuenta ha sido creada automáticamente.');
    }
    
    // Iniciar sesión
    $_SESSION['usuario'] = [
        'id' => $usuario['id'],
        'email' => $usuario['email'],
    ];
    
    // Redirigir al dashboard
    header('Location: ' . BASE_URL . '/dashboard');
    exit;
}

// ============================================
// CONFIGURAR ROUTER
// ============================================
$router = new Router();

// Cargar las rutas desde web.php
$routes = require __DIR__ . '/routes/web.php';
foreach ($routes as $routeKey => $handler) {
    list($method, $path) = explode(' ', $routeKey, 2);
    $router->add($method, $path, $handler);
}

// ============================================
// MANEJAR LA RUTA SOLICITADA
// ============================================
$uri = $_SERVER['REQUEST_URI'] ?? '/';
$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

// Obtener el path limpio para verificar si es la raíz
$uriPath = parse_url($uri, PHP_URL_PATH);
$cleanPath = trim(str_replace(BASE_URL, '', $uriPath), '/');

// Si es la raíz, mostrar la página de inicio
if ($cleanPath === '' || $cleanPath === 'index.php') {
    $titulo = 'Inicio - WILLS IMPORT';
    
    ob_start();
    ?>
    <div class="max-w-4xl mx-auto">
        <!-- Contenido de bienvenida -->
        <div class="bg-white rounded-xl shadow p-8 mb-6">
            <h1 class="text-3xl font-extrabold text-slate-800 mb-4">
                <?= auth() ? '¡Bienvenido de vuelta!' : 'Bienvenido a WILLS IMPORT' ?>
            </h1>
            <p class="text-slate-600 leading-relaxed text-lg">
                Sistema de votación para importaciones desde China. Comparte y vota por los mejores productos de Alibaba.
            </p>
        </div>

        <!-- Tarjetas de características -->
        <div class="grid md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-xl shadow p-6 hover:shadow-lg transition">
                <div class="text-4xl mb-4">📦</div>
                <h3 class="text-xl font-bold text-slate-800 mb-2">Importación</h3>
                <p class="text-slate-600">Productos de alta calidad desde China con los mejores precios del mercado.</p>
            </div>
            <div class="bg-white rounded-xl shadow p-6 hover:shadow-lg transition">
                <div class="text-4xl mb-4">⭐</div>
                <h3 class="text-xl font-bold text-slate-800 mb-2">Votación</h3>
                <p class="text-slate-600">Vota por tus productos favoritos y ayuda a otros a encontrar los mejores.</p>
            </div>
            <div class="bg-white rounded-xl shadow p-6 hover:shadow-lg transition">
                <div class="text-4xl mb-4">🤝</div>
                <h3 class="text-xl font-bold text-slate-800 mb-2">Comunidad</h3>
                <p class="text-slate-600">Únete a nuestra comunidad de importadores y comparte tu experiencia.</p>
            </div>
        </div>

        <!-- Sección de acceso (login o dashboard) -->
        <?php if (auth()): ?>
            <div id="acceso" class="bg-green-50 border border-green-200 rounded-xl p-6 text-center">
                <h2 class="text-2xl font-bold text-slate-800 mb-2">Explora los productos</h2>
                <p class="text-slate-600 mb-4">Descubre y vota por los mejores productos de Alibaba.</p>
                <div class="flex flex-wrap justify-center gap-4">
                    <a href="<?= url('dashboard') ?>" class="inline-block bg-slate-900 text-white font-bold px-6 py-3 rounded-lg hover:bg-slate-700 transition">
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
                               class="w-full border border-slate-300 rounded-lg px-4 py-3 text-center focus:outline-none focus:ring-2 focus:ring-amber-500">
                    </div>
                    <button class="w-full bg-slate-900 text-white font-bold py-3 rounded-lg hover:bg-slate-700 transition">
                        Acceder
                    </button>
                </form>
                <div class="mt-4 text-xs text-slate-400">
                    <p>💡 Al ingresar tu correo, se creará automáticamente tu cuenta si no existe</p>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <?php
    $contenido = ob_get_clean();
    require_once __DIR__ . '/resources/views/layout.php';
    exit;
}

// Si no es la raíz, usar el router
$router->dispatch($uri, $method);