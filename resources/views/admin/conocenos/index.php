<div class="max-w-5xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-slate-800">
            <i class="fas fa-info-circle mr-2 text-primary-500"></i> Gestión de Conócenos
        </h2>
        <span class="text-sm text-slate-500">Edita la información de la página</span>
    </div>

    <?php if ($flash = getFlash()): ?>
        <div class="mb-4 rounded-lg px-4 py-3 text-sm font-semibold <?= $flash['tipo'] === 'exito' ? 'bg-green-100 text-green-800 border border-green-300' : 'bg-red-100 text-red-800 border border-red-300' ?>">
            <?= e($flash['mensaje']) ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="<?= url('admin/conocenos/actualizar') ?>" enctype="multipart/form-data" id="formConocenos">
        <!-- ============================================ -->
        <!-- ENCABEZADO -->
        <!-- ============================================ -->
        <div class="bg-white rounded-xl shadow p-6 mb-6 border-t-4 border-primary-500">
            <h3 class="text-lg font-bold text-slate-800 mb-4">
                <i class="fas fa-heading mr-2 text-primary-500"></i> Encabezado
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1">Título principal</label>
                    <input type="text" name="encabezado_titulo" 
                           value="<?= e($conocenos['encabezado_titulo'] ?? 'Sobre Nosotros') ?>"
                           class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1">Imagen</label>
                    <div class="relative border-2 border-dashed border-slate-300 rounded-lg p-4 hover:border-primary-400 transition group">
                        <input type="file" name="encabezado_imagen" accept="image/*" class="imagen-preview-input absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                        <div class="text-center">
                            <?php if (!empty($conocenos['encabezado_imagen'])): ?>
                                <img src="<?= url('public/img/' . $conocenos['encabezado_imagen']) ?>" 
                                     alt="Encabezado" 
                                     class="max-h-24 mx-auto rounded-lg border border-slate-200">
                            <?php else: ?>
                                <div class="text-3xl text-slate-400"><i class="fas fa-image"></i></div>
                                <p class="text-xs text-slate-500 mt-1">Haz clic para cambiar</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-3">
                <label class="block text-sm font-bold text-slate-700 mb-1">Descripción principal</label>
                <textarea name="encabezado_descripcion" rows="3"
                          class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500"><?= e($conocenos['encabezado_descripcion'] ?? '') ?></textarea>
            </div>
        </div>

        <!-- ============================================ -->
        <!-- MISIÓN, VISIÓN, HISTORIA -->
        <!-- ============================================ -->
        <?php 
        $secciones = [
            'mision' => ['icono' => 'fa-bullseye', 'label' => 'Misión'],
            'vision' => ['icono' => 'fa-eye', 'label' => 'Visión'],
            'historia' => ['icono' => 'fa-book-open', 'label' => 'Nuestra Historia']
        ];
        foreach ($secciones as $key => $seccion): 
        ?>
        <div class="bg-white rounded-xl shadow p-6 mb-6 border-t-4 border-primary-500">
            <h3 class="text-lg font-bold text-slate-800 mb-4">
                <i class="fas <?= $seccion['icono'] ?> mr-2 text-primary-500"></i> <?= $seccion['label'] ?>
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1">Texto</label>
                    <textarea name="<?= $key ?>_texto" rows="4"
                              class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500"><?= e($conocenos[$key . '_texto'] ?? '') ?></textarea>
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1">Imagen</label>
                    <div class="relative border-2 border-dashed border-slate-300 rounded-lg p-4 hover:border-primary-400 transition group">
                        <input type="file" name="<?= $key ?>_imagen" accept="image/*" class="imagen-preview-input absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                        <div class="text-center">
                            <?php if (!empty($conocenos[$key . '_imagen'])): ?>
                                <img src="<?= url('public/img/' . $conocenos[$key . '_imagen']) ?>" 
                                     alt="<?= $seccion['label'] ?>" 
                                     class="max-h-24 mx-auto rounded-lg border border-slate-200">
                            <?php else: ?>
                                <div class="text-3xl text-slate-400"><i class="fas fa-image"></i></div>
                                <p class="text-xs text-slate-500 mt-1">Haz clic para cambiar</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>

        <!-- ============================================ -->
        <!-- EQUIPO - Campo Dinámico -->
        <!-- ============================================ -->
        <div class="bg-white rounded-xl shadow p-6 mb-6 border-t-4 border-primary-500">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-slate-800">
                    <i class="fas fa-users mr-2 text-primary-500"></i> Nuestro Equipo
                </h3>
                <button type="button" id="addMiembroBtn" 
                        class="bg-primary-500 hover:bg-primary-600 text-white px-4 py-2 rounded-lg text-sm font-semibold transition">
                    <i class="fas fa-plus mr-1"></i> Agregar Miembro
                </button>
            </div>
            
            <div id="equipoContainer" class="space-y-3">
                <?php foreach ($equipo as $miembro): ?>
                    <div class="miembro-item bg-primary-50/50 rounded-lg p-4 border border-primary-100 relative">
                        <button type="button" class="remove-miembro absolute top-2 right-2 text-red-500 hover:text-red-700 transition" title="Eliminar miembro">
                            <i class="fas fa-times"></i>
                        </button>
                        <input type="hidden" name="equipo_id[]" value="<?= $miembro['id'] ?>">
                        <div class="grid grid-cols-1 md:grid-cols-4 gap-3">
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Imagen</label>
                                <div class="relative border-2 border-dashed border-slate-300 rounded-lg p-2 hover:border-primary-400 transition group">
                                    <input type="file" class="miembro-imagen-input" accept="image/*"
                                           name="equipo_imagen_<?= $miembro['id'] ?>"
                                           style="position:absolute;inset:0;width:100%;height:100%;opacity:0;cursor:pointer;">
                                    <div class="text-center">
                                        <?php if (!empty($miembro['imagen'])): ?>
                                            <img src="<?= url('public/img/' . $miembro['imagen']) ?>" 
                                                 alt="<?= e($miembro['nombre']) ?>" 
                                                 class="max-h-16 mx-auto rounded-lg border border-slate-200 miembro-img-<?= $miembro['id'] ?>">
                                        <?php else: ?>
                                            <div class="text-2xl text-slate-400"><i class="fas fa-user"></i></div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Nombre *</label>
                                <input type="text" class="miembro-nombre w-full border border-slate-300 rounded-lg px-3 py-1.5 text-sm"
                                       name="equipo_nombre_<?= $miembro['id'] ?>"
                                       value="<?= e($miembro['nombre']) ?>"
                                       placeholder="Nombre completo">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-700 mb-1">Cargo *</label>
                                <input type="text" class="miembro-cargo w-full border border-slate-300 rounded-lg px-3 py-1.5 text-sm"
                                       name="equipo_cargo_<?= $miembro['id'] ?>"
                                       value="<?= e($miembro['cargo']) ?>"
                                       placeholder="Cargo / Rol">
                            </div>
                            <div class="flex items-end">
                                <div class="text-xs text-slate-400 w-full text-center py-1.5">
                                    <i class="fas fa-sync-alt text-primary-400 mr-1"></i> Se guarda con "Guardar todos"
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <button type="submit" class="w-full bg-primary-600 hover:bg-primary-700 text-white font-bold py-3 rounded-xl transition shadow-md hover:shadow-lg">
            <i class="fas fa-save mr-2"></i> Guardar Todos los Cambios
        </button>
    </form>
