<div class="bg-white rounded-xl shadow overflow-hidden">
    <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between flex-wrap gap-3">
        <h2 class="text-xl font-bold text-slate-800">
            <i class="fas fa-user-shield mr-2 text-red-600"></i> Lista de Administradores
        </h2>
        <div class="flex items-center gap-3">
            <span class="text-sm text-slate-500">Total: <?= count($administradores) ?> administradores</span>
            <a href="<?= url('admin/administradores/crear') ?>" 
               class="bg-primary-500 hover:bg-primary-600 text-white px-4 py-2 rounded-lg text-sm font-semibold transition">
                <i class="fas fa-plus mr-1"></i> Nuevo Administrador
            </a>
        </div>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Nombre</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Fecha de Registro</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                <?php if (empty($administradores)): ?>
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-slate-500">No hay administradores registrados</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($administradores as $admin): ?>
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-6 py-4 text-sm font-medium text-slate-900">#<?= $admin['id'] ?></td>
                            <td class="px-6 py-4 text-sm text-slate-600 font-medium">
                                <?= e($admin['nombre']) ?>
                                <?php if ($admin['id'] == $_SESSION['administrador']['id']): ?>
                                    <span class="ml-2 text-xs bg-primary-100 text-primary-700 px-2 py-0.5 rounded-full">Tú</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600"><?= e($admin['email']) ?></td>
                            <td class="px-6 py-4 text-sm text-slate-500"><?= date('d/m/Y H:i', strtotime($admin['created_at'])) ?></td>
                            <td class="px-6 py-4 text-sm">
                                <div class="flex items-center gap-2">
                                    <a href="<?= url('admin/administradores/editar') ?>?id=<?= $admin['id'] ?>" 
                                       class="text-blue-600 hover:text-blue-800 transition">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <?php if ($admin['id'] != $_SESSION['administrador']['id']): ?>
                                        <a href="<?= url('admin/administradores/eliminar') ?>?id=<?= $admin['id'] ?>" 
                                           onclick="return confirm('¿Estás seguro de eliminar este administrador?')"
                                           class="text-red-600 hover:text-red-800 transition">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>