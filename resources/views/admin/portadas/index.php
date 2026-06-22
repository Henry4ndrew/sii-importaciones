<div class="bg-white rounded-xl shadow overflow-hidden">
    <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between flex-wrap gap-3">
        <h2 class="text-xl font-bold text-slate-800">
            <i class="fas fa-images mr-2 text-primary-500"></i> Lista de Portadas
        </h2>
        <div class="flex items-center gap-3">
            <span class="text-sm text-slate-500">Total: <?= count($portadas) ?> portadas</span>
            <a href="<?= url('admin/portadas/crear') ?>" 
               class="bg-primary-500 hover:bg-primary-600 text-white px-4 py-2 rounded-lg text-sm font-semibold transition">
                <i class="fas fa-plus mr-1"></i> Nueva Portada
            </a>
        </div>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-primary-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Imagen</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Título</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Orden</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Estado</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                <?php if (empty($portadas)): ?>
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-slate-500">No hay portadas registradas</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($portadas as $portada): ?>
                        <tr class="hover:bg-primary-50/50 transition">
                            <td class="px-6 py-4 text-sm font-medium text-slate-900">#<?= $portada['id'] ?></td>
                            <td class="px-6 py-4">
                                <?php if (!empty($portada['ruta_imagen'])): ?>
                                    <img src="<?= url('public/img/' . $portada['ruta_imagen']) ?>" 
                                         alt="<?= e($portada['titulo']) ?>" 
                                         class="w-16 h-12 object-cover rounded-lg border border-slate-200">
                                <?php else: ?>
                                    <span class="text-slate-400 text-sm">Sin imagen</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600 font-medium"><?= e($portada['titulo']) ?></td>
                            <td class="px-6 py-4 text-sm text-slate-600 text-center"><?= $portada['orden'] ?></td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs font-semibold rounded-full <?= $portada['activo'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                    <?= $portada['activo'] ? 'Activo' : 'Inactivo' ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <div class="flex items-center gap-2">
                                    <a href="<?= url('admin/portadas/ver') ?>?id=<?= $portada['id'] ?>" 
                                       class="text-primary-500 hover:text-primary-700 transition"
                                       title="Ver detalle">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="<?= url('admin/portadas/toggle') ?>?id=<?= $portada['id'] ?>" 
                                       class="text-<?= $portada['activo'] ? 'green' : 'red' ?>-600 hover:text-<?= $portada['activo'] ? 'green' : 'red' ?>-800 transition"
                                       title="<?= $portada['activo'] ? 'Desactivar' : 'Activar' ?>">
                                        <i class="fas fa-<?= $portada['activo'] ? 'check-circle' : 'times-circle' ?>"></i>
                                    </a>
                                    <a href="<?= url('admin/portadas/editar') ?>?id=<?= $portada['id'] ?>" 
                                       class="text-primary-500 hover:text-primary-700 transition"
                                       title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="<?= url('admin/portadas/eliminar') ?>?id=<?= $portada['id'] ?>" 
                                       onclick="return confirm('¿Estás seguro de eliminar esta portada?')"
                                       class="text-red-600 hover:text-red-800 transition"
                                       title="Eliminar">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>