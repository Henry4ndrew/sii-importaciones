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

    // ============================================
    // OBTENER DATOS DE EMPRESA
    // ============================================
    $empresaData = null;
    try {
        $empresaPath = __DIR__ . '/app/Models/Empresa.php';
        if (file_exists($empresaPath)) {
            require_once $empresaPath;
            if (class_exists('Empresa')) {
                $empresaData = Empresa::get();
            }
        }
    } catch (Exception $e) {
        $empresaData = null;
    }
    
    if (!$empresaData || empty($empresaData)) {
        $empresaData = [
            'descripcion_corporativa' => 'Sistema de votación para importaciones desde China',
            'email_principal' => '',
            'direccion_textual' => '',
            'enlace_gps' => '',
            'facebook' => '',
            'instagram' => '',
            'tiktok' => '',
            'youtube' => '',
            'whatsapp' => '',
        ];
    }

    // ============================================
    // PROCESAR WHATSAPP - Agregar +591 si no existe
    // ============================================
    $whatsappNumero = '';
    $whatsappMostrar = '';
    if (!empty($empresaData['whatsapp'])) {
        // Limpiar número (solo dígitos)
        $whatsappNumero = preg_replace('/[^0-9]/', '', $empresaData['whatsapp']);
        // Mostrar con +591 si no lo tiene
        if (strpos($empresaData['whatsapp'], '+591') === 0) {
            $whatsappMostrar = $empresaData['whatsapp'];
        } else {
            $whatsappMostrar = '+591 ' . $whatsappNumero;
        }
    }

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
    
    $slidesToShow = $portadas;
    $titulo = 'Inicio - SII Importaciones';
    
    ob_start();
    ?>

    <!-- ============================================ -->
    <!-- HERO SLIDER - FULL WIDTH                     -->
    <!-- ============================================ -->
    <div class="hero-section-full">
        <div class="hero-frame">
            <?php foreach($slidesToShow as $idx => $portada): 
                $imgUrl = $portada['ruta_imagen'] ?? '';
                if (!empty($imgUrl) && !filter_var($imgUrl, FILTER_VALIDATE_URL)) {
                    $imgUrl = url('public/img/' . $imgUrl);
                }
            ?>
            <div class="hero-panel<?php echo $idx === 0 ? ' is-active' : ''; ?>" data-index="<?php echo $idx; ?>">
                <div class="hero-panel-bg" style="background-image: url('<?php echo htmlspecialchars($imgUrl); ?>');"></div>
                <div class="hero-panel-shade"></div>
                <div class="hero-panel-content">
                    <span class="hero-badge">SII IMPORTACIONES</span>
                    <h1 class="hero-title"><?php echo htmlspecialchars($portada['titulo'] ?? ''); ?></h1>
                    <?php if (!empty($portada['descripcion'])): ?>
                    <p class="hero-subtitle"><?php echo htmlspecialchars($portada['descripcion']); ?></p>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>

            <div class="hero-progress-track"><div class="hero-progress-fill is-running"></div></div>
            <button class="hero-nav hero-nav--prev" aria-label="Anterior">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="15 18 9 12 15 6"/>
                </svg>
            </button>
            <button class="hero-nav hero-nav--next" aria-label="Siguiente">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="9 18 15 12 9 6"/>
                </svg>
            </button>
            <div class="hero-thumbs">
                <?php foreach($slidesToShow as $idx => $portada): ?>
                <button class="hero-thumb<?php echo $idx === 0 ? ' is-on' : ''; ?>" data-index="<?php echo $idx; ?>">
                    <span class="hero-thumb-pip"></span>
                    <span class="hero-thumb-label"><?php echo htmlspecialchars($portada['titulo'] ?? ''); ?></span>
                </button>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="hero-divider">
            <svg viewBox="0 0 1440 80" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M0,40 C240,80 480,0 720,40 C960,80 1200,0 1440,40 L1440,80 L0,80 Z" fill="#0A1626"/>
            </svg>
        </div>
    </div>




