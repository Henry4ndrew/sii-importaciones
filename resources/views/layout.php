<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($titulo ?? 'Sistema de Votación') ?> | SII Importaciones</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= url('public/css/slider.css') ?>">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: "#E6EDF5",
                            100: "#B8CCE3",
                            200: "#8AAAD1",
                            300: "#5D89BF",
                            400: "#3E6FA8",
                            500: "#2F5A8A",
                            600: "#25496F",
                            700: "#1C3956",
                            800: "#12283D",
                            900: "#0A1626"
                        },
                        sky: {
                            DEFAULT: "#00eeff",
                        }
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .facebook-hover:hover {
            background: rgba(24, 119, 242, 0.15);
            border-color: #1877F2;
            color: #1877F2;
            box-shadow: 0 8px 20px rgba(24, 119, 242, 0.25);
        }
        .instagram-hover:hover {
            background: rgba(225, 48, 108, 0.15);
            border-color: #E1306C;
            color: #E1306C;
            box-shadow: 0 8px 20px rgba(225, 48, 108, 0.25);
        }
        .tiktok-hover:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.5);
            color: #ffffff;
            box-shadow: 0 8px 20px rgba(255, 255, 255, 0.15);
        }
        .youtube-hover:hover {
            background: rgba(255, 0, 0, 0.15);
            border-color: #FF0000;
            color: #FF0000;
            box-shadow: 0 8px 20px rgba(255, 0, 0, 0.25);
        }
        .whatsapp-hover:hover {
            background: rgba(37, 211, 102, 0.15);
            border-color: #25D366;
            color: #25D366;
            box-shadow: 0 8px 20px rgba(37, 211, 102, 0.25);
        }
        .footer-link {
            transition: all 0.2s ease;
        }
        .footer-link:hover {
            color: #ffffff;
            padding-left: 4px;
        }
        /* Header principal fijo */
        .main-header {
            position: sticky;
            top: 0;
            z-index: 9999;
        }
        /* Sub-header fijo justo debajo del header principal */
        .sub-header {
            position: sticky;
            top: 64px; /* Altura del header en móvil (h-16) */
            z-index: 9998;
            background: rgba(10, 22, 38, 0.95);
            backdrop-filter: blur(8px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            transition: top 0.3s ease;
        }
        @media (min-width: 768px) {
            .sub-header {
                top: 80px; /* Altura del header en escritorio (h-20) */
            }
        }
        @media (max-width: 640px) {
            .sub-header {
                top: 60px;
            }
        }
    </style>
</head>
<body class="min-h-screen flex flex-col" style="background-color: #12283D; background-image: url('<?= url('public/img/fondo.avif') ?>'); background-size: cover; background-position: center; background-attachment: fixed; background-repeat: no-repeat;">
       <div style="position: fixed; inset: 0; background: rgba(18, 40, 61, 0.75); z-index: 0; pointer-events: none;"></div>

<?php
// ============================================
// CARGAR DATOS DE EMPRESA
// ============================================
$empresaData = null;

try {
    if (function_exists('db')) {
        $db = db();
        $stmt = $db->prepare("SELECT * FROM empresa LIMIT 1");
        $stmt->execute();
        $empresaData = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$empresaData) {
            $insertStmt = $db->prepare("
                INSERT INTO empresa (
                    descripcion_corporativa, 
                    email_principal, 
                    direccion_textual, 
                    enlace_gps, 
                    facebook, 
                    instagram, 
                    whatsapp
                ) VALUES (?, ?, ?, ?, ?, ?, ?)
            ");
            $insertStmt->execute([
                'SII Importaciones - Sistema de votación para importaciones desde China',
                'contacto@willsimport.com',
                'Av. Principal 123, Lima, Perú',
                'https://maps.google.com/',
                'https://facebook.com/siiimportaciones',
                'https://instagram.com/siiimportaciones',
                '76543210'
            ]);
            
            $stmt = $db->prepare("SELECT * FROM empresa LIMIT 1");
            $stmt->execute();
            $empresaData = $stmt->fetch(PDO::FETCH_ASSOC);
        }
    }
} catch (Exception $e) {
    $empresaData = null;
}

if (!$empresaData || empty($empresaData)) {
    $empresaData = [
        'descripcion_corporativa' => 'SII Importaciones - Sistema de votación para importaciones desde China',
        'email_principal' => 'contacto@willsimport.com',
        'direccion_textual' => 'Av. Principal 123, Lima, Perú',
        'enlace_gps' => 'https://maps.google.com/',
        'facebook' => 'https://facebook.com/siiimportaciones',
        'instagram' => 'https://instagram.com/siiimportaciones',
        'tiktok' => '',
        'youtube' => '',
        'whatsapp' => '76543210',
    ];
}

$whatsappNumero = '';
$whatsappMostrar = '';
if (!empty($empresaData['whatsapp'])) {
    $whatsappNumero = preg_replace('/[^0-9]/', '', $empresaData['whatsapp']);
    if (strpos($empresaData['whatsapp'], '+591') === 0) {
        $whatsappMostrar = $empresaData['whatsapp'];
    } else {
        $whatsappMostrar = '+591 ' . $whatsappNumero;
    }
}
?>

<!-- ============================================ -->
<!-- HEADER PRINCIPAL (z-index: 9999)              -->
<!-- ============================================ -->
<header class="main-header bg-primary-800 shadow-lg border-b border-primary-700/50">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-wrap items-center justify-between py-3 md:py-4 gap-2">
            
            <!-- LOGO -->
            <a href="<?= url('/') ?>" class="flex items-center gap-2 group bg-white/5 p-2 rounded-xl hover:bg-white/10 transition-all duration-300">
                <img src="<?= url('public/img/logo-SII.avif') ?>" 
                     alt="Logo SII Importaciones" 
                     class="h-10 md:h-12 transition-transform group-hover:scale-105">
            </a>

            <!-- MENÚ DESKTOP -->
            <nav class="hidden md:flex items-center gap-4 lg:gap-6">
                <a href="<?= url('/') ?>" class="text-white hover:text-sky transition-colors duration-200 font-medium flex items-center gap-2">
                    <i class="fa-solid fa-house text-sm text-primary-50 hover:text-sky"></i>
                    <span>Inicio</span>
                </a>
                <a href="<?= url('conocenos') ?>" class="text-white hover:text-sky transition-colors duration-200 font-medium flex items-center gap-2">
                    <i class="fa-solid fa-circle-info text-sm text-primary-50 hover:text-sky"></i>
                    <span>Conócenos</span>
                </a>
                <a href="<?= url('servicios') ?>" class="text-white hover:text-sky transition-colors duration-200 font-medium flex items-center gap-2">
                    <i class="fa-solid fa-briefcase text-sm text-primary-50 hover:text-sky"></i>
                    <span>Servicios</span>
                </a>
                <a href="<?= url('contactos') ?>" class="text-white hover:text-sky transition-colors duration-200 font-medium flex items-center gap-2">
                    <i class="fa-solid fa-address-book text-sm text-primary-50 hover:text-sky"></i>
                    <span>Contactos</span>
                </a>
            </nav>

            <!-- LOGIN / PERFIL DESKTOP -->
            <div class="hidden md:flex items-center gap-3">
                <?php if (auth()): ?>
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center gap-2 text-white hover:text-sky transition-colors duration-200 bg-white/5 px-4 py-2 rounded-lg border border-white/10">
                            <i class="fas fa-user-circle text-xl text-sky"></i>
                            <span class="font-medium text-sm max-w-[120px] truncate"><?= e(auth()['email']) ?></span>
                            <i class="fas fa-chevron-down text-xs"></i>
                        </button>
                        <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl py-2 border border-gray-100" style="display: none;">
                            <a href="<?= url('logout') ?>" class="block px-4 py-2 text-red-600 hover:bg-red-50 transition-colors">
                                <i class="fas fa-sign-out-alt mr-2"></i> Cerrar Sesión
                            </a>
                        </div>
                    </div>
                <?php else: ?>
                    <a href="<?= url('/') ?>#acceso" class="bg-white text-primary-800 hover:bg-primary-50 px-6 py-2 rounded-lg transition-all duration-200 flex items-center gap-2 shadow-md hover:shadow-lg font-medium">
                        <i class="fas fa-sign-in-alt"></i>
                        <span>Iniciar sesión</span>
                    </a>
                <?php endif; ?>
            </div>

            <!-- BOTÓN MENÚ MÓVIL -->
            <button id="mobile-menu-button" class="md:hidden text-white hover:text-primary-200 focus:outline-none bg-white/5 p-2 rounded-lg">
                <i class="fas fa-bars text-2xl"></i>
            </button>
        </div>
    </div>

    <!-- MENÚ MÓVIL (Off-canvas) -->
    <div id="mobile-menu" class="fixed inset-0 z-50 transform translate-x-full transition-transform duration-300 ease-in-out md:hidden">
        <div class="absolute inset-0 bg-black bg-opacity-50" id="mobile-menu-overlay"></div>
        <div class="absolute right-0 top-0 h-full w-72 bg-primary-900 shadow-2xl overflow-y-auto">
            <div class="p-4 border-b border-primary-800 flex justify-between items-center">
                <div class="flex items-center gap-2">
                    <img src="<?= url('public/img/logo-SII.avif') ?>" alt="Logo" class="h-8 w-auto">
                    <span class="font-bold text-white">SII</span>
                </div>
                <button id="close-menu" class="text-primary-300 hover:text-white">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <nav class="flex flex-col p-4">
                <?php if (auth()): ?>
                    <div class="mb-4 pb-4 border-b border-primary-800">
                        <div class="flex items-center gap-2 text-primary-300">
                            <i class="fas fa-user-circle text-2xl text-sky"></i>
                            <span class="font-medium text-white text-sm truncate"><?= e(auth()['email']) ?></span>
                        </div>
                    </div>
                <?php endif; ?>

                <a href="<?= url('/') ?>" class="py-3 text-primary-200 hover:text-sky hover:bg-primary-800 px-3 rounded-lg transition-colors flex items-center gap-3">
                    <i class="fa-solid fa-house text-sm"></i>
                    <span>Inicio</span>
                </a>
                <a href="<?= url('conocenos') ?>" class="py-3 text-primary-200 hover:text-sky hover:bg-primary-800 px-3 rounded-lg transition-colors flex items-center gap-3">
                    <i class="fa-solid fa-circle-info text-sm hover:text-sky"></i>
                    <span>Conócenos</span>
                </a>
                <a href="<?= url('servicios') ?>" class="py-3 text-primary-200 hover:text-sky hover:bg-primary-800 px-3 rounded-lg transition-colors flex items-center gap-3">
                    <i class="fa-solid fa-briefcase text-sm hover:text-sky"></i>
                    <span>Servicios</span>
                </a>
                <a href="<?= url('contactos') ?>" class="py-3 text-primary-200 hover:text-sky hover:bg-primary-800 px-3 rounded-lg transition-colors flex items-center gap-3">
                    <i class="fa-solid fa-address-book text-sm hover:text-sky"></i>
                    <span>Contactos</span>
                </a>




                <?php if (auth()): ?>
                    <hr class="my-3 border-primary-800">
                    <div class="flex flex-col gap-2 mt-2">
                        <a href="<?= url('dashboard') ?>" class="w-full text-center px-3 py-2 rounded-lg bg-primary-700/50 hover:bg-primary-600 text-sm font-semibold text-primary-200 hover:text-white transition">
                            <i class="fas fa-box text-xs"></i><span class="ml-1"> Productos</span>
                        </a>
                        <a href="<?= url('ranking') ?>" class="w-full text-center px-3 py-2 rounded-lg bg-primary-700/50 hover:bg-primary-600 text-sm font-semibold text-primary-200 hover:text-white transition">
                            <i class="fas fa-trophy text-xs"></i><span class="ml-1"> Más Votados</span>
                        </a>
                        <a href="<?= url('productos/crear') ?>" class="w-full text-center px-3 py-2 rounded-lg gap-2 font-bold px-5 py-2.5 rounded-xl text-white bg-gradient-to-r from-[#00c8d7] to-primary-500 hover:from-primary-900 hover:to-[#00c8d7] transition-all duration-300 ease-in-out [text-shadow:0_1px_4px_rgba(0,0,0,0.4)]">
                            <i class="fa-solid fa-plus text-xs"></i>
                            <span class="ml-1"> Publicar Producto</span>
                        </a>
                    </div>
                    <hr class="my-3 border-primary-800">
                    <a href="<?= url('logout') ?>" class="py-3 text-red-300 hover:text-white hover:bg-red-800 px-3 rounded-lg transition-colors flex items-center gap-3">
                        <i class="fas fa-sign-out-alt"></i>
                        <span> Cerrar Sesión</span>
                    </a>
                <?php else: ?>
                    <hr class="my-3 border-primary-800">
                    <a href="<?= url('/') ?>#acceso" class="py-3 bg-primary-700 hover:bg-primary-600 text-white px-3 rounded-lg transition-colors flex items-center justify-center gap-2">
                        <i class="fas fa-sign-in-alt"></i>
                        <span>Iniciar sesión</span>
                    </a>
                <?php endif; ?>
            </nav>
        </div>
    </div>
</header>

<!-- ============================================ -->
<!-- SUB-HEADER: BOTONES DE PRODUCTOS (z-index: 9998) -->
<!-- ============================================ -->
<?php if (auth()): ?>
<div class="sub-header">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 pt-2.5">
        <div class="flex flex-wrap items-center justify-center gap-3 py-3 md:py-4">
            <a href="<?= url('dashboard') ?>" class="px-5 py-2 md:px-6 md:py-2.5 rounded-lg bg-primary-700/50 hover:bg-primary-600 text-sm font-semibold text-primary-200 hover:text-white transition-all duration-200 flex items-center gap-2">
                <i class="fas fa-box text-xs"></i>
                <span>Productos</span>
            </a>
            <a href="<?= url('ranking') ?>" class="px-5 py-2 md:px-6 md:py-2.5 rounded-lg bg-primary-700/50 hover:bg-primary-600 text-sm font-semibold text-primary-200 hover:text-white transition-all duration-200 flex items-center gap-2">
                <i class="fas fa-trophy text-xs"></i>
                <span>Más Votados</span>
            </a>
            <a href="<?= url('productos/crear') ?>" class="px-5 py-2 md:px-6 md:py-2.5 rounded-lg inline-flex items-center gap-2 font-bold px-5 py-2.5 rounded-xl text-white bg-gradient-to-r from-[#00c8d7] to-primary-500 hover:from-primary-900 hover:to-[#00c8d7] transition-all duration-300 ease-in-out [text-shadow:0_1px_4px_rgba(0,0,0,0.4)]">
                <i class="fa-solid fa-plus text-xs"></i>
                <span>Publicar Producto</span>
            </a>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- ============================================ -->
<!-- MENSAJE FLASH                                -->
<!-- ============================================ -->
<!-- ============================================ -->
<!-- MENSAJE FLASH                                -->
<!-- ============================================ -->
<?php if ($flash = getFlash()): ?>
    <div id="flash-message" class="fixed top-24 right-6 z-[9999] max-w-sm w-full px-4 animate-slideInRight">
        <div class="rounded-2xl px-6 py-4 text-sm font-semibold shadow-2xl backdrop-blur-lg border flex items-center gap-3
            <?= $flash['tipo'] === 'exito' 
                ? 'bg-green-500/90 text-white border-green-400/50' 
                : 'bg-red-500/90 text-white border-red-400/50' ?>">
            <span class="text-xl flex-shrink-0">
                <?= $flash['tipo'] === 'exito' ? '✅' : '❌' ?>
            </span>
            <span class="flex-1"><?= e($flash['mensaje']) ?></span>
            <button onclick="this.closest('#flash-message').remove()" class="text-white/70 hover:text-white transition flex-shrink-0">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>

    <style>
        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(80px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        @keyframes slideOutRight {
            from {
                opacity: 1;
                transform: translateX(0);
            }
            to {
                opacity: 0;
                transform: translateX(80px);
            }
        }
        .animate-slideInRight {
            animation: slideInRight 0.5s cubic-bezier(0.22, 0.61, 0.36, 1) forwards;
        }
        .animate-slideOutRight {
            animation: slideOutRight 0.4s cubic-bezier(0.22, 0.61, 0.36, 1) forwards;
        }
    </style>

    <script>
        // Auto-cerrar después de 4 segundos
        setTimeout(function() {
            const flash = document.getElementById('flash-message');
            if (flash) {
                flash.classList.remove('animate-slideInRight');
                flash.classList.add('animate-slideOutRight');
                setTimeout(function() {
                    flash.remove();
                }, 500);
            }
        }, 4000);
    </script>
<?php endif; ?>

<!-- ============================================ -->
<!-- BOTÓN WHATSAPP FLOTANTE                      -->
<!-- ============================================ -->

<?php if (!empty($whatsappNumero)): ?>
<style>
    .whatsapp-float-minimal {
        position: fixed;
        bottom: 24px;
        right: 24px;
        z-index: 9999;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        background: #25D366;
        color: white;
        text-decoration: none;
        box-shadow: 0 4px 20px rgba(37, 211, 102, 0.35);
        transition: all 0.3s ease;
        animation: float 3s ease-in-out infinite;
    }

    .whatsapp-float-minimal:hover {
        transform: scale(1.1);
        box-shadow: 0 8px 30px rgba(37, 211, 102, 0.5);
        color: white;
    }

    .whatsapp-float-minimal i {
        font-size: 32px;
        filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.2));
    }

    @keyframes float {
        0%, 100% {
            transform: translateY(0px);
        }
        50% {
            transform: translateY(-8px);
        }
    }

    @media (max-width: 768px) {
        .whatsapp-float-minimal {
            width: 54px;
            height: 54px;
            bottom: 16px;
            right: 16px;
        }
        .whatsapp-float-minimal i {
            font-size: 28px;
        }
    }
