<div class="max-w-4xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-slate-800">
            <i class="fas fa-building mr-2 text-primary-500"></i> Gestión de Empresa
        </h2>
        <span class="text-sm text-slate-500">Edita la información corporativa</span>
    </div>

    <?php if ($flash = getFlash()): ?>
        <div class="mb-4 rounded-lg px-4 py-3 text-sm font-semibold <?= $flash['tipo'] === 'exito' ? 'bg-green-100 text-green-800 border border-green-300' : 'bg-red-100 text-red-800 border border-red-300' ?>">
            <?= e($flash['mensaje']) ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="<?= url('admin/empresa/actualizar') ?>" class="space-y-6">
        <!-- ============================================ -->
        <!-- DESCRIPCIÓN CORPORATIVA -->
        <!-- ============================================ -->
        <div class="bg-white rounded-xl shadow p-6 border-t-4 border-primary-500">
            <h3 class="text-lg font-bold text-slate-800 mb-4">
                <i class="fas fa-file-alt mr-2 text-primary-500"></i> Descripción Corporativa
            </h3>
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Descripción de la empresa</label>
                <textarea name="descripcion_corporativa" rows="6"
                          class="w-full border border-slate-300 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition"><?= e($empresa['descripcion_corporativa'] ?? '') ?></textarea>
                <p class="text-xs text-slate-400 mt-1">Describe la misión, visión y valores de tu empresa.</p>
            </div>
        </div>

        <!-- ============================================ -->
        <!-- CONTACTO Y UBICACIÓN -->
        <!-- ============================================ -->
        <div class="bg-white rounded-xl shadow p-6 border-t-4 border-primary-500">
            <h3 class="text-lg font-bold text-slate-800 mb-4">
                <i class="fas fa-address-card mr-2 text-primary-500"></i> Contacto y Ubicación
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1">Email principal</label>
                    <input type="email" name="email_principal" 
                           value="<?= e($empresa['email_principal'] ?? '') ?>"
                           placeholder="contacto@empresa.com"
                           class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1">Enlace GPS (Google Maps)</label>
                    <input type="url" name="enlace_gps" 
                           value="<?= e($empresa['enlace_gps'] ?? '') ?>"
                           placeholder="https://maps.google.com/..."
                           class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition">
                </div>
            </div>
            <div class="mt-4">
                <label class="block text-sm font-bold text-slate-700 mb-1">Dirección textual</label>
                <textarea name="direccion_textual" rows="2"
                          placeholder="Av. Principal 123, Lima, Perú"
                          class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition"><?= e($empresa['direccion_textual'] ?? '') ?></textarea>
            </div>
        </div>

        <!-- ============================================ -->
        <!-- REDES SOCIALES -->
        <!-- ============================================ -->
        <div class="bg-white rounded-xl shadow p-6 border-t-4 border-primary-500">
            <h3 class="text-lg font-bold text-slate-800 mb-4">
                <i class="fas fa-share-alt mr-2 text-primary-500"></i> Redes Sociales
            </h3>
            <p class="text-sm text-slate-500 mb-4">Ingresa los enlaces completos a tus redes sociales. Déjalos vacíos si no deseas mostrarlos.</p>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Facebook -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1">
                        <i class="fab fa-facebook mr-2 text-blue-600"></i> Facebook
                    </label>
                    <input type="url" name="facebook" 
                           value="<?= e($empresa['facebook'] ?? '') ?>"
                           placeholder="https://facebook.com/tuempresa"
                           class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition">
                </div>

                <!-- Instagram -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1">
                        <i class="fab fa-instagram mr-2 text-pink-600"></i> Instagram
                    </label>
                    <input type="url" name="instagram" 
                           value="<?= e($empresa['instagram'] ?? '') ?>"
                           placeholder="https://instagram.com/tuempresa"
                           class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition">
                </div>

                <!-- TikTok -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1">
                        <i class="fab fa-tiktok mr-2 text-black"></i> TikTok
                    </label>
                    <input type="url" name="tiktok" 
                           value="<?= e($empresa['tiktok'] ?? '') ?>"
                           placeholder="https://tiktok.com/@tuempresa"
                           class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition">
                </div>

                <!-- YouTube -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1">
                        <i class="fab fa-youtube mr-2 text-red-600"></i> YouTube
                    </label>
                    <input type="url" name="youtube" 
                           value="<?= e($empresa['youtube'] ?? '') ?>"
                           placeholder="https://youtube.com/@tuempresa"
                           class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition">
                </div>

                <!-- WhatsApp -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-bold text-slate-700 mb-1">
                        <i class="fab fa-whatsapp mr-2 text-green-600"></i> WhatsApp
                    </label>
                    <div class="flex items-center gap-2">
                        <span class="bg-slate-100 border border-slate-300 rounded-lg px-4 py-2 text-slate-600 font-semibold text-sm whitespace-nowrap">
                            +591
                        </span>
                        <input type="text" name="whatsapp" 
                            value="<?= e(preg_replace('/^\+591/', '', $empresa['whatsapp'] ?? '')) ?>"
                            placeholder="76543210"
                            class="flex-1 border border-slate-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition">
                    </div>
                    <p class="text-xs text-slate-400 mt-1">Ingresa solo el número de celular boliviano (ej: 76543210). El prefijo +591 se agregará automáticamente.</p>
                </div>
            </div>
        </div>

        <!-- ============================================ -->
        <!-- BOTÓN GUARDAR -->
        <!-- ============================================ -->
        <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-bold py-3 rounded-xl transition shadow-md hover:shadow-lg">
            <i class="fas fa-save mr-2"></i> Guardar Todos los Cambios
        </button>
    </form>
</div>