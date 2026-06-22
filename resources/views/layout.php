<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($titulo ?? 'Sistema de Votación') ?> | Sii importaciones</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
    tailwind.config = {
    theme: {
        extend: {
        colors: {
            primary: {
            50:  "#E6EDF5",
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

</head>
<body class="bg-slate-100 min-h-screen flex flex-col">

    <!-- Barra de navegación -->
    <nav class="bg-slate-900 text-white shadow-lg">
        <div class="max-w-6xl mx-auto px-4 py-3">
            <!-- Fila 1: Logo y menú principal -->
            <div class="flex flex-wrap items-center justify-between gap-3">
            <!-- Logo -->
            <a href="<?= url('/') ?>" class="flex items-center gap-2">
                <span class="text-white font-black rounded-lg px-2 py-1 text-lg" style="background: #2F5A8A;">SI</span>
                <div>
                    <span class="font-bold text-lg tracking-wide" style="color: #12283D;">SII IMPORTACIONES</span>
                    <p class="text-xs text-slate-400 -mt-1">Sistema de votación para importaciones desde China</p>
                </div>
            </a>

                <!-- Menú principal (escritorio) -->
                <div class="hidden md:flex items-center gap-1 text-sm">
                    <a href="<?= url('/') ?>" class="px-3 py-2 rounded-lg hover:bg-slate-700 font-semibold">Inicio</a>
                    <a href="<?= url('conocenos') ?>" class="px-3 py-2 rounded-lg hover:bg-slate-700 font-semibold">Conócenos</a>
                    <a href="<?= url('servicios') ?>" class="px-3 py-2 rounded-lg hover:bg-slate-700 font-semibold">Servicios</a>
                    <a href="<?= url('contactos') ?>" class="px-3 py-2 rounded-lg hover:bg-slate-700 font-semibold">Contactos</a>
                </div>

                <!-- Acciones de usuario -->
                <!-- En el header, la sección de acciones de usuario -->
                <div class="flex items-center gap-2 text-sm">
                    <?php 
                    // Verificar si es administrador
                    $admin = $_SESSION['administrador'] ?? null;
                    ?>
                    
                    <?php if ($admin): ?>
                        <!-- Administrador logueado -->
                        <span class="hidden sm:inline text-red-300 px-2 text-xs truncate max-w-[150px]">
                            👑 <?= e($admin['nombre']) ?>
                        </span>
                        <a href="<?= url('auth/logout') ?>" class="px-3 py-2 rounded-lg bg-red-600 hover:bg-red-700 text-white font-semibold">
                            Cerrar Sesión Admin
                        </a>
                    <?php elseif (auth()): ?>
                        <!-- Usuario normal logueado -->
                        <span class="hidden sm:inline text-slate-300 px-2 text-xs truncate max-w-[150px]"><?= e(auth()['email']) ?></span>
                        <a href="<?= url('logout') ?>" class="px-3 py-2 rounded-lg bg-red-600 hover:bg-red-700 text-white font-semibold">Cerrar Sesión</a>
                    <?php else: ?>
                        <!-- Usuario no logueado -->
                        <a href="<?= url('/') ?>#acceso" class="px-3 py-2 rounded-lg bg-amber-500 text-slate-900 font-bold hover:bg-amber-400">Iniciar Sesión</a>
                    <?php endif; ?>
                </div>


            </div>

            <!-- Fila 2: Menú secundario (Productos, Ranking, Publicar) -->
            <?php if (auth()): ?>
            <div class="flex flex-wrap items-center gap-2 mt-3 pt-3 border-t border-slate-700">
                <a href="<?= url('dashboard') ?>" class="px-3 py-1.5 rounded-lg bg-slate-700 hover:bg-slate-600 text-sm font-semibold">
                    📦 Productos
                </a>
                <a href="<?= url('ranking') ?>" class="px-3 py-1.5 rounded-lg bg-slate-700 hover:bg-slate-600 text-sm font-semibold">
                    🏆 Más Votados
                </a>
                <a href="<?= url('productos/crear') ?>" class="px-3 py-1.5 rounded-lg bg-amber-500 text-slate-900 font-bold hover:bg-amber-400 text-sm">
                    ➕ Publicar Producto
                </a>
            </div>
            <?php endif; ?>

            <!-- Menú móvil (hamburguesa) -->
            <div class="md:hidden flex flex-wrap gap-2 mt-3 pt-3 border-t border-slate-700">
                <a href="<?= url('/') ?>" class="px-3 py-1.5 rounded-lg bg-slate-700 hover:bg-slate-600 text-sm font-semibold">Inicio</a>
                <a href="<?= url('conocenos') ?>" class="px-3 py-1.5 rounded-lg bg-slate-700 hover:bg-slate-600 text-sm font-semibold">Conócenos</a>
                <a href="<?= url('servicios') ?>" class="px-3 py-1.5 rounded-lg bg-slate-700 hover:bg-slate-600 text-sm font-semibold">Servicios</a>
                <a href="<?= url('contactos') ?>" class="px-3 py-1.5 rounded-lg bg-slate-700 hover:bg-slate-600 text-sm font-semibold">Contactos</a>
            </div>
        </div>
    </nav>

    <!-- Mensaje flash -->
    <?php if ($flash = getFlash()): ?>
        <div class="max-w-6xl mx-auto px-4 mt-4 w-full">
            <div class="rounded-lg px-4 py-3 text-sm font-semibold <?= $flash['tipo'] === 'exito' ? 'bg-green-100 text-green-800 border border-green-300' : 'bg-red-100 text-red-800 border border-red-300' ?>">
                <?= e($flash['mensaje']) ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- Contenido de la vista -->
    <main class="max-w-6xl mx-auto px-4 py-6 w-full flex-1">
        <?= $contenido ?>
    </main>

    <footer class="bg-slate-900 text-slate-400 text-center text-sm py-4">
        WILLS IMPORT — Comparte y vota por los mejores productos de Alibaba
    </footer>

    <!-- Script para scroll suave al formulario si hay mensaje flash -->
    <script>
        // Si hay un mensaje flash y estamos en la página de inicio, desplazarse al formulario
        <?php if (!$auth() && $flash = getFlash()): ?>
        document.addEventListener('DOMContentLoaded', function() {
            const acceso = document.getElementById('acceso');
            if (acceso) {
                setTimeout(function() {
                    acceso.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }, 300);
            }
        });
        <?php endif; ?>
        
        // Si la URL tiene #acceso, desplazarse al formulario
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
</body>
</html>