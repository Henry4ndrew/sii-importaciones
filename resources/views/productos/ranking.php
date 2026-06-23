<div class="w-full mb-5">
    <h1 class="text-2xl font-extrabold text-primary-50 mb-1">Productos Más Votados <span class="text-sky">(10+ votos)</span></h1>
    <p class="text-primary-50 text-sm">Los productos favoritos de la comunidad, ordenados por número de votos.</p>
</div>

<?php if (empty($productos)): ?>
    <div class="bg-primary-700/90 rounded-2xl p-6 border border-white/20 shadow-2xl text-center">
        <p class="text-5xl mb-3">🏆</p>
        <p class="font-semibold text-white">Ningún producto ha alcanzado los 10 votos todavía.</p>
        <p class="text-sm text-primary-50 mt-1">Vota por tus favoritos en la sección de productos recientes.</p>
    </div>
<?php else: ?>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
        <?php foreach ($productos as $producto): ?>
            <?php $vista = 'ranking'; include __DIR__ . '/_tarjeta.php'; ?>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