</style>

<a href="https://wa.me/<?= $whatsappNumero ?>?text=<?= urlencode('¡Hola! Me gustaría obtener información sobre SII Importaciones.') ?>" 
   target="_blank" 
   class="whatsapp-float-minimal"
   aria-label="Contactar por WhatsApp">
    <i class="fa-brands fa-whatsapp"></i>
</a>
<?php endif; ?>


<!-- ============================================ -->
<!-- CONTENIDO PRINCIPAL                          -->
<!-- ============================================ -->
 
<main class="max-w-6xl mx-auto px-4 py-6 w-full flex-1" style="position: relative; z-index: 1; display: flex; flex-direction: column; min-height: 100vh; width: 100%;">
    <?= $contenido ?>
</main>

<!-- ============================================ -->
<!-- FOOTER PROFESIONAL                           -->
<!-- ============================================ -->
<footer class="relative bg-primary-900 text-gray-400 border-t border-primary-800">
    <div class="max-w-6xl mx-auto px-6 py-12">
        
        <!-- Grid principal -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            
            <!-- Columna 1: Logo + Redes Sociales -->
            <div class="space-y-4">
                <div class="flex items-center gap-3">
                    <img src="<?= url('public/img/logo-SII.avif') ?>" alt="Logo" class="h-12 w-auto">
                </div>
                
                <div class="flex gap-3 pt-2">
                    <?php if (!empty($empresaData['facebook'])): ?>
                        <a href="<?= e($empresaData['facebook']) ?>" target="_blank" rel="noopener" 
                           class="w-10 h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-gray-400 transition-all duration-300 hover:-translate-y-1 facebook-hover">
                            <i class="fa-brands fa-facebook-f"></i>
                        </a>
                    <?php endif; ?>
                    <?php if (!empty($empresaData['instagram'])): ?>
                        <a href="<?= e($empresaData['instagram']) ?>" target="_blank" rel="noopener" 
                           class="w-10 h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-gray-400 transition-all duration-300 hover:-translate-y-1 instagram-hover">
                            <i class="fa-brands fa-instagram"></i>
                        </a>
                    <?php endif; ?>
                    <?php if (!empty($empresaData['tiktok'])): ?>
                        <a href="<?= e($empresaData['tiktok']) ?>" target="_blank" rel="noopener" 
                           class="w-10 h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-gray-400 transition-all duration-300 hover:-translate-y-1 tiktok-hover">
                            <i class="fa-brands fa-tiktok"></i>
                        </a>
                    <?php endif; ?>
                    <?php if (!empty($empresaData['youtube'])): ?>
                        <a href="<?= e($empresaData['youtube']) ?>" target="_blank" rel="noopener" 
                           class="w-10 h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-gray-400 transition-all duration-300 hover:-translate-y-1 youtube-hover">
                            <i class="fa-brands fa-youtube"></i>
                        </a>
                    <?php endif; ?>
                    <?php if (!empty($whatsappMostrar)): ?>
                        <a href="https://wa.me/<?= $whatsappNumero ?>" target="_blank" rel="noopener" 
                           class="w-10 h-10 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-gray-400 transition-all duration-300 hover:-translate-y-1 whatsapp-hover">
                            <i class="fa-brands fa-whatsapp"></i>
                        </a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Columna 2: Navegación -->
            <div>
                <h4 class="text-xs font-bold uppercase tracking-wider text-gray-300 mb-5 pb-2 border-b border-white/5 relative">
                    Navegación
                    <span class="absolute bottom-[-1px] left-0 w-8 h-[2px] bg-sky rounded-full"></span>
                </h4>
                <ul class="space-y-2.5">
                    <li><a href="<?= url('/') ?>" class="footer-link text-gray-400 hover:text-white text-sm flex items-center gap-2">
                        <i class="fa-solid fa-chevron-right text-[10px] text-sky"></i> Inicio
                    </a></li>
                    <li><a href="<?= url('conocenos') ?>" class="footer-link text-gray-400 hover:text-white text-sm flex items-center gap-2">
                        <i class="fa-solid fa-chevron-right text-[10px] text-sky"></i> Conócenos
                    </a></li>
                    <li><a href="<?= url('servicios') ?>" class="footer-link text-gray-400 hover:text-white text-sm flex items-center gap-2">
                        <i class="fa-solid fa-chevron-right text-[10px] text-sky"></i> Servicios
                    </a></li>
                    <li><a href="<?= url('contactos') ?>" class="footer-link text-gray-400 hover:text-white text-sm flex items-center gap-2">
                        <i class="fa-solid fa-chevron-right text-[10px] text-sky"></i> Contactos
                    </a></li>
                </ul>
            </div>

            <!-- Columna 3: Contacto -->
            <div>
                <h4 class="text-xs font-bold uppercase tracking-wider text-gray-300 mb-5 pb-2 border-b border-white/5 relative">
                    Contacto
                    <span class="absolute bottom-[-1px] left-0 w-8 h-[2px] bg-sky rounded-full"></span>
                </h4>
                <ul class="space-y-3">
                    <?php if (!empty($whatsappMostrar)): ?>
                        <li class="flex items-center gap-3 text-sm">
                            <span class="w-8 h-8 rounded-xl bg-[#00eeff]/10 border border-[#00eeff]/20 flex items-center justify-center text-[#00eeff] flex-shrink-0">
                                <i class="fa-brands fa-whatsapp text-lg"></i>
                            </span>
                            <a href="https://wa.me/<?= $whatsappNumero ?>" target="_blank" class="text-gray-400 hover:text-white transition-colors">
                                <?= e($whatsappMostrar) ?>
                            </a>
                        </li>
                    <?php endif; ?>
                    <?php if (!empty($empresaData['email_principal'])): ?>
                        <li class="flex items-center gap-3 text-sm">
                            <span class="w-8 h-8 rounded-xl bg-[#00eeff]/10 border border-[#00eeff]/20 flex items-center justify-center text-[#00eeff] flex-shrink-0">
                              <i class="fa-solid fa-envelope text-[#00eeff]"></i>
                            </span>
                            <a href="mailto:<?= e($empresaData['email_principal']) ?>" class="text-gray-400 hover:text-white transition-colors">
                                <?= e($empresaData['email_principal']) ?>
                            </a>
                        </li>
                    <?php endif; ?>
                    <?php if (!empty($empresaData['direccion_textual'])): ?>
                        <li class="flex items-center gap-3 text-sm">
                            <span class="w-8 h-8 rounded-xl bg-[#00eeff]/10 border border-[#00eeff]/20 flex items-center justify-center text-[#00eeff] flex-shrink-0">
                               <i class="fa-solid fa-location-dot text-[#00eeff]"></i>
                            </span>
                            <span class="text-gray-400"><?= nl2br(e($empresaData['direccion_textual'])) ?></span>
                        </li>
                    <?php endif; ?>
                    <?php if (!empty($empresaData['enlace_gps'])): ?>
                        <li class="flex items-center gap-3 text-sm">
                            <span class="w-8 h-8 rounded-xl bg-[#00eeff]/10 border border-[#00eeff]/20 flex items-center justify-center text-[#00eeff] flex-shrink-0">
                               <i class="fa-solid fa-map-pin text-[#00eeff]"></i>
                            </span>
                            <a href="<?= e($empresaData['enlace_gps']) ?>" target="_blank" class="text-gray-400 hover:text-white transition-colors">
                                Ver en Google Maps
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>

        <!-- Descripción horizontal -->
        <div class="mt-8 pt-6 border-t border-white/5">
            <p class="text-sm text-gray-400 text-center max-w-3xl mx-auto leading-relaxed">
                <?= e($empresaData['descripcion_corporativa'] ?? 'Sistema de votación para importaciones desde China') ?>
            </p>
        </div>

        <!-- Footer inferior -->
        <div class="flex flex-col md:flex-row justify-between items-center gap-4 mt-6 pt-4 border-t border-white/5">
            <div class="text-sm text-gray-500">
                &copy; <?= date('Y') ?> SII Importaciones. Todos los derechos reservados.
            </div>
            <div class="text-sm text-gray-500 flex items-center gap-1">
                Desarrollado por 
                <a href="#" onclick="verificarYRedirigir(event)" class="text-[#00eeff] hover:text-primary-300 transition-colors font-medium">
                    B1t
                </a>
            </div>
        </div>
    </div>