</div>

<!-- ============================================ -->
<!-- MODAL PARA AGREGAR MIEMBRO -->
<!-- ============================================ -->
<div id="modalAgregarMiembro" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 hidden">
    <div class="bg-white rounded-xl shadow-2xl p-6 max-w-md w-full mx-4">
        <h3 class="text-xl font-bold text-slate-800 mb-4">
            <i class="fas fa-user-plus mr-2 text-primary-500"></i> Agregar Miembro del Equipo
        </h3>
        <form id="formAgregarMiembro" enctype="multipart/form-data">
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1">Nombre *</label>
                    <input type="text" id="modalNombre" required
                           class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1">Cargo *</label>
                    <input type="text" id="modalCargo" required
                           class="w-full border border-slate-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
                </div>
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1">Imagen</label>
                    <input type="file" id="modalImagen" accept="image/*"
                           class="w-full border border-slate-300 rounded-lg px-4 py-2">
                    <div id="modalPreview" class="mt-2 hidden">
                        <img src="#" alt="Vista previa" class="max-h-32 rounded-lg border border-slate-200">
                    </div>
                </div>
                <div class="flex gap-3 pt-4">
                    <button type="submit" class="bg-primary-500 hover:bg-primary-600 text-white px-6 py-2 rounded-lg font-semibold transition">
                        <i class="fas fa-save mr-2"></i> Guardar
                    </button>
                    <button type="button" id="cerrarModal" class="bg-slate-200 hover:bg-slate-300 text-slate-700 px-6 py-2 rounded-lg font-semibold transition">
                        Cancelar
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // ============================================
    // VISTA PREVIA DE IMÁGENES - Todas las secciones
    // ============================================
    document.querySelectorAll('.imagen-preview-input').forEach(function(input) {
        input.addEventListener('change', function(e) {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                const container = this.closest('.relative');
                const textCenter = container.querySelector('.text-center');
                
                reader.onload = function(e) {
                    // Limpiar y mostrar la nueva imagen
                    textCenter.innerHTML = `<img src="${e.target.result}" alt="Vista previa" class="max-h-24 mx-auto rounded-lg border border-slate-200">`;
                }
                reader.readAsDataURL(file);
            }
        });
    });

    // ============================================
    // VISTA PREVIA - Inputs de equipo
    // ============================================
    document.querySelectorAll('.miembro-imagen-input').forEach(function(input) {
        input.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                const container = this.closest('.relative');
                const textCenter = container.querySelector('.text-center');
                
                reader.onload = function(e) {
                    textCenter.innerHTML = `<img src="${e.target.result}" alt="Vista previa" class="max-h-16 mx-auto rounded-lg border border-slate-200">`;
                }
                reader.readAsDataURL(file);
            }
        });
    });

    // ============================================
    // MODAL PARA AGREGAR MIEMBRO
    // ============================================
    const modal = document.getElementById('modalAgregarMiembro');
    const addBtn = document.getElementById('addMiembroBtn');
    const cerrarModal = document.getElementById('cerrarModal');
    const formAgregar = document.getElementById('formAgregarMiembro');

    addBtn.addEventListener('click', function() {
        modal.classList.remove('hidden');
        document.getElementById('modalNombre').value = '';
        document.getElementById('modalCargo').value = '';
        document.getElementById('modalImagen').value = '';
        document.getElementById('modalPreview').classList.add('hidden');
    });

    cerrarModal.addEventListener('click', function() {
        modal.classList.add('hidden');
    });

    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.classList.add('hidden');
        }
    });

    // Vista previa de imagen en modal
    document.getElementById('modalImagen').addEventListener('change', function(e) {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('modalPreview');
                preview.querySelector('img').src = e.target.result;
                preview.classList.remove('hidden');
            }
            reader.readAsDataURL(file);
        }
    });

    // Enviar formulario de agregar miembro
    formAgregar.addEventListener('submit', function(e) {
        e.preventDefault();
        const nombre = document.getElementById('modalNombre').value.trim();
        const cargo = document.getElementById('modalCargo').value.trim();
        const imagen = document.getElementById('modalImagen').files[0];

        if (!nombre || !cargo) {
            alert('Nombre y cargo son obligatorios.');
            return;
        }

        const formData = new FormData();
        formData.append('nombre', nombre);
        formData.append('cargo', cargo);
        if (imagen) {
            formData.append('imagen', imagen);
        }

        fetch('<?= url('admin/conocenos/equipo/guardar') ?>', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'Error al guardar');
            }
        })
        .catch(error => {
            alert('Error de conexión');
        });
    });

    // ============================================
    // ELIMINAR MIEMBRO - CON ACTUALIZACIÓN AUTOMÁTICA
    // ============================================
    document.querySelectorAll('.remove-miembro').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const item = this.closest('.miembro-item');
            const idInput = item.querySelector('input[name="equipo_id[]"]');
            if (!idInput) return;
            const id = idInput.value;
            
            if (confirm('¿Estás seguro de eliminar este miembro del equipo?')) {
                // Eliminar visualmente
                item.remove();
                
                // Enviar solicitud de eliminación
                fetch('<?= url('admin/conocenos/equipo/eliminar') ?>?id=' + id)
                .then(response => response.json())
                .then(data => {
                    if (!data.success) {
                        alert(data.message || 'Error al eliminar');
                        location.reload();
                    }
                })
                .catch(() => {
                    location.reload();
                });
            }
        });
    });
});
</script>