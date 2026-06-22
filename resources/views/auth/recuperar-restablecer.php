<div class="max-w-md mx-auto mt-8">
    <div class="bg-white rounded-xl shadow-lg p-8" style="border-top: 4px solid #2F5A8A;">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-extrabold" style="color: #12283D;">Restablecer Contraseña</h1>
            <span class="bg-green-100 text-green-800 text-xs font-semibold px-3 py-1 rounded-full">Nueva Contraseña</span>
        </div>
        <p class="text-slate-500 text-sm text-center mb-6">
            Ingresa tu nueva contraseña para completar el proceso.
        </p>

        <?php if ($flash = getFlash()): ?>
            <div class="mb-4 rounded-lg px-4 py-3 text-sm font-semibold <?= $flash['tipo'] === 'exito' ? 'bg-green-100 text-green-800 border border-green-300' : 'bg-red-100 text-red-800 border border-red-300' ?>">
                <?= e($flash['mensaje']) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?= url('auth/restablecer') ?>" class="space-y-4">
            <input type="hidden" name="token" value="<?= e($token ?? '') ?>">
            
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Nueva contraseña</label>
                <input type="password" name="password" required
                       placeholder="••••••••"
                       minlength="6"
                       class="w-full border border-slate-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 transition"
                       style="border-color: #B8CCE3; focus:ring-color: #2F5A8A;">
                <p class="text-xs text-slate-400 mt-1">Mínimo 6 caracteres</p>
            </div>
            
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Confirmar contraseña</label>
                <input type="password" name="password_confirm" required
                       placeholder="••••••••"
                       minlength="6"
                       class="w-full border border-slate-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 transition"
                       style="border-color: #B8CCE3; focus:ring-color: #2F5A8A;">
            </div>
            
            <button type="submit" class="w-full text-white font-bold py-3 rounded-lg transition hover:opacity-90" style="background: #2F5A8A;">
                <i class="fas fa-check mr-2"></i> Restablecer Contraseña
            </button>
        </form>

        <div class="mt-6 pt-4 border-t border-slate-200 text-center">
            <a href="<?= url('auth/login') ?>" class="text-sm hover:underline" style="color: #2F5A8A;">
                ← Volver al inicio de sesión
            </a>
        </div>
        
        <div class="mt-4 text-xs text-center text-slate-400">
            <p>🔒 Tu contraseña será actualizada de forma segura</p>
        </div>
    </div>
</div>