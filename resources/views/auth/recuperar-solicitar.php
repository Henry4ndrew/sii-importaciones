<div class="max-w-md mx-auto mt-8">
    <div class="bg-white rounded-xl shadow-lg p-8" style="border-top: 4px solid #2F5A8A;">
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-2xl font-extrabold" style="color: #12283D;">Recuperar Contraseña</h1>
            <span class="bg-primary-100 text-primary-700 text-xs font-semibold px-3 py-1 rounded-full" style="background: #B8CCE3; color: #12283D;">Administrador</span>
        </div>
        <p class="text-slate-500 text-sm text-center mb-6">
            Ingresa tu correo electrónico y te enviaremos un enlace para restablecer tu contraseña.
        </p>

        <?php if ($flash = getFlash()): ?>
            <div class="mb-4 rounded-lg px-4 py-3 text-sm font-semibold <?= $flash['tipo'] === 'exito' ? 'bg-green-100 text-green-800 border border-green-300' : 'bg-red-100 text-red-800 border border-red-300' ?>">
                <?= e($flash['mensaje']) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?= url('auth/recuperar') ?>" class="space-y-4">
            <!-- IMPORTANTE: Este campo oculto identifica la solicitud de recuperación -->
            <input type="hidden" name="recuperar" value="1">
            
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Correo electrónico</label>
                <input type="email" name="email" required
                    placeholder="usuario@gmail.com"
                    class="w-full border border-slate-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 transition" 
                    style="border-color: #B8CCE3; outline-color: #2F5A8A;">
            </div>
            <button type="submit" class="w-full text-white font-bold py-3 rounded-lg transition hover:opacity-90" style="background: #2F5A8A;">
                <i class="fas fa-envelope mr-2"></i> Enviar enlace de recuperación
            </button>
        </form>

        <div class="mt-6 pt-4 border-t border-slate-200 text-center">
            <a href="<?= url('auth/login') ?>" class="text-sm hover:underline" style="color: #2F5A8A;">
                ← Volver al inicio de sesión
            </a>
        </div>
        
        <div class="mt-4 text-xs text-center text-slate-400">
            <p>🔒 Recibirás un enlace en tu correo para restablecer tu contraseña</p>
        </div>
    </div>
</div>