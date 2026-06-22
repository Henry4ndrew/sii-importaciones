<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-xl shadow overflow-hidden border-t-4 border-primary-500">
        <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
            <h2 class="text-xl font-bold text-slate-800">
                <i class="fas fa-eye mr-2 text-primary-500"></i> Detalle de Publicación
            </h2>
            <div class="flex items-center gap-2">
                <a href="<?= url('admin/publicaciones') ?>" 
                   class="bg-slate-200 hover:bg-slate-300 text-slate-700 px-4 py-2 rounded-lg text-sm font-semibold transition">
                    <i class="fas fa-arrow-left mr-1"></i> Volver
                </a>
            </div>
        </div>

        <div class="p-6 space-y-6">
            <!-- Información del producto -->
            <div class="grid md:grid-cols-2 gap-6">
                <!-- Imagen -->
                <div>
                    <?php if (!empty($publicacion['imagen'])): ?>
                        <img src="<?= $publicacion['imagen'] ?>" 
                             alt="<?= e($publicacion['nombre']) ?>" 
                             class="w-full max-h-[300px] object-cover rounded-lg shadow-lg border-2 border-primary-100">
                    <?php else: ?>
                        <div class="w-full h-[200px] bg-slate-200 rounded-lg flex items-center justify-center">
                            <i class="fas fa-image text-4xl text-slate-400"></i>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Datos -->
                <div class="space-y-4">
                    <div>
                        <h3 class="text-sm font-semibold text-primary-600 uppercase tracking-wider">ID</h3>
                        <p class="text-lg font-bold text-slate-800">#<?= $publicacion['id'] ?></p>
                    </div>

                    <div>
                        <h3 class="text-sm font-semibold text-primary-600 uppercase tracking-wider">Nombre del Producto</h3>
                        <p class="text-lg font-bold text-slate-800"><?= e($publicacion['nombre']) ?></p>
                    </div>

                    <div>
                        <h3 class="text-sm font-semibold text-primary-600 uppercase tracking-wider">Publicado por</h3>
                        <p class="text-lg font-bold text-slate-800"><?= e($publicacion['publicado_por']) ?></p>
                    </div>

                    <div>
                        <h3 class="text-sm font-semibold text-primary-600 uppercase tracking-wider">Total de Votos</h3>
                        <p class="text-2xl font-bold text-amber-600">
                            <i class="fas fa-star mr-2"></i>
                            <?= $publicacion['total_votos'] ?? 0 ?>
                        </p>
                    </div>

                    <div>
                        <h3 class="text-sm font-semibold text-primary-600 uppercase tracking-wider">Fecha de Publicación</h3>
                        <p class="text-lg font-bold text-slate-800"><?= date('d/m/Y H:i:s', strtotime($publicacion['created_at'])) ?></p>
                    </div>
                </div>
            </div>

            <!-- URL del producto -->
            <div>
                <h3 class="text-sm font-semibold text-primary-600 uppercase tracking-wider">URL del Producto</h3>
                <div class="mt-1 p-3 bg-primary-50/50 rounded-lg border border-primary-100">
                    <a href="<?= e($publicacion['url']) ?>" target="_blank" class="text-primary-600 hover:text-primary-800 break-all">
                        <?= e($publicacion['url']) ?>
                        <i class="fas fa-external-link-alt ml-1 text-xs"></i>
                    </a>
                </div>
            </div>

            <!-- Detalles del producto -->
            <?php if (!empty($publicacion['precio']) || !empty($publicacion['pedido_minimo'])): ?>
                <div>
                    <h3 class="text-sm font-semibold text-primary-600 uppercase tracking-wider mb-3">Detalles del Producto</h3>
                    <div class="grid md:grid-cols-2 gap-4">
                        <?php if (!empty($publicacion['precio'])): ?>
                            <div class="bg-primary-50/50 rounded-lg p-3 border border-primary-100">
                                <span class="text-xs text-slate-500">Precio</span>
                                <p class="font-bold text-amber-600"><?= e($publicacion['precio']) ?></p>
                                <?php if (!empty($publicacion['precio_original'])): ?>
                                    <span class="text-sm text-slate-400 line-through"><?= e($publicacion['precio_original']) ?></span>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($publicacion['pedido_minimo'])): ?>
                            <div class="bg-primary-50/50 rounded-lg p-3 border border-primary-100">
                                <span class="text-xs text-slate-500">Pedido Mínimo</span>
                                <p class="font-bold text-slate-800"><?= e($publicacion['pedido_minimo']) ?></p>
                            </div>
                        <?php endif; ?>
                        
                        <?php if (!empty($publicacion['oferta'])): ?>
                            <div class="bg-primary-50/50 rounded-lg p-3 border border-primary-100">
                                <span class="text-xs text-slate-500">Oferta</span>
                                <p class="font-bold text-green-600"><?= e($publicacion['oferta']) ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Lista de votantes -->
            <div>
                <div class="flex items-center justify-between mb-3">
                    <h3 class="text-sm font-semibold text-primary-600 uppercase tracking-wider">
                        <i class="fas fa-users mr-2"></i> Usuarios que votaron
                    </h3>
                    <span class="text-sm text-slate-500">Total: <?= count($votantes) ?> votos</span>
                </div>
                
                <?php if (empty($votantes)): ?>
                    <p class="text-slate-400 text-sm">No hay votos registrados para este producto.</p>
                <?php else: ?>
                    <div class="bg-primary-50/50 rounded-lg border border-primary-100 overflow-hidden">
                        <table class="w-full">
                            <thead class="bg-primary-100/50">
                                <tr>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-primary-700">Usuario</th>
                                    <th class="px-4 py-2 text-left text-xs font-medium text-primary-700">Fecha de voto</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-primary-100">
                                <?php foreach ($votantes as $votante): ?>
                                    <tr class="hover:bg-primary-50/50">
                                        <td class="px-4 py-2 text-sm text-slate-700"><?= e($votante['email']) ?></td>
                                        <td class="px-4 py-2 text-sm text-slate-500"><?= date('d/m/Y H:i', strtotime($votante['fecha_voto'])) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Acciones -->
            <div class="flex flex-wrap gap-3 pt-4 border-t border-slate-200">
                <a href="<?= url('admin/publicaciones/eliminar') ?>?id=<?= $publicacion['id'] ?>" 
                   onclick="return confirm('¿Estás seguro de eliminar esta publicación? Esta acción eliminará también todos los votos asociados.')"
                   class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-semibold transition">
                    <i class="fas fa-trash mr-1"></i> Eliminar Publicación
                </a>
                <a href="<?= url('admin/publicaciones') ?>" 
                   class="bg-slate-200 hover:bg-slate-300 text-slate-700 px-4 py-2 rounded-lg text-sm font-semibold transition">
                    <i class="fas fa-arrow-left mr-1"></i> Volver al listado
                </a>
            </div>
        </div>
    </div>
</div>