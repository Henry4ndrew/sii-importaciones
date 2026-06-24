<div class="max-w-6xl mx-auto px-4">
    <div class="max-w-4xl mx-auto mt-8">
        <div id="acceso" class="rounded-2xl overflow-hidden border border-white/20 shadow-2xl bg-primary-900/80 backdrop-blur-sm scroll-mt-20">

            <!-- Contenido -->
            <div class="px-6 pt-6 pb-4 text-center border-b border-white/10">
                <!-- Logo -->
                <div class="flex justify-center mb-4">
                    <img src="<?= url('public/img/logo-SII.avif') ?>" 
                         alt="Logo SII" 
                         class="h-16 w-auto object-contain">
                </div>
                
                <h2 class="text-2xl font-extrabold text-white leading-tight">
                    Acceso
                    <span class="text-[#00eeff]">Administrador</span>
                </h2>
                <p class="text-primary-50/80 text-sm mt-2 flex items-center justify-center gap-2">
                    <i class="fa-solid fa-shield-halved text-[#00eeff]/60"></i>
                    Ingresa tus credenciales para acceder al panel de administración
                </p>
            </div>

            <div class="p-6">
                <?php if ($flash = getFlash()): ?>
                    <div class="mb-4 rounded-xl px-4 py-3 text-sm font-semibold border
                        <?= $flash['tipo'] === 'exito'
                            ? 'bg-green-900/40 text-green-300 border-green-500/30'
                            : 'bg-red-900/40 text-red-300 border-red-500/30' ?>">
                        <i class="fa-solid fa-<?= $flash['tipo'] === 'exito' ? 'circle-check' : 'circle-exclamation' ?> mr-2"></i>
                        <?= e($flash['mensaje']) ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="<?= url('auth/login') ?>" class="max-w-md mx-auto space-y-4">
                    <div>
                        <label class="block text-sm font-bold text-primary-50/80 mb-1 text-left">
                            <i class="fa-solid fa-envelope text-[#00eeff]/60 mr-2"></i>
                            Correo electrónico
                        </label>
                        <input type="email" name="email" required
                            placeholder="usuario@gmail.com"
                            class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-3 text-white placeholder:text-white/40 focus:outline-none focus:ring-2 focus:ring-[#00eeff]/50 focus:border-[#00eeff]/50 transition-all duration-200">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-bold text-primary-50/80 mb-1 text-left">
                            <i class="fa-solid fa-lock text-[#00eeff]/60 mr-2"></i>
                            Contraseña
                        </label>
                        <input type="password" name="password" required
                            placeholder="••••••••"
                            class="w-full bg-white/10 border border-white/20 rounded-xl px-4 py-3 text-white placeholder:text-white/40 focus:outline-none focus:ring-2 focus:ring-[#00eeff]/50 focus:border-[#00eeff]/50 transition-all duration-200">
                    </div>

                    <button type="submit" 
                        class="w-full px-5 py-2 md:px-6 md:py-2.5 rounded-xl inline-flex items-center justify-center gap-2 font-bold text-white bg-gradient-to-r from-[#00c8d7] to-primary-500 hover:from-primary-900 hover:to-[#00c8d7] transition-all duration-300 ease-in-out [text-shadow:0_1px_4px_rgba(0,0,0,0.4)]">
                        <i class="fa-solid fa-right-to-bracket"></i>
                        Ingresar como Administrador
                    </button>
                </form>

                <div class="w-12 h-0.5 bg-[#00eeff]/40 mx-auto my-5 rounded-full"></div>

                <div class="text-center space-y-3">
                    <a href="<?= url('auth/recuperar') ?>" 
                        class="text-sm font-semibold text-primary-50/70 hover:text-[#00eeff] transition-colors duration-200 inline-flex items-center gap-1">
                        <i class="fa-solid fa-key text-[#00eeff]/60 text-xs"></i>
                        ¿Olvidaste tu contraseña?
                    </a>
                    
                    <div class="pt-4 border-t border-white/10">
                        <a href="<?= url('/') ?>" 
                            class="text-sm text-primary-50/50 hover:text-[#00eeff] transition-colors duration-200 inline-flex items-center gap-1">
                            <i class="fa-solid fa-arrow-left text-[#00eeff]/60 text-xs"></i>
                            Volver al inicio
                        </a>
                    </div>
                </div>

                <div class="mt-5 pt-4 border-t border-white/10 text-center">
                    <p class="text-xs text-primary-50/40 flex items-center justify-center gap-2">
                        <i class="fa-solid fa-shield-halved text-[#00eeff]/60"></i>
                        <span>🔒 Acceso restringido solo para administradores</span>
                        <i class="fa-solid fa-shield-halved text-[#00eeff]/60"></i>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>