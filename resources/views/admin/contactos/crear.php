<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-xl shadow p-6 border-t-4 border-primary-500">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-slate-800">
                <i class="fas fa-plus-circle mr-2 text-primary-500"></i> Nuevo Contacto
            </h2>
            <a href="<?= url('admin/contactos') ?>" class="text-sm text-slate-500 hover:text-primary-600 transition">
                <i class="fas fa-arrow-left mr-1"></i> Volver
            </a>
        </div>

        <?php if ($flash = getFlash()): ?>
            <div class="mb-4 rounded-lg px-4 py-3 text-sm font-semibold <?= $flash['tipo'] === 'exito' ? 'bg-green-100 text-green-800 border border-green-300' : 'bg-red-100 text-red-800 border border-red-300' ?>">
                <?= e($flash['mensaje']) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?= url('admin/contactos/guardar') ?>" class="space-y-4" enctype="multipart/form-data">
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Ciudad <span class="text-red-500">*</span></label>
                <input type="text" name="ciudad" required
                       placeholder="Ej: Lima - Perú"
                       class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition">
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Teléfono <span class="text-red-500">*</span></label>
                <input type="text" name="telefono" required
                       placeholder="Ej: +51 999 999 999"
                       class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition">
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Correo electrónico <span class="text-red-500">*</span></label>
                <input type="email" name="correo" required
                       placeholder="contacto@correo.com"
                       class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition">
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Horarios</label>
                <textarea name="horarios" rows="3"
                          placeholder="Ej: Lunes a Viernes: 9:00 AM - 6:00 PM"
                          class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition"></textarea>
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Imagen (opcional)</label>
                <div class="relative border-2 border-dashed border-slate-300 rounded-lg p-6 hover:border-primary-400 transition group">
                    <input type="file" name="imagen" id="imagen" accept="image/*"
                           class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                    <div class="text-center">
                        <div class="text-4xl mb-2 text-slate-400 group-hover:text-primary-400 transition">
                            <i class="fas fa-cloud-upload-alt"></i>
                        </div>
                        <p class="text-sm text-slate-600">Haz clic o arrastra para seleccionar una imagen</p>
                        <p class="text-xs text-slate-400 mt-1">Formatos: JPG, PNG, WEBP, AVIF, GIF • Máx: 5MB</p>
                        <div id="previewContainer" class="mt-3 hidden">
                            <img id="imagePreview" src="#" alt="Vista previa" class="max-h-48 mx-auto rounded-lg shadow">
                            <button type="button" id="removeImage" class="mt-2 text-sm text-red-500 hover:text-red-700">
                                <i class="fas fa-times mr-1"></i> Eliminar imagen
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Orden</label>
                <div class="flex items-center gap-3">
                    <input type="number" name="orden" id="orden" 
                           value="<?= $ultimoOrden ?? 0 ?>"
                           min="1"
                           class="w-32 border border-slate-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition">
                    <span class="text-sm text-slate-500">
                        <i class="fas fa-info-circle text-primary-400 mr-1"></i>
                        Déjalo en 0 para asignar automáticamente
                    </span>
                </div>
                <p class="text-xs text-slate-400 mt-1">Número menor = aparece primero. El siguiente orden disponible es: <strong class="text-primary-600"><?= $ultimoOrden ?? 1 ?></strong></p>
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" name="activo" id="activo" checked
                       class="w-4 h-4 text-primary-600 border-slate-300 rounded focus:ring-primary-500">
                <label for="activo" class="text-sm font-medium text-slate-700">Activo</label>
            </div>

            <div class="flex gap-3 pt-4 border-t border-slate-200">
                <button type="submit" 
                        class="bg-primary-500 hover:bg-primary-600 text-white px-6 py-2.5 rounded-lg font-semibold transition shadow-md hover:shadow-lg">
                    <i class="fas fa-save mr-2"></i> Guardar
                </button>
                <a href="<?= url('admin/contactos') ?>" 
                   class="bg-slate-200 hover:bg-slate-300 text-slate-700 px-6 py-2.5 rounded-lg font-semibold transition">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const inputFile = document.getElementById('imagen');
    const previewContainer = document.getElementById('previewContainer');
    const imagePreview = document.getElementById('imagePreview');
    const removeBtn = document.getElementById('removeImage');

    inputFile.addEventListener('change', function(e) {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.src = e.target.result;
                previewContainer.classList.remove('hidden');
            }
            reader.readAsDataURL(file);
        }
    });

    removeBtn.addEventListener('click', function() {
        inputFile.value = '';
        previewContainer.classList.add('hidden');
        imagePreview.src = '#';
    });

    const ordenInput = document.getElementById('orden');
    ordenInput.addEventListener('change', function() {
        if (this.value < 0) {
            this.value = 0;
        }
    });
});
</script>