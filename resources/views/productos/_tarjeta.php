<?php /* Tarjeta de producto. Espera: $producto, $votados, $vista ('/' o 'ranking') */ ?>
<div class="bg-white rounded-xl shadow hover:shadow-lg transition overflow-hidden flex flex-col">
    <div class="h-48 bg-slate-200 flex items-center justify-center overflow-hidden relative">
        <?php if (!empty($producto['oferta'])): ?>
            <span class="absolute top-2 right-2 bg-red-600 text-white text-xs font-black rounded-full px-3 py-1 shadow">
                OFERTA <?= e($producto['oferta']) ?>
            </span>
        <?php endif; ?>
        <?php if (!empty($producto['imagen'])): ?>
            <img src="<?= e($producto['imagen']) ?>" alt="<?= e($producto['nombre']) ?>" class="w-full h-full object-cover foto-principal">
        <?php else: ?>
            <span class="animate-pulse text-center text-slate-400">
                <span class="text-5xl block">📦</span>
                <span class="text-xs font-semibold">Obteniendo foto y precio…</span>
            </span>
        <?php endif; ?>
    </div>

    <?php $galeria = !empty($producto['imagenes']) ? (json_decode($producto['imagenes'], true) ?: []) : []; ?>
    <?php if (count($galeria) > 1): ?>
        <!-- Galería: clic en una miniatura cambia la foto grande de la tarjeta -->
        <div class="flex gap-1.5 px-3 pt-2 overflow-x-auto">
            <?php foreach (array_slice($galeria, 0, 5) as $img): ?>
                <img src="<?= e($img) ?>" alt="" loading="lazy"
                     class="w-11 h-11 object-cover rounded-md border border-slate-200 cursor-pointer hover:border-amber-500 shrink-0"
                     onclick="this.closest('.flex-col').querySelector('.foto-principal').src = this.src">
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <div class="p-4 flex flex-col flex-1">
        <h3 class="font-bold text-slate-800 leading-snug line-clamp-2"><?= e($producto['nombre']) ?></h3>

        <?php if (!empty($producto['precio'])): ?>
            <p class="mt-1">
                <span class="text-amber-600 font-extrabold text-lg"><?= e($producto['precio']) ?></span>
                <?php if (!empty($producto['precio_original'])): ?>
                    <span class="text-slate-400 text-sm line-through ml-1"><?= e($producto['precio_original']) ?></span>
                <?php endif; ?>
            </p>
        <?php endif; ?>

        <?php if (!empty($producto['pedido_minimo'])): ?>
            <p class="text-xs text-slate-600 mt-0.5">Pedido mínimo: <span class="font-semibold"><?= e($producto['pedido_minimo']) ?></span></p>
        <?php endif; ?>

        <p class="text-xs text-slate-500 mt-1">
            Publicado por <?= e($producto['publicado_por']) ?> · <?= e(date('d/m/Y', strtotime($producto['created_at']))) ?>
        </p>

        <a href="<?= e($producto['url']) ?>" target="_blank" rel="noopener" class="text-blue-600 text-sm hover:underline mt-1">Ver en Alibaba ↗</a>

        <div class="flex items-center justify-between mt-auto pt-4">
            <span class="inline-flex items-center gap-1 bg-slate-100 rounded-full px-3 py-1 text-sm font-bold text-slate-700">
                ▲ <?= (int) $producto['total_votos'] ?> votos
            </span>

            <?php if (in_array($producto['id'], $votados)): ?>
                <span class="px-4 py-2 rounded-lg bg-green-100 text-green-700 text-sm font-bold">✓ Ya votaste</span>
            <?php else: ?>
                <form method="POST" action="<?= url('votar') ?>">
                    <input type="hidden" name="producto_id" value="<?= (int) $producto['id'] ?>">
                    <input type="hidden" name="volver_a" value="<?= e($vista ?? '/') ?>">
                    <button class="px-4 py-2 rounded-lg bg-slate-900 text-white text-sm font-bold hover:bg-amber-500 hover:text-slate-900 transition">
                        Votar ▲
                    </button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</div>
