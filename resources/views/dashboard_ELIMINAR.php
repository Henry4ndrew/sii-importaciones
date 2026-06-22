
<div class="max-w-full">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-extrabold text-slate-800">Panel de Administrador</h1>
        <div class="flex items-center gap-3">
            <span class="bg-red-100 text-red-800 text-xs font-semibold px-3 py-1 rounded-full">
                👑 <?= e($admin['nombre'] ?? 'Administrador') ?>
            </span>
            <a href="<?= url('auth/logout') ?>" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition text-sm font-semibold">
                <i class="fas fa-sign-out-alt mr-1"></i> Cerrar Sesión
            </a>
        </div>
    </div>

    <!-- Tarjetas de estadísticas -->
    <div class="grid md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow p-6 hover:shadow-lg transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-500 font-medium">Total Usuarios</p>
                    <p class="text-3xl font-bold text-slate-800"><?= $totalUsuarios ?></p>
                </div>
                <div class="text-4xl">👥</div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow p-6 hover:shadow-lg transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-500 font-medium">Total Productos</p>
                    <p class="text-3xl font-bold text-slate-800"><?= $totalProductos ?></p>
                </div>
                <div class="text-4xl">📦</div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow p-6 hover:shadow-lg transition">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-500 font-medium">Total Votos</p>
                    <p class="text-3xl font-bold text-slate-800"><?= $totalVotos ?></p>
                </div>
                <div class="text-4xl">⭐</div>
            </div>
        </div>
    </div>

    <!-- Acciones rápidas -->
    <div class="bg-white rounded-xl shadow p-6">
        <h2 class="text-xl font-bold text-slate-800 mb-4">Acciones Rápidas</h2>
        <div class="grid md:grid-cols-3 gap-4">
            <a href="<?= url('dashboard') ?>" class="bg-slate-100 hover:bg-slate-200 rounded-lg p-4 text-center transition">
                <div class="text-2xl mb-2">📦</div>
                <p class="font-semibold text-slate-700">Ver Productos</p>
                <p class="text-sm text-slate-500">Gestiona todos los productos</p>
            </a>
            <a href="<?= url('ranking') ?>" class="bg-slate-100 hover:bg-slate-200 rounded-lg p-4 text-center transition">
                <div class="text-2xl mb-2">🏆</div>
                <p class="font-semibold text-slate-700">Ranking</p>
                <p class="text-sm text-slate-500">Productos más votados</p>
            </a>
            <a href="<?= url('/') ?>" class="bg-slate-100 hover:bg-slate-200 rounded-lg p-4 text-center transition">
                <div class="text-2xl mb-2">🏠</div>
                <p class="font-semibold text-slate-700">Inicio</p>
                <p class="text-sm text-slate-500">Volver al sitio principal</p>
            </a>
        </div>
    </div>
</div>