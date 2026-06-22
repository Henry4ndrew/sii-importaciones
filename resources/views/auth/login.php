<div class="max-w-md mx-auto mt-8">
    <div class="bg-white rounded-xl shadow p-8">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-extrabold text-slate-800">Acceso Administrador</h1>
            <span class="bg-red-100 text-red-800 text-xs font-semibold px-3 py-1 rounded-full">Área Restringida</span>
        </div>
        <p class="text-slate-500 text-sm text-center mb-6">Ingresa tus credenciales para acceder al panel de administración</p>

        <?php if ($flash = getFlash()): ?>
            <div class="mb-4 rounded-lg px-4 py-3 text-sm font-semibold <?= $flash['tipo'] === 'exito' ? 'bg-green-100 text-green-800 border border-green-300' : 'bg-red-100 text-red-800 border border-red-300' ?>">
                <?= e($flash['mensaje']) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?= url('auth/login') ?>" class="space-y-4">
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Correo electrónico</label>
                <input type="email" name="email" required
                       placeholder="admin@willsimport.com"
                       class="w-full border border-slate-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500">
            </div>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Contraseña</label>
                <input type="password" name="password" required
                       placeholder="••••••••"
                       class="w-full border border-slate-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-red-500">
            </div>
            <button class="w-full bg-red-600 text-white font-bold py-3 rounded-lg hover:bg-red-700 transition">
                Ingresar como Administrador
            </button>
        </form>

        <div class="mt-6 pt-4 border-t border-slate-200 text-center">
            <a href="<?= url('/') ?>" class="text-sm text-slate-500 hover:text-slate-700 hover:underline">
                ← Volver al inicio
            </a>
        </div>
        
        <div class="mt-4 text-xs text-center text-slate-400">
            <p>🔒 Acceso restringido solo para administradores</p>
        </div>
    </div>
</div>