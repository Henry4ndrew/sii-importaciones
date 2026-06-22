<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($titulo ?? 'Panel de Administración') ?> | SII Importaciones</title>

    <!-- Tailwind CSS con paleta de colores SII -->
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
                        }
                    }
                }
            }
        }
    </script>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        /* Solo estilos que Tailwind no puede manejar fácilmente */
        .sidebar {
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            min-height: calc(100vh - 64px);
            overflow-y: auto;
            overflow-x: hidden;
            flex-shrink: 0;
        }

        .sidebar::-webkit-scrollbar {
            width: 4px;
        }
        .sidebar::-webkit-scrollbar-track {
            background: transparent;
        }
        .sidebar::-webkit-scrollbar-thumb {
            background: #5D89BF;
            border-radius: 10px;
        }

        /* Backdrop */
        .sidebar-backdrop {
            transition: opacity 0.3s ease;
            opacity: 0;
            pointer-events: none;
        }
        .sidebar-backdrop.active {
            opacity: 1;
            pointer-events: auto;
        }


     /* ============================================
        ANIMACIÓN HAMBURGUESA -> CRUZ (CORREGIDA)
        ============================================ */
        .hamburger {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            width: 26px;
            height: 20px;
            padding: 2px 0;
            cursor: pointer;
            background: transparent;
            border: none;
            outline: none;
        }

        .hamburger span {
            display: block;
            height: 2.5px;
            width: 100%;
            background: #ffffff;
            border-radius: 4px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            transform-origin: center;
        }

        /* Estado abierto (cruz perfecta) */
        .hamburger.open span:nth-child(1) {
            transform: translateY(7px) rotate(45deg);
        }

        .hamburger.open span:nth-child(2) {
            opacity: 0;
            transform: scaleX(0);
        }

        .hamburger.open span:nth-child(3) {
            transform: translateY(-7px) rotate(-45deg);
        }

        /* ============================================
           LOGO - IMAGEN COMPLETA SIN RECORTE
           ============================================ */
        .logo-img {
            object-fit: contain;
            width: auto;
            height: 100%;
            max-height: 42px;
        }

        .logo-img-sm {
            object-fit: contain;
            width: auto;
            height: 100%;
            max-height: 34px;
        }

        /* Contenedor del logo para mantener proporciones */
        .logo-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 42px;
            width: auto;
        }

        .logo-wrapper-sm {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 34px;
            width: auto;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                top: 0;
                left: 0;
                bottom: 0;
                z-index: 1000;
                width: 280px;
                transform: translateX(-100%);
                box-shadow: 4px 0 30px rgba(0,0,0,0.3);
                min-height: 100vh;
            }
            .sidebar.mobile-open {
                transform: translateX(0);
            }
            .sidebar-backdrop {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(0,0,0,0.5);
                z-index: 999;
                backdrop-filter: blur(4px);
            }
        }

        @media (min-width: 769px) {
            .sidebar-backdrop {
                display: none !important;
            }
        }
    </style>
