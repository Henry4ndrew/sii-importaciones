<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-xl shadow p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-slate-800">
                <i class="fas fa-user-plus mr-2 text-primary-500"></i> Nuevo Administrador
            </h2>
            <a href="<?= url('admin/administradores') ?>" 
               class="text-sm text-slate-500 hover:text-slate-700">
                <i class="fas fa-arrow-left mr-1"></i> Volver
            </a>
        </div>

        <?php if ($flash = getFlash()): ?>
            <div class="mb-4 rounded-lg px-4 py-3 text-sm font-semibold <?= $flash['tipo'] === 'exito' ? 'bg-green-100 text-green-800 border border-green-300' : 'bg-red-100 text-red-800 border border-red-300' ?>">
                <?= e($flash['mensaje']) ?>
            </div>
        <?php endif; ?>

        <!-- ============================================ -->
        <!-- ACCIÓN CORRECTA: /admin/administradores/guardar -->
        <!-- ============================================ -->
        <form method="POST" action="<?= url('admin/administradores/guardar') ?>" class="space-y-4">
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Nombre completo</label>
                <input type="text" name="nombre" required
                       placeholder="Nombre del administrador"
                       class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Correo electrónico</label>
                <input type="email" name="email" required
                       placeholder="admin@correo.com"
                       class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Contraseña</label>
                <input type="password" name="password" required
                       placeholder="Mínimo 6 caracteres"
                       minlength="6"
                       class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Confirmar contraseña</label>
                <input type="password" name="password_confirm" required
                       placeholder="Repite la contraseña"
                       minlength="6"
                       class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
            </div>

            <div class="flex gap-3 pt-4">
                <button type="submit" 
                        class="bg-primary-500 hover:bg-primary-600 text-white px-6 py-2 rounded-lg font-semibold transition">
                    <i class="fas fa-save mr-2"></i> Crear Administrador
                </button>
                <a href="<?= url('admin/administradores') ?>" 
                   class="bg-slate-200 hover:bg-slate-300 text-slate-700 px-6 py-2 rounded-lg font-semibold transition">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>