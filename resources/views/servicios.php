<div class="max-w-6xl mx-auto">
    <!-- Hero Header -->
    <div class="text-center mb-12">
        <span class="inline-block bg-primary-100 text-primary-700 px-4 py-1.5 rounded-full text-sm font-semibold tracking-wide uppercase border border-primary-200">
            <i class="fas fa-concierge-bell mr-2"></i> Nuestros Servicios
        </span>
        <h1 class="text-4xl md:text-5xl font-extrabold mt-4 mb-3" style="color: #0A1626;">
            Soluciones Integrales para tu Negocio
        </h1>
        <p class="text-slate-600 text-lg max-w-2xl mx-auto">
            Ofrecemos servicios especializados para importaciones desde China, con el respaldo de nuestra experiencia y compromiso.
        </p>
    </div>

    <?php if (empty($servicios)): ?>
        <div class="bg-white rounded-xl shadow p-12 text-center border border-slate-200">
            <i class="fas fa-concierge-bell text-5xl text-slate-300 mb-4"></i>
            <h3 class="text-xl font-semibold text-slate-600">No hay servicios disponibles</h3>
            <p class="text-slate-400">Pronto estaremos agregando nuevos servicios para ti.</p>
        </div>
    <?php else: ?>
        <!-- Grid de servicios -->
        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($servicios as $servicio): 
                $imgUrl = !empty($servicio['imagen']) ? url('public/img/' . $servicio['imagen']) : 'https://images.unsplash.com/photo-1450101499163-c8848c66ca85?w=800';
            ?>
                <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 hover:-translate-y-2 border border-slate-100 group">
                    <!-- Imagen del servicio -->
                    <div class="relative h-52 overflow-hidden">
                        <img src="<?= $imgUrl ?>" 
                             alt="<?= e($servicio['titulo']) ?>" 
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                        <div class="absolute bottom-4 left-4 right-4">
                            <h2 class="text-xl font-bold text-white drop-shadow-lg"><?= e($servicio['titulo']) ?></h2>
                        </div>
                        <?php if (!empty($servicio['subsecciones'])): ?>
                            <span class="absolute top-3 right-3 bg-primary-500/90 text-white text-xs font-semibold px-3 py-1 rounded-full backdrop-blur-sm">
                                <?= count($servicio['subsecciones']) ?> subsecciones
                            </span>
                        <?php endif; ?>
                    </div>

                    <!-- Contenido del servicio -->
                    <div class="p-6">
                        <!-- Subsecciones -->
                        <?php if (!empty($servicio['subsecciones'])): ?>
                            <div class="space-y-3">
                                <?php foreach ($servicio['subsecciones'] as $sub): ?>
                                    <div class="flex items-start gap-3 p-3 rounded-lg hover:bg-primary-50 transition-colors border border-transparent hover:border-primary-100">
                                        <div class="w-8 h-8 rounded-full bg-primary-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                                            <i class="fas fa-check text-primary-600 text-sm"></i>
                                        </div>
                                        <div>
                                            <h4 class="font-semibold text-slate-800"><?= e($sub['subtitulo']) ?></h4>
                                            <p class="text-sm text-slate-500 leading-relaxed"><?= nl2br(e($sub['descripcion'])) ?></p>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p class="text-slate-400 text-sm italic">Sin subsecciones disponibles</p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <!-- CTA Final -->
    <div class="mt-16 text-center bg-primary-50 rounded-2xl p-8 md:p-12 border border-primary-100">
        <h2 class="text-2xl md:text-3xl font-bold text-primary-800 mb-3">¿Necesitas un servicio personalizado?</h2>
        <p class="text-slate-600 mb-6">Contáctanos y te asesoraremos en todo el proceso de importación desde China.</p>
        <a href="<?= url('contactos') ?>" 
           class="inline-block bg-primary-600 hover:bg-primary-700 text-white font-bold px-8 py-3 rounded-xl transition-all duration-200 hover:shadow-lg">
            <i class="fas fa-envelope mr-2"></i> Contáctanos
        </a>
    </div>
</div>

<style>
/* Animación de entrada */
.service-card {
    animation: fadeInUp 0.6s ease both;
}

.service-card:nth-child(1) { animation-delay: 0.1s; }
.service-card:nth-child(2) { animation-delay: 0.2s; }
.service-card:nth-child(3) { animation-delay: 0.3s; }
.service-card:nth-child(4) { animation-delay: 0.4s; }
.service-card:nth-child(5) { animation-delay: 0.5s; }
.service-card:nth-child(6) { animation-delay: 0.6s; }

@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>