<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($titulo ?? 'Sistema de Votación') ?> | WILLS IMPORT</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100 min-h-screen flex flex-col">

    <!-- Barra de navegación -->
    <nav class="bg-slate-900 text-white shadow-lg">
        <div class="max-w-6xl mx-auto px-4 py-3 flex flex-wrap items-center justify-between gap-3">
            <a href="<?= url('/') ?>" class="flex items-center gap-2">
                <span class="bg-amber-500 text-slate-900 font-black rounded-lg px-2 py-1 text-lg">WI</span>
                <div>
                    <span class="font-bold text-lg tracking-wide">WILLS IMPORT</span>
                    <p class="text-xs text-slate-400 -mt-1">Sistema de votación para importaciones desde China</p>
                </div>
            </a>

            <div class="flex items-center gap-2 text-sm">
                <a href="<?= url('/') ?>" class="px-3 py-2 rounded-lg hover:bg-slate-700 font-semibold">PRODUCTOS</a>
                <a href="<?= url('ranking') ?>" class="px-3 py-2 rounded-lg hover:bg-slate-700 font-semibold">Más Votados</a>

                <?php if (auth()): ?>
                    <a href="<?= url('productos/crear') ?>" class="px-3 py-2 rounded-lg bg-amber-500 text-slate-900 font-bold hover:bg-amber-400">+ Publicar</a>
                    <span class="hidden sm:inline text-slate-300 px-2"><?= e(auth()['nombre']) ?></span>
                    <a href="<?= url('logout') ?>" class="px-3 py-2 rounded-lg bg-slate-700 hover:bg-slate-600">Salir</a>
                <?php else: ?>
                    <a href="<?= url('login') ?>" class="px-3 py-2 rounded-lg bg-amber-500 text-slate-900 font-bold hover:bg-amber-400">Acceder al Sistema</a>
                <?php endif; ?>
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
</body>
</html>
