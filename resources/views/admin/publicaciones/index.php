<div class="bg-white rounded-xl shadow overflow-hidden">
    <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between flex-wrap gap-3">
        <h2 class="text-xl font-bold text-slate-800">
            <i class="fas fa-newspaper mr-2 text-primary-500"></i> Gestión de Publicaciones
        </h2>
        <span class="text-sm text-slate-500">Total: <?= count($publicaciones) ?> publicaciones</span>
    </div>

    <!-- Filtros -->
    <div class="px-6 py-4 bg-slate-50 border-b border-slate-200">
        <form method="GET" action="<?= url('admin/publicaciones') ?>" class="flex flex-wrap items-center gap-3">
            <!-- Búsqueda -->
            <div class="flex-1 min-w-[200px]">
                <input type="text" name="buscar" value="<?= e($busqueda ?? '') ?>" 
                       placeholder="Buscar por título o usuario..."
                       class="w-full border border-slate-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition">
            </div>

            <!-- Filtro por votos -->
            <div class="min-w-[150px]">
                <select name="votos" class="w-full border border-slate-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition">
                    <option value="todos" <?= ($filtroVotos ?? 'todos') === 'todos' ? 'selected' : '' ?>>Todos los votos</option>
                    <option value="0" <?= ($filtroVotos ?? '') === '0' ? 'selected' : '' ?>>Sin votos</option>
                    <option value="1-5" <?= ($filtroVotos ?? '') === '1-5' ? 'selected' : '' ?>>1 - 5 votos</option>
                    <option value="6-10" <?= ($filtroVotos ?? '') === '6-10' ? 'selected' : '' ?>>6 - 10 votos</option>
                    <option value="10+" <?= ($filtroVotos ?? '') === '10+' ? 'selected' : '' ?>>Más de 10 votos</option>
                </select>
            </div>

            <!-- Filtro por fecha -->
            <div class="min-w-[150px]">
                <select name="fecha" class="w-full border border-slate-300 rounded-lg px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition">
                    <option value="todos" <?= ($filtroFecha ?? 'todos') === 'todos' ? 'selected' : '' ?>>Todas las fechas</option>
                    <option value="hoy" <?= ($filtroFecha ?? '') === 'hoy' ? 'selected' : '' ?>>Hoy</option>
                    <option value="semana" <?= ($filtroFecha ?? '') === 'semana' ? 'selected' : '' ?>>Última semana</option>
                    <option value="mes" <?= ($filtroFecha ?? '') === 'mes' ? 'selected' : '' ?>>Último mes</option>
                </select>
            </div>

            <!-- Botones -->
            <button type="submit" class="bg-primary-500 hover:bg-primary-600 text-white px-4 py-2 rounded-lg text-sm font-semibold transition">
                <i class="fas fa-filter mr-1"></i> Filtrar
            </button>
            <a href="<?= url('admin/publicaciones') ?>" class="bg-slate-200 hover:bg-slate-300 text-slate-700 px-4 py-2 rounded-lg text-sm font-semibold transition">
                <i class="fas fa-undo mr-1"></i> Limpiar
            </a>
        </form>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-primary-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Producto</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Publicado por</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Votos</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Fecha</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-primary-700 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                <?php if (empty($publicaciones)): ?>
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-slate-500">No hay publicaciones que coincidan con los filtros</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($publicaciones as $pub): ?>
                        <tr class="hover:bg-primary-50/50 transition">
                            <td class="px-6 py-4 text-sm font-medium text-slate-900">#<?= $pub['id'] ?></td>
                            <td class="px-6 py-4 text-sm text-slate-600">
                                <div class="flex items-center gap-3">
                                    <?php if (!empty($pub['imagen'])): ?>
                                        <img src="<?= $pub['imagen'] ?>" 
                                             alt="<?= e($pub['nombre']) ?>" 
                                             class="w-10 h-10 object-cover rounded-lg border border-slate-200">
                                    <?php else: ?>
                                        <div class="w-10 h-10 bg-slate-200 rounded-lg flex items-center justify-center">
                                            <i class="fas fa-image text-slate-400"></i>
                                        </div>
                                    <?php endif; ?>
                                    <span class="font-medium truncate max-w-[200px]"><?= e($pub['nombre']) ?></span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600"><?= e($pub['publicado_por']) ?></td>
                            <td class="px-6 py-4 text-sm">
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold <?= ($pub['total_votos'] ?? 0) > 0 ? 'bg-amber-100 text-amber-800' : 'bg-slate-100 text-slate-500' ?>">
                                    <i class="fas fa-star text-xs"></i>
                                    <?= $pub['total_votos'] ?? 0 ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-500"><?= date('d/m/Y H:i', strtotime($pub['created_at'])) ?></td>
                            <td class="px-6 py-4 text-sm">
                                <div class="flex items-center gap-2">
                                    <a href="<?= url('admin/publicaciones/ver') ?>?id=<?= $pub['id'] ?>" 
                                       class="text-primary-500 hover:text-primary-700 transition"
                                       title="Ver detalle">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="<?= url('admin/publicaciones/eliminar') ?>?id=<?= $pub['id'] ?>" 
                                       onclick="return confirm('¿Estás seguro de eliminar esta publicación? Esta acción eliminará también todos los votos asociados.')"
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