<!-- Descripción Corporativa -->
<div class="text-center border-b border-primary-700/30 bg-primary-900 w-screen relative left-1/2 -translate-x-1/2 px-6 py-16">  <h1 class="text-3xl md:text-4xl font-extrabold text-primary-50 mb-3">Sii Importaciones</h1>
    <div class="w-12 h-0.5 bg-sky mx-auto mb-4 rounded-full opacity-60"></div>
    <p class="text-primary-200 text-sm md:text-base leading-relaxed max-w-3xl mx-auto italic">
        <?= e($empresaData['descripcion_corporativa'] ?? 'Sistema de votación para importaciones desde China') ?>
    </p>
    <div class="w-12 h-0.5 bg-sky mx-auto mt-4 rounded-full opacity-60"></div>
</div>

    <!-- Script del Slider -->
    <script>
    (function(){
        var frame = document.querySelector('.hero-frame');
        var panels = document.querySelectorAll('.hero-panel');
        var thumbs = document.querySelectorAll('.hero-thumb');
        var prevBtn = document.querySelector('.hero-nav--prev');
        var nextBtn = document.querySelector('.hero-nav--next');
        var bar = document.querySelector('.hero-progress-fill');
        if (!panels.length) return;
        var DURATION = 5000, current = 0, total = panels.length, timer = null, locked = false;
        function goTo(idx) {
            if (locked || idx === current || idx < 0 || idx >= total) return;
            locked = true;
            panels[current].classList.remove('is-active');
            thumbs[current].classList.remove('is-on');
            current = idx;
            panels[current].classList.add('is-active');
            thumbs[current].classList.add('is-on');
            bar.classList.remove('is-running');
            void bar.offsetWidth;
            setTimeout(function(){ bar.classList.add('is-running'); locked = false; }, 60);
        }
        function next() { goTo((current + 1) % total); }
        function prev() { goTo((current - 1 + total) % total); }
        function autoStart() { stopAuto(); bar.classList.add('is-running'); timer = setTimeout(function(){ next(); autoStart(); }, DURATION); }
        function stopAuto() { clearTimeout(timer); bar.classList.remove('is-running'); }
        if (nextBtn) nextBtn.addEventListener('click', function(){ next(); autoStart(); });
        if (prevBtn) prevBtn.addEventListener('click', function(){ prev(); autoStart(); });
        thumbs.forEach(function(t){ t.addEventListener('click', function(){ goTo(parseInt(this.getAttribute('data-index'))); autoStart(); }); });
        if (frame) {
            frame.addEventListener('mouseenter', stopAuto);
            frame.addEventListener('mouseleave', autoStart);
        }
        document.addEventListener('keydown', function(e){ if (e.key === 'ArrowLeft') { prev(); autoStart(); } if (e.key === 'ArrowRight') { next(); autoStart(); } });
        var touchX = 0;
        if (frame) {
            frame.addEventListener('touchstart', function(e){ touchX = e.touches[0].clientX; }, {passive: true});
            frame.addEventListener('touchend', function(e){ var diff = touchX - e.changedTouches[0].clientX; if (Math.abs(diff) > 50) { diff > 0 ? next() : prev(); autoStart(); } });
        }
        autoStart();
    })();
    </script>











