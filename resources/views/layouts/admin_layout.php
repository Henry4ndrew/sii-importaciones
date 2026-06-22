<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($titulo ?? 'Panel de Administración') ?> | Sii importaciones</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .sidebar {
            transition: all 0.3s ease;
            width: 250px;
            min-height: calc(100vh - 64px);
        }
        .sidebar-link {
            transition: all 0.2s ease;
            border-left: 3px solid transparent;
        }
        .sidebar-link:hover {
            background: #1e293b;
            border-left-color: #ef4444;
        }
        .sidebar-link.active {
            background: #1e293b;
            border-left-color: #ef4444;
        }
        .main-content {
            flex: 1;
            min-height: calc(100vh - 64px);
        }
        .badge-admin {
            background: #ef4444;
            color: white;
            font-size: 10px;
            padding: 2px 8px;
            border-radius: 10px;
            margin-left: 8px;
        }
        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                min-height: auto;
                display: none;
            }
            .sidebar.mobile-open {
                display: block;
            }
            .sidebar-toggle {
                display: block !important;
            }
        }
    </style>
</head>
<body class="bg-slate-100">

    <!-- Barra de navegación superior -->
    <nav class="bg-slate-900 text-white shadow-lg border-b-4 border-red-600 sticky top-0 z-50">
        <div class="px-4 py-3">
            <div class="flex items-center justify-between">
                <!-- Logo y toggle sidebar (móvil) -->
                <div class="flex items-center gap-3">
                    <button onclick="toggleSidebar()" class="sidebar-toggle hidden md:hidden text-white hover:text-red-400 text-xl">
                        <i class="fas fa-bars"></i>
                    </button>
                    <a href="<?= url('admin/dashboard') ?>" class="flex items-center gap-2">
                        <span class="bg-red-600 text-white font-black rounded-lg px-2 py-1 text-lg">WI</span>
                        <div>
                            <span class="font-bold text-lg tracking-wide">WILLS IMPORT</span>
                            <p class="text-xs text-red-400 -mt-1">Panel de Administración</p>
                        </div>
                    </a>
                </div>

                <!-- Información del administrador -->
                <div class="flex items-center gap-4">
                    <span class="hidden sm:inline text-sm text-slate-300">
                        <i class="fas fa-user-shield mr-1 text-red-400"></i>
                        <?= e($admin['nombre'] ?? 'Administrador') ?>
                    </span>
                    <div class="relative group">
                        <button class="flex items-center gap-2 text-sm hover:text-red-400 transition">
                            <span class="w-8 h-8 rounded-full bg-red-600 flex items-center justify-center text-white font-bold">
                                <?= substr(e($admin['nombre'] ?? 'A'), 0, 1) ?>
                            </span>
                            <i class="fas fa-chevron-down text-xs"></i>
                        </button>
                        <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg py-1 hidden group-hover:block">
                            <a href="<?= url('admin/dashboard') ?>" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-100">
                                <i class="fas fa-chart-pie mr-2"></i> Dashboard
                            </a>
                            <hr class="my-1">
                            <a href="<?= url('auth/logout') ?>" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                <i class="fas fa-sign-out-alt mr-2"></i> Cerrar Sesión
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Contenedor principal con sidebar -->
    <div class="flex">
        <!-- Sidebar -->
        <aside id="sidebar" class="sidebar bg-slate-800 text-white flex-shrink-0 overflow-y-auto">
            <nav class="p-4">
                <div class="mb-6 pb-4 border-b border-slate-700">
                    <p class="text-xs text-slate-400 uppercase tracking-wider">Menú Principal</p>
                </div>

                <!-- Dashboard -->
                <a href="<?= url('admin/dashboard') ?>" class="sidebar-link <?= $activePage === 'dashboard' ? 'active' : '' ?> flex items-center gap-3 px-4 py-3 rounded-r-lg text-sm font-medium transition">
                    <i class="fas fa-chart-pie w-5 text-red-400"></i>
                    <span>Dashboard</span>
                    <span class="badge-admin">Admin</span>
                </a>

                <!-- Productos -->
                <a href="<?= url('dashboard') ?>" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-r-lg text-sm font-medium transition hover:bg-slate-700">
                    <i class="fas fa-box w-5 text-blue-400"></i>
                    <span>Productos</span>
                </a>

                <!-- Ranking -->
                <a href="<?= url('ranking') ?>" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-r-lg text-sm font-medium transition hover:bg-slate-700">
                    <i class="fas fa-trophy w-5 text-yellow-400"></i>
                    <span>Ranking</span>
                </a>

                <!-- Usuarios -->
                <a href="<?= url('admin/usuarios') ?>" class="sidebar-link <?= $activePage === 'usuarios' ? 'active' : '' ?> flex items-center gap-3 px-4 py-3 rounded-r-lg text-sm font-medium transition hover:bg-slate-700">
                    <i class="fas fa-users w-5 text-green-400"></i>
                    <span>Usuarios</span>
                    <?php if (isset($totalUsuarios)): ?>
                        <span class="ml-auto bg-slate-700 text-xs px-2 py-0.5 rounded-full"><?= $totalUsuarios ?></span>
                    <?php endif; ?>
                </a>

                <!-- Votos -->
                <a href="#" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-r-lg text-sm font-medium transition hover:bg-slate-700">
                    <i class="fas fa-star w-5 text-amber-400"></i>
                    <span>Votos</span>
                    <?php if (isset($totalVotos)): ?>
                        <span class="ml-auto bg-slate-700 text-xs px-2 py-0.5 rounded-full"><?= $totalVotos ?></span>
                    <?php endif; ?>
                </a>

                <div class="mt-6 pt-4 border-t border-slate-700">
                    <p class="text-xs text-slate-400 uppercase tracking-wider">Sistema</p>
                </div>

                <!-- Ir al sitio -->
                <a href="<?= url('/') ?>" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-r-lg text-sm font-medium transition hover:bg-slate-700">
                    <i class="fas fa-globe w-5 text-purple-400"></i>
                    <span>Ir al Sitio</span>
                </a>

                <!-- Cerrar Sesión -->
                <a href="<?= url('auth/logout') ?>" class="sidebar-link flex items-center gap-3 px-4 py-3 rounded-r-lg text-sm font-medium transition hover:bg-red-900/50 text-red-300">
                    <i class="fas fa-sign-out-alt w-5 text-red-400"></i>
                    <span>Cerrar Sesión</span>
                </a>
            </nav>
        </aside>

        <!-- Contenido principal -->
        <main class="main-content bg-slate-100">
            <!-- Mensaje flash -->
            <?php if ($flash = getFlash()): ?>
                <div class="max-w-full mx-4 mt-4">
                    <div class="rounded-lg px-4 py-3 text-sm font-semibold <?= $flash['tipo'] === 'exito' ? 'bg-green-100 text-green-800 border border-green-300' : 'bg-red-100 text-red-800 border border-red-300' ?>">
                        <i class="fas <?= $flash['tipo'] === 'exito' ? 'fa-check-circle' : 'fa-exclamation-circle' ?> mr-2"></i>
                        <?= e($flash['mensaje']) ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Contenido de la vista -->
            <div class="p-6">
                <?= $contenido ?>
            </div>
        </main>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('mobile-open');
        }

        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const toggleBtn = document.querySelector('.sidebar-toggle');
            if (window.innerWidth <= 768) {
                if (!sidebar.contains(event.target) && !toggleBtn.contains(event.target)) {
                    sidebar.classList.remove('mobile-open');
                }
            }
        });
    </script>
</body>
</html>