<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-xl shadow p-6 border-t-4 border-primary-500">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-slate-800">
                <i class="fas fa-edit mr-2 text-primary-500"></i> Editar Portada
            </h2>
            <a href="<?= url('admin/portadas') ?>" class="text-sm text-slate-500 hover:text-primary-600 transition">
                <i class="fas fa-arrow-left mr-1"></i> Volver
            </a>
        </div>

        <?php if ($flash = getFlash()): ?>
            <div class="mb-4 rounded-lg px-4 py-3 text-sm font-semibold <?= $flash['tipo'] === 'exito' ? 'bg-green-100 text-green-800 border border-green-300' : 'bg-red-100 text-red-800 border border-red-300' ?>">
                <?= e($flash['mensaje']) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?= url('admin/portadas/actualizar') ?>" class="space-y-4" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= $portada['id'] ?>">

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Título <span class="text-red-500">*</span></label>
                <input type="text" name="titulo" required
                       value="<?= e($portada['titulo']) ?>"
                       class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition">
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Descripción</label>
                <textarea name="descripcion" rows="3"
                          class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition"><?= e($portada['descripcion'] ?? '') ?></textarea>
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Imagen actual</label>
                <?php if (!empty($portada['ruta_imagen'])): ?>
                    <div class="mb-3">
                        <img src="<?= url('public/img/' . $portada['ruta_imagen']) ?>" 
                             alt="<?= e($portada['titulo']) ?>" 
                             class="max-h-32 rounded-lg shadow border border-slate-200">
                    </div>
                <?php endif; ?>
                
                <div class="relative border-2 border-dashed border-slate-300 rounded-lg p-6 hover:border-primary-400 transition group">
                    <input type="file" name="imagen" id="imagen" accept="image/*"
                           class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                    <div class="text-center">
                        <div class="text-4xl mb-2 text-slate-400 group-hover:text-primary-400 transition">
                            <i class="fas fa-cloud-upload-alt"></i>
                        </div>
                        <p class="text-sm text-slate-600">Haz clic para cambiar la imagen (opcional)</p>
                        <p class="text-xs text-slate-400 mt-1">Formatos: JPG, PNG, WEBP, AVIF, GIF • Máx: 5MB</p>
                        <div id="previewContainer" class="mt-3 hidden">
                            <img id="imagePreview" src="#" alt="Vista previa" class="max-h-48 mx-auto rounded-lg shadow">
                            <button type="button" id="removeImage" class="mt-2 text-sm text-red-500 hover:text-red-700">
                                <i class="fas fa-times mr-1"></i> Cancelar selección
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Orden</label>
                <div class="flex items-center gap-3">
                    <input type="number" name="orden" id="orden" 
                           value="<?= $portada['orden'] ?>"
                           min="1"
                           class="w-32 border border-slate-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition">
                    <span class="text-sm text-slate-500">
                        <i class="fas fa-info-circle text-primary-400 mr-1"></i>
                        Al cambiar el orden, se intercambiará con la portada que tenga ese número
                    </span>
                </div>
                <p class="text-xs text-slate-400 mt-1">Número menor = aparece primero. Los números se intercambian automáticamente.</p>
                <div id="ordenInfo" class="text-xs text-primary-600 mt-1 hidden">
                    <i class="fas fa-exchange-alt mr-1"></i> Se intercambiará el orden con la portada que tenga este número
                </div>
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" name="activo" id="activo" <?= $portada['activo'] ? 'checked' : '' ?>
                       class="w-4 h-4 text-primary-600 border-slate-300 rounded focus:ring-primary-500">
                <label for="activo" class="text-sm font-medium text-slate-700">Activo</label>
            </div>

            <div class="flex gap-3 pt-4 border-t border-slate-200">
                <button type="submit" 
                        class="bg-primary-500 hover:bg-primary-600 text-white px-6 py-2.5 rounded-lg font-semibold transition shadow-md hover:shadow-lg">
                    <i class="fas fa-save mr-2"></i> Actualizar
                </button>
                <a href="<?= url('admin/portadas') ?>" 
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
    const ordenInput = document.getElementById('orden');
    const ordenInfo = document.getElementById('ordenInfo');

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

    // Mostrar información sobre intercambio de órdenes
    const ordenActual = <?= $portada['orden'] ?>;
    ordenInput.addEventListener('input', function() {
        const nuevoOrden = parseInt(this.value);
        if (nuevoOrden > 0 && nuevoOrden !== ordenActual) {
            ordenInfo.classList.remove('hidden');
        } else {
            ordenInfo.classList.add('hidden');
        }
    });
});
</script>