<!-- ============================================ -->
<!-- CONTACTO - SECCIÓN MEJORADA                  -->
<!-- ============================================ -->
<div class="w-screen relative left-1/2 -translate-x-1/2 px-4 py-16 bg-gradient-to-b from-primary-900/80 via-primary-800/60 to-primary-900/80 backdrop-blur-sm border-y border-white/5">

    <div class="max-w-5xl mx-auto">

        <!-- Título sección -->
        <div class="text-center mb-12">
            <span class="text-xs font-black uppercase tracking-widest text-[#00c8d7] bg-[#00c8d7]/10 border border-[#00c8d7]/20 rounded-full px-4 py-1.5 inline-flex items-center gap-2">
                <i class="fa-solid fa-address-card"></i> Contáctanos
            </span>
            <h2 class="text-2xl md:text-3xl font-extrabold text-white mt-4">
                ¿Necesitas ayuda? <span class="text-[#00c8d7]">Escríbenos</span>
            </h2>
            <p class="text-primary-300 text-sm mt-2 max-w-xl mx-auto">
                Estamos disponibles para atender tus consultas y brindarte la mejor asesoría.
            </p>
            <div class="w-16 h-0.5 bg-gradient-to-r from-transparent via-[#00c8d7] to-transparent mx-auto mt-4 rounded-full"></div>
        </div>

        <!-- Grid de datos de contacto -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-10">

            <!-- Email -->
            <?php if (!empty($empresaData['email_principal'])): ?>
            <a href="mailto:<?= e($empresaData['email_principal']) ?>"
               class="group relative bg-primary-800/40 rounded-2xl border border-white/10 p-6 text-center hover:border-[#00c8d7]/50 hover:bg-primary-800/60 hover:-translate-y-1 transition-all duration-300 cursor-pointer overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-b from-[#00c8d7]/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                <div class="w-14 h-14 rounded-xl bg-[#00c8d7]/10 border border-[#00c8d7]/20 flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform duration-300">
                    <i class="fa-solid fa-envelope text-[#00c8d7] text-xl"></i>
                </div>
                <p class="text-[10px] font-black uppercase tracking-widest text-primary-400 mb-1">Email</p>
                <p class="text-primary-200 group-hover:text-white text-sm transition-colors duration-200 break-all">
                    <?= e($empresaData['email_principal']) ?>
                </p>
            </a>
            <?php endif; ?>

            <!-- Dirección -->
            <?php if (!empty($empresaData['direccion_textual'])): ?>
            <div class="group relative bg-primary-800/40 rounded-2xl border border-white/10 p-6 text-center hover:border-[#00c8d7]/50 hover:bg-primary-800/60 hover:-translate-y-1 transition-all duration-300 overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-b from-[#00c8d7]/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                <div class="w-14 h-14 rounded-xl bg-[#00c8d7]/10 border border-[#00c8d7]/20 flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform duration-300">
                    <i class="fa-solid fa-location-dot text-[#00c8d7] text-xl"></i>
                </div>
                <p class="text-[10px] font-black uppercase tracking-widest text-primary-400 mb-1">Dirección</p>
                <p class="text-primary-200 text-sm leading-relaxed">
                    <?= nl2br(e($empresaData['direccion_textual'])) ?>
                </p>
            </div>
            <?php endif; ?>

            <!-- WhatsApp -->
            <?php if (!empty($whatsappMostrar)): ?>
            <a href="https://wa.me/<?= $whatsappNumero ?>" target="_blank"
               class="group relative bg-primary-800/40 rounded-2xl border border-white/10 p-6 text-center hover:border-[#25D366]/50 hover:bg-primary-800/60 hover:-translate-y-1 transition-all duration-300 cursor-pointer overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-b from-[#25D366]/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                <div class="w-14 h-14 rounded-xl bg-[#25D366]/10 border border-[#25D366]/20 flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform duration-300">
                    <i class="fa-brands fa-whatsapp text-[#25D366] text-2xl"></i>
                </div>
                <p class="text-[10px] font-black uppercase tracking-widest text-primary-400 mb-1">WhatsApp</p>
                <p class="text-primary-200 group-hover:text-white text-sm transition-colors duration-200">
                    <?= e($whatsappMostrar) ?>
                </p>
            </a>
            <?php endif; ?>

            <!-- Google Maps -->
            <?php if (!empty($empresaData['enlace_gps'])): ?>
            <a href="<?= e($empresaData['enlace_gps']) ?>" target="_blank" rel="noopener noreferrer"
               class="group relative bg-primary-800/40 rounded-2xl border border-white/10 p-6 text-center hover:border-[#00c8d7]/50 hover:bg-primary-800/60 hover:-translate-y-1 transition-all duration-300 cursor-pointer overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-b from-[#00c8d7]/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                <div class="w-14 h-14 rounded-xl bg-[#00c8d7]/10 border border-[#00c8d7]/20 flex items-center justify-center mx-auto mb-3 group-hover:scale-110 transition-transform duration-300">
                    <i class="fa-solid fa-map-pin text-[#00c8d7] text-xl"></i>
                </div>
                <p class="text-[10px] font-black uppercase tracking-widest text-primary-400 mb-1">Ubicación</p>
                <p class="text-primary-200 group-hover:text-white text-sm transition-colors duration-200 inline-flex items-center justify-center gap-1">
                    Ver en Google Maps
                    <i class="fa-solid fa-arrow-up-right-from-square text-[10px] group-hover:translate-x-0.5 group-hover:-translate-y-0.5 transition-transform duration-200"></i>
                </p>
            </a>
            <?php endif; ?>

        </div>

        <!-- Redes Sociales -->
        <?php if (!empty($empresaData['facebook']) || !empty($empresaData['instagram']) || !empty($empresaData['tiktok']) || !empty($empresaData['youtube'])): ?>
        <div class="pt-8 border-t border-white/5 text-center">
            <p class="text-[10px] font-black uppercase tracking-widest text-primary-400 mb-5 flex items-center justify-center gap-2">
                <span class="w-8 h-px bg-gradient-to-r from-transparent to-[#00c8d7]/40"></span>
                <i class="fa-solid fa-share-nodes text-[#00c8d7]"></i>
                Síguenos en redes
                <span class="w-8 h-px bg-gradient-to-l from-transparent to-[#00c8d7]/40"></span>
            </p>
            <div class="flex flex-wrap items-center justify-center gap-3">
                <?php if (!empty($empresaData['facebook'])): ?>
                    <a href="<?= e($empresaData['facebook']) ?>" target="_blank" rel="noopener noreferrer"
                       class="w-12 h-12 rounded-xl flex items-center justify-center text-white bg-primary-800/50 border border-white/10 hover:-translate-y-1.5 hover:bg-[#1877F2]/20 hover:border-[#1877F2]/50 hover:text-[#1877F2] transition-all duration-300 shadow-lg hover:shadow-[#1877F2]/10">
                        <i class="fa-brands fa-facebook-f text-lg"></i>
                    </a>
                <?php endif; ?>
                <?php if (!empty($empresaData['instagram'])): ?>
                    <a href="<?= e($empresaData['instagram']) ?>" target="_blank" rel="noopener noreferrer"
                       class="w-12 h-12 rounded-xl flex items-center justify-center text-white bg-primary-800/50 border border-white/10 hover:-translate-y-1.5 hover:bg-[#E1306C]/20 hover:border-[#E1306C]/50 hover:text-[#E1306C] transition-all duration-300 shadow-lg hover:shadow-[#E1306C]/10">
                        <i class="fa-brands fa-instagram text-lg"></i>
                    </a>
                <?php endif; ?>
                <?php if (!empty($empresaData['tiktok'])): ?>
                    <a href="<?= e($empresaData['tiktok']) ?>" target="_blank" rel="noopener noreferrer"
                       class="w-12 h-12 rounded-xl flex items-center justify-center text-white bg-primary-800/50 border border-white/10 hover:-translate-y-1.5 hover:bg-white/10 hover:border-white/40 hover:text-white transition-all duration-300 shadow-lg hover:shadow-white/5">
                        <i class="fa-brands fa-tiktok text-lg"></i>
                    </a>
                <?php endif; ?>
                <?php if (!empty($empresaData['youtube'])): ?>
                    <a href="<?= e($empresaData['youtube']) ?>" target="_blank" rel="noopener noreferrer"
                       class="w-12 h-12 rounded-xl flex items-center justify-center text-white bg-primary-800/50 border border-white/10 hover:-translate-y-1.5 hover:bg-[#FF0000]/20 hover:border-[#FF0000]/50 hover:text-[#FF0000] transition-all duration-300 shadow-lg hover:shadow-[#FF0000]/10">
                        <i class="fa-brands fa-youtube text-lg"></i>
                    </a>
                <?php endif; ?>
                <?php if (!empty($whatsappMostrar)): ?>
                    <a href="https://wa.me/<?= $whatsappNumero ?>" target="_blank" rel="noopener noreferrer"
                       class="w-12 h-12 rounded-xl flex items-center justify-center text-white bg-primary-800/50 border border-white/10 hover:-translate-y-1.5 hover:bg-[#25D366]/20 hover:border-[#25D366]/50 hover:text-[#25D366] transition-all duration-300 shadow-lg hover:shadow-[#25D366]/10">
                        <i class="fa-brands fa-whatsapp text-lg"></i>
                    </a>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>

    </div>
</div>






    <!-- ============================================ -->
    <!-- SECCIÓN DE ACCESO                           -->
    <!-- ============================================ -->
    <div class="max-w-6xl mx-auto px-4">
        <div class="max-w-4xl mx-auto mt-8">
            <?php if (auth()): ?>
            <div id="acceso" class="rounded-2xl overflow-hidden border border-white/20 shadow-2xl bg-primary-900/80 backdrop-blur-sm">

                <div class="relative h-48 overflow-hidden">
                    <img src="<?= url('public/img/portadaAlibaba.webp') ?>" alt="Portada Alibaba"
                        class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-primary-900/90 via-primary-900/30 to-transparent"></div>
                    <div class="absolute bottom-4 left-0 right-0 text-center">
                        <span class="text-xs font-black tracking-widest text-[#00eeff] uppercase">Comunidad de importadores</span>
                    </div>
                </div>

                <div class="p-6 text-center">
                    <h2 class="text-2xl font-extrabold text-white mb-2 leading-tight">
                        Explora los mejores<br>
                        <span class="text-[#00eeff]">productos de Alibaba</span>
                    </h2>
                    <p class="text-primary-50/80 text-sm mb-6">
                        Descubre, vota y publica los productos más interesantes de la comunidad.
                    </p>

                    <div class="w-12 h-0.5 bg-[#00eeff]/40 mx-auto mb-6 rounded-full"></div>

                    <div class="flex flex-wrap justify-center gap-3">
                        <a href="<?= url('dashboard') ?>"
                        class="inline-flex items-center gap-2 text-white font-bold px-5 py-2.5 rounded-xl border border-white/20 bg-primary-700/60 hover:bg-primary-600/80 transition-all duration-300">
                            <i class="fa-solid fa-box text-[#00eeff]"></i>
                            Ver Productos
                        </a>
                        <a href="<?= url('productos/crear') ?>"
                        class="inline-flex items-center gap-2 font-bold px-5 py-2.5 rounded-xl text-white bg-gradient-to-r from-[#00c8d7] to-primary-500 hover:from-primary-900 hover:to-[#00c8d7] transition-all duration-300 ease-in-out [text-shadow:0_1px_4px_rgba(0,0,0,0.4)]">
                            <i class="fa-solid fa-plus"></i>
                            Publicar Producto
                        </a>
                    </div>
                </div>
            </div>
            <?php else: ?>
            <div id="acceso" class="rounded-2xl overflow-hidden border border-white/20 shadow-2xl bg-primary-900/80 backdrop-blur-sm scroll-mt-20">
                <div class="relative h-48 overflow-hidden">
                    <img src="<?= url('public/img/portadaAlibaba.webp') ?>" alt="Portada Alibaba"
                        class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-primary-900/90 via-primary-900/30 to-transparent"></div>
                    <div class="absolute bottom-4 left-0 right-0 text-center">
                        <span class="text-xs font-black tracking-widest text-[#00eeff] uppercase">Bienvenido</span>
                    </div>
                </div>

                <div class="px-6 pt-5 pb-4 text-center border-b border-white/10">
                    <h2 class="text-2xl font-extrabold text-white leading-tight">
                        Accede al<br>
                        <span class="text-[#00eeff]">Sistema</span>
                    </h2>
                    <p class="text-primary-50/80 text-sm mt-2">
                        Ingresa tu correo electrónico para comenzar a votar y publicar productos.
                    </p>
                </div>

                <div class="p-6">
                    <?php if ($flash = getFlash()): ?>
                        <div class="mb-4 rounded-xl px-4 py-3 text-sm font-semibold border
                            <?= $flash['tipo'] === 'exito'
                                ? 'bg-green-900/40 text-green-300 border-green-500/30'
                                : 'bg-red-900/40 text-red-300 border-red-500/30' ?>">
                            <?= e($flash['mensaje']) ?>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="<?= $_SERVER['PHP_SELF'] ?>" class="max-w-md mx-auto space-y-4">
                        <div>
                            <input type="email" name="email" required
                                placeholder="tu@email.com"
                                class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-3 text-white text-center placeholder:text-white/40 focus:outline-none focus:ring-2 focus:ring-[#00eeff]/50 focus:border-[#00eeff]/50 transition-all duration-200">
                        </div>
                        <button class="w-full inline-flex items-center justify-center gap-2 font-bold py-3 rounded-xl text-white bg-gradient-to-r from-[#00eeff] to-primary-500 hover:from-primary-900 hover:to-[#00eeff] transition-all duration-300 ease-in-out [text-shadow:0_1px_4px_rgba(0,0,0,0.4)]">
                            <i class="fa-solid fa-right-to-bracket"></i>
                            Acceder
                        </button>
                    </form>

                    <div class="w-12 h-0.5 bg-[#00eeff]/40 mx-auto my-5 rounded-full"></div>

                    <p class="text-center text-xs text-primary-50/50 flex items-center justify-center gap-1">
                        <i class="fa-solid fa-circle-info text-[#00eeff]/60"></i>
                        Al ingresar tu correo, se creará automáticamente tu cuenta si no existe.
                    </p>

                    <div class="mt-5 pt-4 border-t border-white/10 text-center">
                        <a href="<?= url('auth/login') ?>"
                        class="inline-flex items-center gap-2 text-sm font-semibold text-primary-50/70 hover:text-[#00eeff] transition-colors duration-200">
                            <i class="fa-solid fa-lock text-xs"></i>
                            ¿Eres administrador? Inicia sesión aquí
                        </a>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

                





  <div class="w-screen relative left-1/2 -translate-x-1/2 overflow-hidden mt-6 -mb-6">

    <div class="relative h-[500px] md:h-[600px]">
        <img src="<?= url('public/img/imgServicio.jpg') ?>"
             alt="Servicios SII Importaciones"
             class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-r from-primary-900/95 via-primary-900/40 to-primary-900/95"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-primary-900/90 via-transparent to-primary-900/30"></div>

        <div class="absolute inset-0 flex flex-col items-center justify-center text-center px-6">
            <span class="inline-block text-xs font-semibold uppercase tracking-widest text-[#00c8d7] mb-3 border border-[#00c8d7]/30 rounded-full px-4 py-1 bg-primary-900/40 backdrop-blur-sm">
                <i class="fa-solid fa-briefcase mr-1"></i> Nuestros Servicios
            </span>
            <h2 class="text-3xl md:text-5xl font-extrabold text-white mb-4 leading-tight drop-shadow-lg">
                Soluciones integrales para<br>
                <span class="text-[#00c8d7]">tus importaciones</span>
            </h2>
            <p class="text-primary-100/80 text-sm md:text-base leading-relaxed max-w-2xl mb-7">
                En SII Importaciones ofrecemos servicios especializados para facilitar tus importaciones desde China.
                Desde la búsqueda de proveedores hasta la logística internacional, te acompañamos en cada paso.
            </p>
            <a href="<?= url('servicios') ?>"
               class="inline-flex items-center gap-2 font-bold px-7 py-3 rounded-xl text-white bg-gradient-to-r from-[#00c8d7] to-primary-500 hover:from-primary-900 hover:to-[#00c8d7] transition-all duration-300 ease-in-out [text-shadow:0_1px_4px_rgba(0,0,0,0.4)] shadow-lg shadow-[#00c8d7]/20">
                <i class="fa-solid fa-arrow-right"></i>
                Ver Servicios
            </a>
        </div>
    </div>

</div>




    </div>


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