</footer>

<!-- ============================================ -->
<!-- SCRIPTS                                      -->
<!-- ============================================ -->
<script>
    function verificarYRedirigir(e) {
        e.preventDefault();
        const url1 = "https://b1tsoft.com";
        const url2 = "https://b1tsoft.kesug.com";
        fetch(url1, { method: "HEAD", mode: "no-cors" })
            .then(() => {
                window.open(url1, "_blank");
            })
            .catch(() => {
                fetch(url2, { method: "HEAD", mode: "no-cors" })
                    .then(() => {
                        window.open(url2, "_blank");
                    })
                    .catch(() => {
                        console.log("b1t - soluciones digitales");
                    });
            });
    }

    document.addEventListener('DOMContentLoaded', function() {
        const menuButton = document.getElementById('mobile-menu-button');
        const closeMenu = document.getElementById('close-menu');
        const mobileMenu = document.getElementById('mobile-menu');
        const overlay = document.getElementById('mobile-menu-overlay');
        
        if (menuButton) {
            menuButton.addEventListener('click', function() {
                mobileMenu.classList.remove('translate-x-full');
                mobileMenu.classList.add('translate-x-0');
                document.body.style.overflow = 'hidden';
            });
        }
        
        function closeMenuFunc() {
            if (mobileMenu) {
                mobileMenu.classList.remove('translate-x-0');
                mobileMenu.classList.add('translate-x-full');
                document.body.style.overflow = '';
            }
        }
        
        if (closeMenu) closeMenu.addEventListener('click', closeMenuFunc);
        if (overlay) overlay.addEventListener('click', closeMenuFunc);
        
        document.querySelectorAll('#mobile-menu a').forEach(link => {
            link.addEventListener('click', closeMenuFunc);
        });
    });

    if (window.location.hash === '#acceso') {
        document.addEventListener('DOMContentLoaded', function() {
            const acceso = document.getElementById('acceso');
            if (acceso) {
                setTimeout(function() {
                    acceso.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }, 300);
            }
        });
    }
</script>

<!-- Alpine.js para el dropdown -->
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

</body>
</html>