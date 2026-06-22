<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow overflow-hidden border-t-4 border-primary-500">
        <!-- Header -->
        <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
            <h2 class="text-xl font-bold text-slate-800">
                <i class="fas fa-eye mr-2 text-primary-500"></i> Detalle de Portada
            </h2>
            <div class="flex items-center gap-2">
                <a href="<?= url('admin/portadas/editar') ?>?id=<?= $portada['id'] ?>" 
                   class="bg-primary-500 hover:bg-primary-600 text-white px-4 py-2 rounded-lg text-sm font-semibold transition">
                    <i class="fas fa-edit mr-1"></i> Editar
                </a>
                <a href="<?= url('admin/portadas') ?>" 
                   class="bg-slate-200 hover:bg-slate-300 text-slate-700 px-4 py-2 rounded-lg text-sm font-semibold transition">
                    <i class="fas fa-arrow-left mr-1"></i> Volver
                </a>
            </div>
        </div>

        <!-- Contenido -->
        <div class="p-6 space-y-6">
            <!-- Imagen principal -->
            <div class="flex justify-center bg-primary-50/50 rounded-lg p-4">
                <?php if (!empty($portada['ruta_imagen'])): ?>
                    <img src="<?= url('public/img/' . $portada['ruta_imagen']) ?>" 
                         alt="<?= e($portada['titulo']) ?>" 
                         class="max-h-[400px] w-auto rounded-lg shadow-lg object-cover border-2 border-primary-100">
                <?php else: ?>
                    <div class="flex items-center justify-center w-full h-48 bg-slate-200 rounded-lg">
                        <span class="text-slate-400">Sin imagen</span>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Información -->
            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-sm font-semibold text-primary-600 uppercase tracking-wider">Título</h3>
                    <p class="text-lg font-bold text-slate-800 mt-1"><?= e($portada['titulo']) ?></p>
                </div>

                <div>
                    <h3 class="text-sm font-semibold text-primary-600 uppercase tracking-wider">Orden</h3>
                    <p class="text-lg font-bold text-slate-800 mt-1"><?= $portada['orden'] ?></p>
                </div>

                <div>
                    <h3 class="text-sm font-semibold text-primary-600 uppercase tracking-wider">Estado</h3>
                    <p class="mt-1">
                        <span class="px-3 py-1 text-sm font-semibold rounded-full <?= $portada['activo'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                            <?= $portada['activo'] ? '✅ Activo' : '❌ Inactivo' ?>
                        </span>
                    </p>
                </div>

                <div>
                    <h3 class="text-sm font-semibold text-primary-600 uppercase tracking-wider">Fecha de creación</h3>
                    <p class="text-lg font-bold text-slate-800 mt-1"><?= date('d/m/Y H:i', strtotime($portada['created_at'])) ?></p>
                </div>
            </div>

            <!-- Descripción -->
            <div>
                <h3 class="text-sm font-semibold text-primary-600 uppercase tracking-wider">Descripción</h3>
                <div class="mt-2 p-4 bg-primary-50/50 rounded-lg border border-primary-100">
                    <p class="text-slate-700"><?= e($portada['descripcion'] ?? 'Sin descripción') ?></p>
                </div>
            </div>

            <!-- Ruta de la imagen -->
            <div>
                <h3 class="text-sm font-semibold text-primary-600 uppercase tracking-wider">Ruta de la imagen</h3>
                <p class="mt-1 text-sm text-slate-600 bg-primary-50/50 p-2 rounded-lg font-mono border border-primary-100">
                    <?= e($portada['ruta_imagen']) ?>
                </p>
            </div>

            <!-- Acciones rápidas -->
            <div class="flex flex-wrap gap-3 pt-4 border-t border-slate-200">
                <a href="<?= url('admin/portadas/editar') ?>?id=<?= $portada['id'] ?>" 
                   class="bg-primary-500 hover:bg-primary-600 text-white px-4 py-2 rounded-lg text-sm font-semibold transition">
                    <i class="fas fa-edit mr-1"></i> Editar
                </a>
                <a href="<?= url('admin/portadas/toggle') ?>?id=<?= $portada['id'] ?>" 
                   class="<?= $portada['activo'] ? 'bg-yellow-500 hover:bg-yellow-600' : 'bg-green-500 hover:bg-green-600' ?> text-white px-4 py-2 rounded-lg text-sm font-semibold transition">
                    <i class="fas fa-<?= $portada['activo'] ? 'times' : 'check' ?> mr-1"></i>
                    <?= $portada['activo'] ? 'Desactivar' : 'Activar' ?>
                </a>
                <a href="<?= url('admin/portadas/eliminar') ?>?id=<?= $portada['id'] ?>" 
                   onclick="return confirm('¿Estás seguro de eliminar esta portada?')"
                   class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-semibold transition">
                    <i class="fas fa-trash mr-1"></i> Eliminar
                </a>
            </div>
        </div>
    </div>
</div>