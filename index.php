<?php
session_start();

// ============================================
// 1. CARGAR AUTOLOADER DE COMPOSER
// ============================================
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
}

// ============================================
// 2. AUTOLOADER PARA CLASES DEL PROYECTO
// ============================================
spl_autoload_register(function ($class) {
    // Clases con namespace App\
    if (strpos($class, 'App\\') === 0) {
        $file = __DIR__ . '/app/' . str_replace('\\', '/', substr($class, 4)) . '.php';
    } else {
        // Clases sin namespace
        $paths = [
            __DIR__ . '/app/controllers/',
            __DIR__ . '/app/Models/',
            __DIR__ . '/app/Helpers/',
        ];
        
        foreach ($paths as $path) {
            $file = $path . $class . '.php';
            if (file_exists($file)) {
                require $file;
                return true;
            }
        }
        return false;
    }
    
    if (file_exists($file)) {
        require $file;
        return true;
    }
    return false;
});

// ============================================
// 3. CARGAR CONFIGURACIÓN Y FUNCIONES
// ============================================
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/app/Helpers/functions.php';
require_once __DIR__ . '/app/Models/Usuario.php';
require_once __DIR__ . '/app/Models/Administrador.php';

// ============================================
// 4. CARGAR BOOTSTRAP DE LA APLICACIÓN
// ============================================
require_once __DIR__ . '/bootstrap/app.php';

// ============================================
// 5. PROCESAR PETICIONES POST
// ============================================
$tipoPost = detectarTipoPost();
if ($tipoPost) {
    procesarPost($tipoPost);
    exit;
}

// ============================================
// 6. OBTENER LA RUTA SOLICITADA
// ============================================
$cleanPath = obtenerCleanPath();

// ============================================
// 7. RUTAS DE ADMINISTRADOR (Panel Admin)
// ============================================
if (esRutaAdmin($cleanPath)) {
    // Cargar rutas admin
    $adminRoutes = require __DIR__ . '/app/Routes/admin.php';
    
    // Buscar la ruta directamente
    if (isset($adminRoutes[$cleanPath])) {
        $routeConfig = $adminRoutes[$cleanPath];
        $controllerClass = $routeConfig['controller'];
        $action = $routeConfig['action'];
        
        $controllerFile = __DIR__ . '/app/controllers/' . $controllerClass . '.php';
        if (file_exists($controllerFile)) {
            require_once $controllerFile;
            
            if (class_exists($controllerClass)) {
                $controller = new $controllerClass();
                if (method_exists($controller, $action)) {
                    $controller->$action();
                    exit;
                }
            }
        }
    }
    
    // Si llegamos aquí, mostrar 404
    http_response_code(404);
    view('error404', ['titulo' => 'Página no encontrada']);
    exit;
}

// ============================================
// 8. RUTAS DE AUTENTICACIÓN
// ============================================
if ($cleanPath === 'auth/login') {
    require_once __DIR__ . '/app/controllers/AdministradorController.php';
    $controller = new AdministradorController();
    $controller->showLogin();
    exit;
}

if ($cleanPath === 'auth/logout') {
    require_once __DIR__ . '/app/controllers/AdministradorController.php';
    $controller = new AdministradorController();
    $controller->logout();
    exit;
}

if ($cleanPath === 'auth/recuperar') {
    require_once __DIR__ . '/app/controllers/RecuperacionController.php';
    $controller = new RecuperacionController();
    $controller->showSolicitar();
    exit;
}

if ($cleanPath === 'auth/restablecer') {
    require_once __DIR__ . '/app/controllers/RecuperacionController.php';
    $controller = new RecuperacionController();
    $controller->showRestablecer();
    exit;
}

