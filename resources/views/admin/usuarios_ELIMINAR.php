<div class="bg-white rounded-xl shadow overflow-hidden">
    <div class="px-6 py-4 border-b border-slate-200 flex items-center justify-between">
        <h2 class="text-xl font-bold text-slate-800">
            <i class="fas fa-users mr-2 text-red-600"></i> Lista de Usuarios
        </h2>
        <span class="text-sm text-slate-500">Total: <?= count($usuarios) ?> usuarios</span>
    </div>
    
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Fecha de Registro</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-slate-500 uppercase tracking-wider">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                <?php if (empty($usuarios)): ?>
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-slate-500">No hay usuarios registrados</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($usuarios as $usuario): ?>
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-6 py-4 text-sm font-medium text-slate-900">#<?= $usuario['id'] ?></td>
                            <td class="px-6 py-4 text-sm text-slate-600"><?= e($usuario['email']) ?></td>
                            <td class="px-6 py-4 text-sm text-slate-500"><?= date('d/m/Y H:i', strtotime($usuario['created_at'])) ?></td>
                            <td class="px-6 py-4 text-sm">
                                <button onclick="if(confirm('¿Eliminar este usuario?')) { alert('Función en desarrollo'); }" class="text-red-600 hover:text-red-800">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>