</head>
<body>

    <!-- ============================================ -->
    <!-- BARRA DE NAVEGACIÓN SUPERIOR                   -->
    <!-- ============================================ -->
    <nav x-data="{ mobileOpen: false }"
         class="bg-primary-900 text-white shadow-lg border-b border-primary-700/50 sticky top-0 z-[1001]">

        <div class="px-3 sm:px-6 py-2.5">
            <div class="flex items-center justify-between">

                <!-- IZQUIERDA: Hamburguesa (móvil) + Logo -->
                <div class="flex items-center gap-3">

                    <!-- Botón hamburguesa (solo móvil) -->
                    <button @click="mobileOpen = !mobileOpen; document.getElementById('sidebar').classList.toggle('mobile-open'); document.getElementById('backdrop').classList.toggle('active')"
                            class="md:hidden hamburger"
                            :class="{ 'open': mobileOpen }"
                            aria-label="Toggle sidebar">
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>

                    <!-- Logo (escritorio) -->
                    <a href="<?= url('admin/dashboard') ?>" class="hidden md:flex items-center gap-3 group">
                        <div class="logo-wrapper">
                            <img src="<?= url('public/img/logo-SII.avif') ?>"
                                 alt="SII Importaciones"
                                 class="logo-img group-hover:opacity-80 transition-opacity">
                        </div>
                        <div>
                            <span class="font-bold text-lg tracking-wide text-white group-hover:text-primary-200 transition-colors">IMPORTACIONES</span>
                            <p class="text-[10px] text-primary-300 -mt-0.5 tracking-wider uppercase">Panel de Administración</p>
                        </div>
                    </a>

                    <!-- Logo (móvil) -->
                    <a href="<?= url('admin/dashboard') ?>" class="md:hidden">
                        <div class="logo-wrapper-sm">
                            <img src="<?= url('public/img/logo-SII.avif') ?>"
                                 alt="SII Importaciones"
                                 class="logo-img-sm">
                        </div>
                    </a>
                </div>

                <!-- DERECHA: Perfil + Cerrar Sesión -->
                <div class="flex items-center gap-2 sm:gap-3">

                    <!-- Avatar -->
                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                        <?= substr(e($admin['nombre'] ?? 'A'), 0, 1) ?>
                    </div>

                    <!-- Email (solo escritorio) -->
                    <span class="hidden sm:block text-sm text-primary-200 font-medium">
                        <?= e($admin['email'] ?? 'admin@correo.com') ?>
                    </span>

                    <!-- Botón Cerrar Sesión -->
                    <a href="<?= url('auth/logout') ?>"
                       class="px-4 py-1.5 rounded-full text-sm font-semibold transition-all duration-200
                              bg-red-500/20 text-red-300 hover:bg-red-500/30 hover:text-white border border-red-500/20 hover:border-red-400/40
                              flex items-center gap-1.5 whitespace-nowrap">
                        <i class="fas fa-sign-out-alt text-xs"></i>
                        <span>Salir</span>
                    </a>

                </div>

            </div>
        </div>
    </nav>

    <!-- ============================================ -->
    <!-- BACKDROP (móvil)                              -->
    <!-- ============================================ -->
    <div id="backdrop"
         class="sidebar-backdrop"
         @click="mobileOpen = false; document.getElementById('sidebar').classList.remove('mobile-open'); document.getElementById('backdrop').classList.remove('active')">
    </div>

    <!-- ============================================ -->
    <!-- CONTENEDOR PRINCIPAL                         -->
    <!-- ============================================ -->
    <div class="flex overflow-x-hidden">

        <!-- SIDEBAR -->
        <aside id="sidebar" class="sidebar bg-gradient-to-b from-primary-900 via-primary-800 to-primary-900 text-white shadow-2xl">

            <!-- Logo en sidebar (solo móvil) -->
            <div class="md:hidden flex items-center gap-3 px-4 py-4 border-b border-primary-700/30">
                <div class="logo-wrapper-sm">
                    <img src="<?= url('public/img/logo-SII.avif') ?>"
                         alt="SII Importaciones"
                         class="logo-img-sm">
                </div>
                <span class="font-bold text-sm tracking-wide text-white">SII IMPORTACIONES</span>
            </div>

            <!-- Menú -->
            <nav class="p-4">

                <div class="mb-5 pb-3 border-b border-primary-700/30">
                    <p class="text-[10px] text-primary-300 uppercase tracking-[0.15em] font-semibold">
                         <i class="fas fa-cog mr-2"></i> Sistema
                    </p>
                </div>

                <!-- Dashboard -->
                <a href="<?= url('admin/dashboard') ?>"
                   class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-all duration-200
                          <?= $activePage === 'dashboard' ? 'bg-primary-700/50 text-white' : 'text-primary-200 hover:bg-primary-800/50 hover:text-white' ?>">
                    <i class="fas fa-chart-pie w-5 text-primary-400"></i>
                    <span>Dashboard</span>
                    <span class="ml-auto text-[9px] font-bold uppercase bg-primary-500 text-primary-900 px-2 py-0.5 rounded-full">Admin</span>
                </a>

                <!-- Publicaciones -->
                <a href="<?= url('admin/publicaciones') ?>"
                   class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 text-primary-200 hover:bg-primary-800/50 hover:text-white">
                    <i class="fas fa-newspaper w-5 text-primary-300"></i>
                    <span>Publicaciones</span>
                </a>

                <!-- Usuarios -->
                <a href="<?= url('admin/usuarios') ?>"
                   class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-all duration-200
                          <?= $activePage === 'usuarios' ? 'bg-primary-700/50 text-white' : 'text-primary-200 hover:bg-primary-800/50 hover:text-white' ?>">
                    <i class="fas fa-users w-5 text-primary-300"></i>
                    <span>Usuarios</span>
                    <?php if (isset($totalUsuarios)): ?>
                        <span class="ml-auto bg-primary-700/50 text-primary-200 text-[10px] px-2.5 py-0.5 rounded-full"><?= $totalUsuarios ?></span>
                    <?php endif; ?>
                </a>

                <!-- Administradores -->
                <a href="<?= url('admin/administradores') ?>"
                class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-all duration-200
                        <?= $activePage === 'administradores' ? 'bg-primary-700/50 text-white' : 'text-primary-200 hover:bg-primary-800/50 hover:text-white' ?>">
                    <i class="fas fa-user-shield w-5 text-primary-300"></i>
                    <span>Administradores</span>
                    <?php
                    // Obtener el total de administradores desde la sesión o calcularlo
                    $totalAdmins = $totalAdministradores ?? 0;
                    ?>
                    <span class="ml-auto bg-primary-700/50 text-primary-200 text-[10px] px-2.5 py-0.5 rounded-full"><?= $totalAdmins ?></span>
                </a>


                <div class="my-5 pt-3 border-t border-primary-700/30">
                    <p class="text-[10px] text-primary-400 uppercase tracking-[0.15em] font-semibold">
                       <i class="fas fa-globe mr-2"></i> Página Web
                    </p>
                </div>

                <!-- Portadas -->
                <a href="<?= url('admin/portadas') ?>"
                class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-all duration-200
                        <?= $activePage === 'portadas' ? 'bg-primary-700/50 text-white' : 'text-primary-200 hover:bg-primary-800/50 hover:text-white' ?>">
                    <i class="fas fa-images w-5 text-purple-400"></i>
                    <span>Portadas</span>
                </a>
                <!-- Votos -->
                <a href="<?= url('admin/conocenos') ?>"
                   class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 text-primary-200 hover:bg-primary-800/50 hover:text-white">
                    <i class="fas fa-users w-5 text-emerald-400"></i>
                    <span>Conócenos</span>
                </a>

                <!-- Votos -->
                <a href="<?= url('admin/contactos') ?>"
                   class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 text-primary-200 hover:bg-primary-800/50 hover:text-white">
                    <i class="fas fa-address-book w-5 text-emerald-400"></i>
                    <span>Contactos</span>
                </a>

                <!-- Votos -->
                <a href="<?= url('admin/servicios') ?>"
                   class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 text-primary-200 hover:bg-primary-800/50 hover:text-white">
                    <i class="fas fa-concierge-bell w-5 text-emerald-400"></i>
                    <span>Servicios</span>
                </a>

                <!-- Votos -->
                <a href="<?= url('admin/empresa') ?>"
                   class="flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 text-primary-200 hover:bg-primary-800/50 hover:text-white">
                   <i class="fas fa-building w-5 text-emerald-400"></i>
                    <span>Empresa</span>
                </a>


                <!-- Cerrar Sesión (móvil) -->
                <a href="<?= url('auth/logout') ?>"
                   class="md:hidden flex items-center gap-3 px-4 py-2.5 rounded-lg text-sm font-medium transition-all duration-200 text-red-300 hover:bg-red-900/30 hover:text-red-200">
                    <i class="fas fa-sign-out-alt w-5 text-red-400"></i>
                    <span>Salir</span>
                </a>

            </nav>
        </aside>

        <!-- ============================================ -->
        <!-- CONTENIDO PRINCIPAL                          -->
        <!-- ============================================ -->
        <main class="flex-1 min-h-[calc(100vh-64px)] bg-slate-100 overflow-x-hidden">

            <!-- Mensaje flash -->
            <?php if ($flash = getFlash()): ?>
                <div class="max-w-full mx-3 sm:mx-4 mt-3 sm:mt-4">
                    <div class="rounded-xl px-4 sm:px-5 py-3 sm:py-3.5 text-sm font-semibold shadow-sm
                                <?= $flash['tipo'] === 'exito'
                                    ? 'bg-emerald-50 text-emerald-800 border border-emerald-200'
                                    : 'bg-red-50 text-red-800 border border-red-200' ?>">
                        <i class="fas <?= $flash['tipo'] === 'exito' ? 'fa-check-circle' : 'fa-exclamation-circle' ?> mr-2"></i>
                        <?= e($flash['mensaje']) ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Contenido de la vista -->
            <div class="p-3 sm:p-6">
                <?= $contenido ?>
            </div>

        </main>

    </div>

    <!-- ============================================ -->
    <!-- SCRIPTS                                       -->
    <!-- ============================================ -->
    <script>
        // Cerrar sidebar con ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                const sidebar = document.getElementById('sidebar');
                const backdrop = document.getElementById('backdrop');
                if (sidebar && sidebar.classList.contains('mobile-open')) {
                    sidebar.classList.remove('mobile-open');
                    if (backdrop) backdrop.classList.remove('active');
                }
            }
        });
    </script>

</body>
</html>