<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-xl shadow p-6 border-t-4 border-primary-500">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-slate-800">
                <i class="fas fa-plus-circle mr-2 text-primary-500"></i> Nuevo Servicio
            </h2>
            <a href="<?= url('admin/servicios') ?>" class="text-sm text-slate-500 hover:text-primary-600 transition">
                <i class="fas fa-arrow-left mr-1"></i> Volver
            </a>
        </div>

        <?php if ($flash = getFlash()): ?>
            <div class="mb-4 rounded-lg px-4 py-3 text-sm font-semibold <?= $flash['tipo'] === 'exito' ? 'bg-green-100 text-green-800 border border-green-300' : 'bg-red-100 text-red-800 border border-red-300' ?>">
                <?= e($flash['mensaje']) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?= url('admin/servicios/guardar') ?>" class="space-y-4" enctype="multipart/form-data" id="formServicio">
            <!-- ============================================ -->
            <!-- CAMPO OCULTO PARA SUBSECCIONES               -->
            <!-- ============================================ -->
            <input type="hidden" name="subsecciones" id="subseccionesHidden" value="">

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Título <span class="text-red-500">*</span></label>
                <input type="text" name="titulo" id="titulo" required
                       placeholder="Nombre del servicio"
                       class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition">
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
                <p class="text-xs text-slate-400 mt-1">El siguiente orden disponible es: <strong class="text-primary-600"><?= $ultimoOrden ?? 1 ?></strong></p>
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" name="activo" id="activo" checked
                       class="w-4 h-4 text-primary-600 border-slate-300 rounded focus:ring-primary-500">
                <label for="activo" class="text-sm font-medium text-slate-700">Activo</label>
            </div>

            <!-- Subsecciones Dinámicas -->
            <div class="pt-4 border-t border-slate-200">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-slate-800">
                        <i class="fas fa-list-ul mr-2 text-primary-500"></i> Subsecciones
                    </h3>
                    <button type="button" id="addSubseccion" 
                            class="bg-primary-500 hover:bg-primary-600 text-white px-4 py-2 rounded-lg text-sm font-semibold transition">
                        <i class="fas fa-plus mr-1"></i> Agregar Subsección
                    </button>
                </div>
                <p class="text-xs text-slate-400 mb-3">Cada servicio puede tener una o varias subsecciones con subtítulo y descripción.</p>
                
                <div id="subseccionesContainer" class="space-y-3">
                    <!-- Las subsecciones se agregarán aquí dinámicamente -->
                </div>
            </div>

            <div class="flex gap-3 pt-4 border-t border-slate-200">
                <button type="submit" 
                        class="bg-primary-500 hover:bg-primary-600 text-white px-6 py-2.5 rounded-lg font-semibold transition shadow-md hover:shadow-lg">
                    <i class="fas fa-save mr-2"></i> Guardar
                </button>
                <a href="<?= url('admin/servicios') ?>" 
                   class="bg-slate-200 hover:bg-slate-300 text-slate-700 px-6 py-2.5 rounded-lg font-semibold transition">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>




<script>
document.addEventListener('DOMContentLoaded', function() {
    // ============================================
    // PREVIEW DE IMAGEN
    // ============================================
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

    // ============================================
    // SUBSECCIONES DINÁMICAS
    // ============================================
    const container = document.getElementById('subseccionesContainer');

    function agregarSubseccion(subtitulo = '', descripcion = '') {
        const div = document.createElement('div');
        div.className = 'subseccion-item bg-primary-50/50 rounded-lg p-4 border border-primary-100 relative';
        div.innerHTML = `
            <button type="button" class="remove-subseccion absolute top-2 right-2 text-red-500 hover:text-red-700 transition" title="Eliminar subsección">
                <i class="fas fa-times"></i>
            </button>
            <div class="grid grid-cols-1 gap-3">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Subtítulo <span class="text-red-500">*</span></label>
                    <input type="text" class="subseccion-subtitulo w-full border border-slate-300 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition" 
                           value="${subtitulo}"
                           placeholder="Subtítulo de la subsección">
                </div>
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1">Descripción <span class="text-red-500">*</span></label>
                    <textarea class="subseccion-descripcion w-full border border-slate-300 rounded-lg px-3 py-1.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition" 
                              rows="2"
                              placeholder="Descripción de la subsección">${descripcion}</textarea>
                </div>
            </div>
        `;
        container.appendChild(div);

        // Eliminar subsección
        div.querySelector('.remove-subseccion').addEventListener('click', function(e) {
            e.preventDefault();
            div.remove();
        });

        // Resetear borde al escribir
        const subtituloInput = div.querySelector('.subseccion-subtitulo');
        const descripcionInput = div.querySelector('.subseccion-descripcion');
        
        subtituloInput.addEventListener('input', function() {
            this.style.borderColor = '';
        });
        descripcionInput.addEventListener('input', function() {
            this.style.borderColor = '';
        });
    }

    // ============================================
    // BOTÓN AGREGAR SUBSECCIÓN - CON VALIDACIÓN
    // ============================================
    document.getElementById('addSubseccion').addEventListener('click', function() {
        // Verificar si la última subsección está vacía
        const items = container.querySelectorAll('.subseccion-item');
        if (items.length > 0) {
            const lastItem = items[items.length - 1];
            const lastSubtitulo = lastItem.querySelector('.subseccion-subtitulo').value.trim();
            const lastDescripcion = lastItem.querySelector('.subseccion-descripcion').value.trim();
            
            if (!lastSubtitulo || !lastDescripcion) {
                alert('⚠️ La subsección actual tiene campos vacíos. Complétalos antes de agregar otra.');
                
                if (!lastSubtitulo) {
                    lastItem.querySelector('.subseccion-subtitulo').style.borderColor = '#ef4444';
                    lastItem.querySelector('.subseccion-subtitulo').focus();
                }
                if (!lastDescripcion) {
                    lastItem.querySelector('.subseccion-descripcion').style.borderColor = '#ef4444';
                }
                return;
            }
        }
        agregarSubseccion();
    });

    // ============================================
    // VALIDACIÓN DE ORDEN
    // ============================================
    const ordenInput = document.getElementById('orden');
    ordenInput.addEventListener('change', function() {
        if (this.value < 0) {
            this.value = 0;
        }
    });

    // ============================================
    // AL ENVIAR EL FORMULARIO - VALIDAR SUBSECCIONES
    // ============================================
    document.getElementById('formServicio').addEventListener('submit', function(e) {
        const subsecciones = [];
        const items = container.querySelectorAll('.subseccion-item');
        let hasError = false;
        
        items.forEach(item => {
            const subtitulo = item.querySelector('.subseccion-subtitulo').value.trim();
            const descripcion = item.querySelector('.subseccion-descripcion').value.trim();
            
            // Validar que ambos campos estén completos
            if (!subtitulo || !descripcion) {
                hasError = true;
                if (!subtitulo) {
                    item.querySelector('.subseccion-subtitulo').style.borderColor = '#ef4444';
                    item.querySelector('.subseccion-subtitulo').focus();
                }
                if (!descripcion) {
                    item.querySelector('.subseccion-descripcion').style.borderColor = '#ef4444';
                }
            } else {
                subsecciones.push({ subtitulo, descripcion });
            }
        });
        
        if (hasError) {
            e.preventDefault();
            alert('⚠️ Todas las subsecciones deben tener subtítulo y descripción completos.');
            return false;
        }
        
        // Guardar en el campo oculto
        document.getElementById('subseccionesHidden').value = JSON.stringify(subsecciones);
    });
});
</script>