// ============================================
// 9. PÁGINA DE INICIO (RAÍZ)
// ============================================
if ($cleanPath === '' || $cleanPath === 'index.php') {
    // Obtener portadas activas para el slider
    require_once __DIR__ . '/app/Models/Portada.php';
    $portadas = Portada::getActivas();
    
    // Si no hay portadas, usar imágenes de respaldo
    if (empty($portadas)) {
        $portadas = [
            [
                'titulo' => 'Bienvenido a SII Importaciones',
                'descripcion' => 'Sistema de votación para importaciones desde China',
                'ruta_imagen' => 'https://images.unsplash.com/photo-1556740749-887f6717d7e4?w=1600'
            ],
            [
                'titulo' => 'Vota por los Mejores Productos',
                'descripcion' => 'Descubre productos innovadores importados desde Alibaba',
                'ruta_imagen' => 'https://images.unsplash.com/photo-1563013544-824ae1b704d3?w=1600'
            ],
            [
                'titulo' => 'Importaciones Inteligentes',
                'descripcion' => 'La comunidad decide qué productos destacan',
                'ruta_imagen' => 'https://images.unsplash.com/photo-1519389950473-47ba0277781c?w=1600'
            ]
        ];
    }
    
    $titulo = 'Inicio - SII Importaciones';
    
    ob_start();
    ?>
    <div class="max-w-4xl mx-auto">
        
        <!-- ============================================ -->
        <!-- SLIDER DE PORTADAS                           -->
        <!-- ============================================ -->
        <div class="relative w-full h-[400px] md:h-[500px] overflow-hidden rounded-xl mb-8 shadow-2xl">
            <div class="relative w-full h-full">
                <?php foreach($portadas as $index => $portada): 
                    // Determinar la URL de la imagen
                    $imgUrl = $portada['ruta_imagen'];
                    // Si es una ruta local, agregar la URL base
                    if (!filter_var($imgUrl, FILTER_VALIDATE_URL)) {
                        $imgUrl = url('public/img/' . $portada['ruta_imagen']);
                    }
                ?>
                <div class="slide absolute inset-0 w-full h-full opacity-0 transition-opacity duration-1000 <?php echo $index === 0 ? 'active' : ''; ?>">
                    <img src="<?= $imgUrl ?>" 
                         alt="<?= e($portada['titulo']) ?>" 
                         class="w-full h-full object-cover">
                    <div class="absolute bottom-0 left-0 right-0 p-8 md:p-12 bg-gradient-to-t from-black/80 via-black/40 to-transparent">
                        <h1 class="text-2xl md:text-4xl font-bold text-white mb-2"><?= e($portada['titulo']) ?></h1>
                        <?php if (!empty($portada['descripcion'])): ?>
                            <p class="text-sm md:text-lg text-primary-200"><?= e($portada['descripcion']) ?></p>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

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

    <!-- ============================================ -->
    <!-- SCRIPTS PARA EL SLIDER                        -->
    <!-- ============================================ -->
    <style>
        /* Hero Slider */
        .slide {
            opacity: 0;
            transition: opacity 1s ease-in-out;
        }
        .slide.active {
            opacity: 1;
        }
        
        /* Animación fadeIn */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fadeIn {
            animation: fadeIn 0.4s ease;
        }
    </style>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Hero Slider
        const slides = document.querySelectorAll('.slide');
        let current = 0;
        
        if (slides.length > 1) {
            setInterval(() => {
                slides[current].classList.remove('active');
                current = (current + 1) % slides.length;
                slides[current].classList.add('active');
            }, 5000);
        }
    });
    </script>

    <?php
    $contenido = ob_get_clean();
    require_once __DIR__ . '/resources/views/layout.php';
    exit;
}

// ============================================
// 10. RUTAS PÚBLICAS
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
// 11. RUTAS DEL DASHBOARD (usar el router)
// ============================================
use App\Core\Router;
$router = new Router();

$routes = require __DIR__ . '/routes/web.php';
foreach ($routes as $routeKey => $handler) {
    list($method, $path) = explode(' ', $routeKey, 2);
    $router->add($method, $path, $handler);
}

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$router->dispatch($uri ?? '/', $method);