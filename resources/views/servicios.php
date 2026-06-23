<?php
// Mapear campos para el diseño
$servicios = $servicios ?? [];
?>

<!-- Hero Section - Full Width -->
<section class="relative w-screen left-1/2 right-1/2 -ml-[50vw] -mr-[50vw] min-h-[60vh] flex items-center overflow-hidden -mt-6">
    <!-- Background -->
    <div class="absolute inset-0 z-0 bg-gradient-to-br from-primary-900 via-primary-800 to-primary-900">
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_30%_50%,rgba(0,200,215,0.08),transparent_60%)]"></div>
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_70%_50%,rgba(47,90,138,0.1),transparent_60%)]"></div>
    </div>

    <!-- Animated Particles -->
    <div class="absolute inset-0 z-0 overflow-hidden opacity-30">
        <div class="absolute top-20 left-10 w-64 h-64 bg-[#00c8d7]/5 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute bottom-20 right-10 w-96 h-96 bg-primary-500/5 rounded-full blur-3xl animate-pulse delay-1000"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-[#00c8d7]/3 rounded-full blur-3xl animate-pulse delay-700"></div>
    </div>

    <!-- Content -->
    <div class="relative z-10 max-w-5xl mx-auto px-4 py-16 md:py-20 text-center">
        <div class="inline-flex items-center gap-2 bg-[#00c8d7]/10 border border-[#00c8d7]/30 rounded-full px-5 py-1.5 mb-6">
            <span class="relative flex h-2 w-2">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#00c8d7] opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2 w-2 bg-[#00c8d7]"></span>
            </span>
            <span class="text-[10px] font-black uppercase tracking-[0.2em] text-[#00c8d7]">Nuestros Servicios</span>
        </div>
        <h1 class="text-4xl md:text-6xl lg:text-7xl font-black text-white leading-[1.05] mb-6">
            Soluciones Integrales<br>
            <span class="text-[#00c8d7]">para tu Negocio</span>
        </h1>
        <p class="text-primary-200/90 text-lg md:text-xl max-w-3xl mx-auto leading-relaxed">
            Ofrecemos servicios especializados para importaciones desde China, con el respaldo de nuestra experiencia y compromiso.
        </p>
        <div class="flex flex-wrap items-center justify-center gap-4 mt-8">
            <div class="flex items-center gap-2 text-primary-300 text-sm">
                <span class="w-16 h-px bg-gradient-to-r from-transparent to-primary-400"></span>
                <span class="text-[10px] font-medium uppercase tracking-[0.2em]">Descubre nuestros servicios</span>
                <span class="w-16 h-px bg-gradient-to-l from-transparent to-primary-400"></span>
            </div>
        </div>
    </div>
</section>

<!-- Servicios -->
<?php if (empty($servicios)): ?>
<div class="w-screen left-1/2 right-1/2 -ml-[50vw] -mr-[50vw] relative overflow-hidden py-20">
    <div class="relative z-10 max-w-4xl mx-auto px-4 text-center">
        <div class="bg-white/5 backdrop-blur-lg rounded-3xl border border-white/10 p-12">
            <div class="w-20 h-20 rounded-2xl bg-[#00c8d7]/10 border border-[#00c8d7]/20 flex items-center justify-center text-[#00c8d7] text-3xl mx-auto mb-4">
                <i class="fas fa-concierge-bell"></i>
            </div>
            <h3 class="text-2xl font-bold text-white mb-2">No hay servicios disponibles</h3>
            <p class="text-primary-300">Pronto estaremos agregando nuevos servicios para ti.</p>
        </div>
    </div>
</div>
<?php else: ?>
    <?php foreach ($servicios as $index => $servicio): 
        $imgUrl = !empty($servicio['imagen']) ? url('public/img/' . $servicio['imagen']) : 'https://images.unsplash.com/photo-1450101499163-c8848c66ca85?w=800';
        $isEven = $index % 2 === 0;
    ?>
    <!-- Servicio <?php echo $index + 1; ?> -->
    <div class="w-screen left-1/2 right-1/2 -ml-[50vw] -mr-[50vw] relative overflow-hidden">
        
        <!-- Imagen de fondo difuminada -->
        <div class="absolute inset-0 z-0">
            <img src="<?php echo htmlspecialchars($imgUrl); ?>" alt="<?php echo e($servicio['titulo']); ?>" class="w-full h-full object-cover blur-sm scale-105">
            <div class="absolute inset-0 <?php echo $isEven ? 'bg-gradient-to-r' : 'bg-gradient-to-l'; ?> from-primary-900/95 via-primary-900/80 to-primary-900/70"></div>
        </div>

        <!-- Efectos decorativos -->
        <div class="absolute inset-0 z-0 overflow-hidden opacity-30">
            <div class="absolute top-20 <?php echo $isEven ? 'left-10' : 'right-10'; ?> w-64 h-64 bg-[#00c8d7]/5 rounded-full blur-3xl animate-pulse"></div>
            <div class="absolute bottom-20 <?php echo $isEven ? 'right-10' : 'left-10'; ?> w-96 h-96 bg-primary-500/5 rounded-full blur-3xl animate-pulse delay-1000"></div>
        </div>

        <!-- Contenido -->
        <div class="relative z-10 max-w-6xl mx-auto px-4 py-16 md:py-20">
            <div class="grid md:grid-cols-2 gap-10 items-center">
                
                <!-- Texto -->
                <div class="<?php echo $isEven ? 'order-2 md:order-1' : 'order-2 md:order-2'; ?>">
                    <div class="inline-flex items-center gap-2 bg-[#00c8d7]/10 border border-[#00c8d7]/30 rounded-full px-4 py-1.5 mb-4">
                        <span class="text-[10px] font-black uppercase tracking-[0.2em] text-[#00c8d7]">Servicio <?php echo $index + 1; ?></span>
                    </div>
                    <h2 class="text-3xl md:text-4xl font-black text-white mb-4">
                        <?php echo e($servicio['titulo']); ?>
                    </h2>
                    
                    <!-- Subsecciones -->
                    <?php if (!empty($servicio['subsecciones'])): ?>
                        <div class="space-y-3 mt-4">
                            <?php foreach ($servicio['subsecciones'] as $sub): ?>
                            <div class="flex items-start gap-3 p-3 rounded-xl bg-white/5 backdrop-blur-sm border border-white/5 hover:border-[#00c8d7]/30 transition-all duration-300 group hover:bg-white/10">
                                <div class="w-8 h-8 rounded-full bg-[#00c8d7]/20 flex items-center justify-center flex-shrink-0 mt-0.5 group-hover:bg-[#00c8d7]/30 transition-colors duration-300">
                                    <i class="fas fa-check text-[#00c8d7] text-xs"></i>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-sky group-hover:text-[#00c8d7] transition-colors duration-300">
                                        <?php echo e($sub['subtitulo']); ?>
                                    </h4>
                                    <p class="text-sm text-primary-50 leading-relaxed">
                                        <?php echo nl2br(e($sub['descripcion'])); ?>
                                    </p>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <p class="text-primary-300 text-sm italic">Sin subsecciones disponibles</p>
                    <?php endif; ?>
                    
                    <?php if (!empty($servicio['descripcion'])): ?>
                    <p class="text-primary-200/90 text-base md:text-lg leading-relaxed mt-4">
                        <?php echo nl2br(e($servicio['descripcion'])); ?>
                    </p>
                    <?php endif; ?>
                </div>

                <!-- Imagen lateral -->
                <div class="<?php echo $isEven ? 'order-1 md:order-2' : 'order-1 md:order-1'; ?>">
                    <div class="relative rounded-3xl overflow-hidden shadow-2xl shadow-[#00c8d7]/5 group">
                        <img src="<?php echo htmlspecialchars($imgUrl); ?>" alt="<?php echo e($servicio['titulo']); ?>" class="w-full h-72 md:h-96 object-cover group-hover:scale-105 transition-transform duration-700">
                        <div class="absolute inset-0 bg-gradient-to-t from-primary-900/60 via-transparent to-transparent"></div>
                        <div class="absolute bottom-4 left-4 right-4">
                            <div class="inline-flex items-center gap-2 bg-primary-900/80 backdrop-blur-sm rounded-full px-4 py-1.5 border border-white/10">
                                <span class="relative flex h-2 w-2">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#00c8d7] opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2 w-2 bg-[#00c8d7]"></span>
                                </span>
                                <span class="text-[10px] text-primary-200 font-medium"><?php echo e($servicio['titulo']); ?></span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <?php endforeach; ?>
<?php endif; ?>

<!-- CTA Final -->
<div class="w-screen left-1/2 right-1/2 -ml-[50vw] -mr-[50vw] relative overflow-hidden">
    <div class="relative z-10 max-w-4xl mx-auto px-4 py-16">
        <div class="relative bg-gradient-to-r from-primary-800/80 via-primary-700/50 to-primary-800/80 backdrop-blur-sm rounded-3xl border border-white/10 p-10 md:p-14 text-center overflow-hidden">
            <div class="absolute -top-20 -right-20 w-64 h-64 bg-[#00c8d7]/5 rounded-full blur-3xl"></div>
            <div class="absolute -bottom-20 -left-20 w-64 h-64 bg-primary-500/5 rounded-full blur-3xl"></div>
            
            <div class="relative z-10">
                <h3 class="text-2xl md:text-3xl font-bold text-white mb-4">
                    ¿Necesitas un servicio <span class="text-[#00c8d7]">personalizado</span>?
                </h3>
                <p class="text-primary-200 max-w-2xl mx-auto mb-6">
                    Contáctanos y te asesoraremos en todo el proceso de importación desde China.
                </p>
                <a href="<?= url('contactos') ?>" 
                   class="inline-flex items-center gap-2 font-bold px-8 py-3.5 rounded-xl text-white bg-gradient-to-r from-[#00c8d7] to-primary-500 hover:from-primary-900 hover:to-[#00c8d7] transition-all duration-300 ease-in-out shadow-lg shadow-[#00c8d7]/20 hover:shadow-xl hover:shadow-[#00c8d7]/30 hover:-translate-y-1">
                    <i class="fa-solid fa-paper-plane"></i>
                    Contáctanos
                    <i class="fa-solid fa-arrow-right text-sm group-hover:translate-x-1 transition-transform"></i>
                </a>
            </div>
        </div>
    </div>
</div>