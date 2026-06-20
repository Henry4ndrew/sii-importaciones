<h1 class="text-2xl font-extrabold text-slate-800 mb-1">Productos Más Votados <span class="text-amber-500">(10+ votos)</span></h1>
<p class="text-slate-500 text-sm mb-6">Los productos favoritos de la comunidad, ordenados por número de votos.</p>

<?php if (empty($productos)): ?>
    <div class="bg-white rounded-xl shadow p-10 text-center text-slate-500">
        <p class="text-5xl mb-3">🏆</p>
        <p class="font-semibold">Ningún producto ha alcanzado los 10 votos todavía.</p>
        <p class="text-sm mt-1">Vota por tus favoritos en la sección de productos recientes.</p>
    </div>
<?php else: ?>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
        <?php foreach ($productos as $producto): ?>
            <?php $vista = 'ranking'; include __DIR__ . '/_tarjeta.php'; ?>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
