<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-extrabold text-slate-800">Productos Recientes</h1>
    <?php if (auth()): ?>
        <a href="<?= url('productos/crear') ?>" class="px-4 py-2 rounded-lg bg-amber-500 text-slate-900 font-bold hover:bg-amber-400">Publicar Nuevo Producto</a>
    <?php endif; ?>
</div>

<?php if (empty($productos)): ?>
    <div class="bg-white rounded-xl shadow p-10 text-center text-slate-500">
        <p class="text-5xl mb-3">🛒</p>
        <p class="font-semibold">Aún no hay productos publicados.</p>
        <p class="text-sm mt-1">¡Sé el primero en compartir un producto de Alibaba!</p>
    </div>
<?php else: ?>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
        <?php foreach ($productos as $producto): ?>
            <?php $vista = '/'; include __DIR__ . '/_tarjeta.php'; ?>
        <?php endforeach; ?>
    </div>

    <?php $hayIncompletos = array_filter($productos, static fn ($p) => empty($p['imagen'])); ?>
    <?php if ($hayIncompletos): ?>
        <!-- Hay productos completándose en segundo plano: refrescar para mostrar sus detalles -->
        <script>setTimeout(function () { location.reload(); }, 7000);</script>
    <?php endif; ?>
<?php endif; ?>
