<!-- ============================================ -->
<!-- PORTADA DECORATIVA - FULL WIDTH              -->
<!-- ============================================ -->
<div class="relative w-screen left-1/2 right-1/2 -ml-[50vw] -mr-[50vw] h-56 md:h-72 overflow-hidden shadow-xl mb-6 bg-primary-900 -mt-10 z-0">
    <?php 
    // Intentar usar la imagen de producción
    $imgUrl = 'https://sii-importaciones.net/img/img_67aa0b186724a4.06741642.webp';
    
    // Fallback: si no carga, usar una imagen local
    $fallbackUrl = url('public/img/portada1.jpg');
    ?>
    <img src="<?php echo htmlspecialchars($imgUrl); ?>" 
         alt="Portada SII Importaciones" 
         class="w-full h-full object-cover"
         onerror="this.src='<?php echo htmlspecialchars($fallbackUrl); ?>'">
    
    <!-- Gradiente sutil de abajo hacia arriba -->
    <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/50 to-black/10"></div>
    <!-- Contenido -->
    <div class="absolute bottom-6 left-6 md:left-10 right-6 md:right-10 max-w-2xl">
        <h1 class="text-2xl md:text-4xl font-bold text-white mb-2 drop-shadow-lg">Productos de Alibaba sugeridos</h1>
        <p class="text-sm md:text-base text-gray-200 drop-shadow-md">Sii-importaciones - Tu voto cuenta, tu opinión nos interesa</p>
    </div>
</div>


<!-- ============================================ -->
<!-- LISTADO DE PRODUCTOS                         -->
<!-- ============================================ -->
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
        <script>setTimeout(function () { location.reload(); }, 7000);</script>
    <?php endif; ?>
<?php endif; ?>






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
