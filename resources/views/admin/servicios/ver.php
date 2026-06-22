<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow overflow-hidden border-t-4 border-primary-500">
        <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
            <h2 class="text-xl font-bold text-slate-800">
                <i class="fas fa-eye mr-2 text-primary-500"></i> Detalle de Servicio
            </h2>
            <div class="flex items-center gap-2">
                <a href="<?= url('admin/servicios/editar') ?>?id=<?= $servicio['id'] ?>" 
                   class="bg-primary-500 hover:bg-primary-600 text-white px-4 py-2 rounded-lg text-sm font-semibold transition">
                    <i class="fas fa-edit mr-1"></i> Editar
                </a>
                <a href="<?= url('admin/servicios') ?>" 
                   class="bg-slate-200 hover:bg-slate-300 text-slate-700 px-4 py-2 rounded-lg text-sm font-semibold transition">
                    <i class="fas fa-arrow-left mr-1"></i> Volver
                </a>
            </div>
        </div>

        <div class="p-6 space-y-6">
            <!-- Imagen -->
            <?php if (!empty($servicio['imagen'])): ?>
                <div class="flex justify-center bg-primary-50/50 rounded-lg p-4">
                    <img src="<?= url('public/img/' . $servicio['imagen']) ?>" 
                         alt="<?= e($servicio['titulo']) ?>" 
                         class="max-h-[300px] w-auto rounded-lg shadow-lg object-cover border-2 border-primary-100">
                </div>
            <?php endif; ?>

            <div class="grid md:grid-cols-2 gap-6">
                <div>
                    <h3 class="text-sm font-semibold text-primary-600 uppercase tracking-wider">Título</h3>
                    <p class="text-lg font-bold text-slate-800 mt-1"><?= e($servicio['titulo']) ?></p>
                </div>

                <div>
                    <h3 class="text-sm font-semibold text-primary-600 uppercase tracking-wider">Orden</h3>
                    <p class="text-lg font-bold text-slate-800 mt-1"><?= $servicio['orden'] ?></p>
                </div>

                <div>
                    <h3 class="text-sm font-semibold text-primary-600 uppercase tracking-wider">Estado</h3>
                    <p class="mt-1">
                        <span class="px-3 py-1 text-sm font-semibold rounded-full <?= $servicio['activo'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                            <?= $servicio['activo'] ? '✅ Activo' : '❌ Inactivo' ?>
                        </span>
                    </p>
                </div>

                <div>
                    <h3 class="text-sm font-semibold text-primary-600 uppercase tracking-wider">Fecha de creación</h3>
                    <p class="text-lg font-bold text-slate-800 mt-1"><?= date('d/m/Y H:i', strtotime($servicio['created_at'])) ?></p>
                </div>
            </div>

            <!-- Subsecciones -->
            <?php if (!empty($servicio['subsecciones'])): ?>
                <div>
                    <h3 class="text-sm font-semibold text-primary-600 uppercase tracking-wider mb-3">
                        <i class="fas fa-list-ul mr-2"></i> Subsecciones
                    </h3>
                    <div class="grid md:grid-cols-2 gap-4">
                        <?php foreach ($servicio['subsecciones'] as $sub): ?>
                            <div class="bg-primary-50/50 rounded-lg p-4 border border-primary-100">
                                <h4 class="font-bold text-slate-800 mb-2"><?= e($sub['subtitulo']) ?></h4>
                                <p class="text-sm text-slate-600"><?= nl2br(e($sub['descripcion'])) ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Ruta de la imagen -->
            <?php if (!empty($servicio['imagen'])): ?>
                <div>
                    <h3 class="text-sm font-semibold text-primary-600 uppercase tracking-wider">Ruta de la imagen</h3>
                    <p class="mt-1 text-sm text-slate-600 bg-primary-50/50 p-2 rounded-lg font-mono border border-primary-100">
                        <?= e($servicio['imagen']) ?>
                    </p>
                </div>
            <?php endif; ?>

            <!-- Acciones rápidas -->
            <div class="flex flex-wrap gap-3 pt-4 border-t border-slate-200">
                <a href="<?= url('admin/servicios/editar') ?>?id=<?= $servicio['id'] ?>" 
                   class="bg-primary-500 hover:bg-primary-600 text-white px-4 py-2 rounded-lg text-sm font-semibold transition">
                    <i class="fas fa-edit mr-1"></i> Editar
                </a>
                <a href="<?= url('admin/servicios/toggle') ?>?id=<?= $servicio['id'] ?>" 
                   class="<?= $servicio['activo'] ? 'bg-yellow-500 hover:bg-yellow-600' : 'bg-green-500 hover:bg-green-600' ?> text-white px-4 py-2 rounded-lg text-sm font-semibold transition">
                    <i class="fas fa-<?= $servicio['activo'] ? 'times' : 'check' ?> mr-1"></i>
                    <?= $servicio['activo'] ? 'Desactivar' : 'Activar' ?>
                </a>
                <a href="<?= url('admin/servicios/eliminar') ?>?id=<?= $servicio['id'] ?>" 
                   onclick="return confirm('¿Estás seguro de eliminar este servicio?')"
                   class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-semibold transition">
                    <i class="fas fa-trash mr-1"></i> Eliminar
                </a>
            </div>
        </div>
    </div>
</div>