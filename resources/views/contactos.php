<?php
$contactos = $contactos ?? [];
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
            <span class="text-[10px] font-black uppercase tracking-[0.2em] text-[#00c8d7]">Atención al Cliente</span>
        </div>
        <h1 class="text-4xl md:text-6xl lg:text-7xl font-black text-white leading-[1.05] mb-6">
            Nuestros <span class="text-[#00c8d7]">Contactos</span>
        </h1>
        <p class="text-primary-200/90 text-lg md:text-xl max-w-3xl mx-auto leading-relaxed">
            Encuentra la sucursal más cercana o contáctanos por teléfono y correo electrónico. Estamos listos para ayudarte.
        </p>
        <div class="flex flex-wrap items-center justify-center gap-4 mt-8">
            <div class="flex items-center gap-2 text-primary-300 text-sm">
                <span class="w-16 h-px bg-gradient-to-r from-transparent to-primary-400"></span>
                <span class="text-[10px] font-medium uppercase tracking-[0.2em]">Contáctanos</span>
                <span class="w-16 h-px bg-gradient-to-l from-transparent to-primary-400"></span>
            </div>
        </div>
    </div>


</section>

<!-- Contactos -->
<?php if (empty($contactos)): ?>
<div class="w-screen left-1/2 right-1/2 -ml-[50vw] -mr-[50vw] relative overflow-hidden py-20">
    <div class="relative z-10 max-w-4xl mx-auto px-4 text-center">
        <div class="bg-white/5 backdrop-blur-lg rounded-3xl border border-white/10 p-12">
            <div class="w-20 h-20 rounded-2xl bg-[#00c8d7]/10 border border-[#00c8d7]/20 flex items-center justify-center text-[#00c8d7] text-3xl mx-auto mb-4">
                <i class="fas fa-address-book"></i>
            </div>
            <h3 class="text-2xl font-bold text-white mb-2">No hay información de contacto registrada</h3>
            <p class="text-primary-300">Por favor, vuelve más tarde.</p>
        </div>
    </div>
</div>
<?php else: ?>
    <?php foreach ($contactos as $index => $contacto): 
        $imgUrl = !empty($contacto['imagen']) ? url('public/img/' . $contacto['imagen']) : 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?w=800';
        $isEven = $index % 2 === 0;
    ?>
    <!-- Contacto <?php echo $index + 1; ?> -->
    <div class="w-screen left-1/2 right-1/2 -ml-[50vw] -mr-[50vw] relative overflow-hidden">
        
        <!-- Imagen de fondo difuminada -->
        <div class="absolute inset-0 z-0">
            <img src="<?php echo htmlspecialchars($imgUrl); ?>" alt="<?php echo e($contacto['ciudad']); ?>" class="w-full h-full object-cover blur-sm scale-105">
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
                        <span class="text-[10px] font-black uppercase tracking-[0.2em] text-[#00c8d7]">Sucursal <?php echo $index + 1; ?></span>
                    </div>
                    <h2 class="text-3xl md:text-4xl font-black text-white mb-4 flex items-center gap-3">
                        <i class="fas fa-location-dot text-[#00c8d7]"></i>
                        <?php echo e($contacto['ciudad']); ?>
                    </h2>
                    
                    <!-- Detalles de contacto -->
                    <div class="space-y-4">
                        <?php if (!empty($contacto['telefono'])): ?>
                        <div class="flex items-center gap-4 group hover:bg-white/5 rounded-xl p-3 transition-colors duration-200">
                            <div class="w-12 h-12 rounded-xl bg-[#00c8d7]/10 border border-[#00c8d7]/20 flex items-center justify-center text-[#00c8d7] text-lg flex-shrink-0 group-hover:scale-110 transition-transform duration-300">
                                <i class="fas fa-phone"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-black uppercase tracking-widest text-sky">Teléfono</p>
                                <span class="text-primary-200 group-hover:text-white text-base font-medium transition-colors duration-200">
                                    <?php echo e($contacto['telefono']); ?>
                                </span>
                            </div>
                        </div>
                        <?php endif; ?>

                        <?php if (!empty($contacto['correo'])): ?>
                        <div class="flex items-center gap-4 group hover:bg-white/5 rounded-xl p-3 transition-colors duration-200">
                            <div class="w-12 h-12 rounded-xl bg-[#00c8d7]/10 border border-[#00c8d7]/20 flex items-center justify-center text-[#00c8d7] text-lg flex-shrink-0 group-hover:scale-110 transition-transform duration-300">
                                <i class="fas fa-envelope"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-black uppercase tracking-widest text-sky">Correo</p>
                                <a href="mailto:<?php echo e($contacto['correo']); ?>" class="text-primary-200 hover:text-white text-base font-medium transition-colors duration-200">
                                    <?php echo e($contacto['correo']); ?>
                                </a>
                            </div>
                        </div>
                        <?php endif; ?>

                        <?php if (!empty($contacto['horarios'])): ?>
                        <div class="flex items-start gap-4 group hover:bg-white/5 rounded-xl p-3 transition-colors duration-200">
                            <div class="w-12 h-12 rounded-xl bg-[#00c8d7]/10 border border-[#00c8d7]/20 flex items-center justify-center text-[#00c8d7] text-lg flex-shrink-0 group-hover:scale-110 transition-transform duration-300">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-black uppercase tracking-widest text-sky">Horarios</p>
                                <span class="text-primary-200 group-hover:text-white text-sm leading-relaxed transition-colors duration-200">
                                    <?php echo nl2br(e($contacto['horarios'])); ?>
                                </span>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- Badge de contacto -->
                    <div class="flex items-center gap-3 mt-6 pt-6 border-t border-white/5">
                        <div class="flex items-center gap-2 bg-[#00c8d7]/10 border border-[#00c8d7]/30 rounded-full px-4 py-1.5">
                            <span class="relative flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#00c8d7] opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-[#00c8d7]"></span>
                            </span>
                            <span class="text-[10px] text-[#00c8d7] font-medium uppercase tracking-wider">Disponible</span>
                        </div>
                    </div>
                </div>

                <!-- Imagen lateral -->
                <div class="<?php echo $isEven ? 'order-1 md:order-2' : 'order-1 md:order-1'; ?>">
                    <div class="relative rounded-3xl overflow-hidden shadow-2xl shadow-[#00c8d7]/5 group">
                        <img src="<?php echo htmlspecialchars($imgUrl); ?>" alt="<?php echo e($contacto['ciudad']); ?>" class="w-full h-72 md:h-96 object-cover group-hover:scale-105 transition-transform duration-700">
                        <div class="absolute inset-0 bg-gradient-to-t from-primary-900/60 via-transparent to-transparent"></div>
                        <div class="absolute bottom-4 left-4 right-4">
                            <div class="inline-flex items-center gap-2 bg-primary-900/80 backdrop-blur-sm rounded-full px-4 py-1.5 border border-white/10">
                                <span class="relative flex h-2 w-2">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#00c8d7] opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2 w-2 bg-[#00c8d7]"></span>
                                </span>
                                <span class="text-[10px] text-primary-200 font-medium"><?php echo e($contacto['ciudad']); ?></span>
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
                    ¿Necesitas <span class="text-[#00c8d7]">asesoría</span> personalizada?
                </h3>
                <p class="text-primary-200 max-w-2xl mx-auto mb-6">
                    Estamos disponibles para atender todas tus consultas y brindarte la mejor solución para tus importaciones.
                </p>
                <div class="flex flex-wrap items-center justify-center gap-4">
                    <?php if (!empty($contactos) && !empty($contactos[0]['telefono'])): ?>
                    <a href="tel:<?= preg_replace('/[^0-9]/', '', $contactos[0]['telefono']) ?>" 
                       class="inline-flex items-center gap-2 font-bold px-8 py-3.5 rounded-xl text-white bg-gradient-to-r from-[#00c8d7] to-primary-500 hover:from-primary-900 hover:to-[#00c8d7] transition-all duration-300 ease-in-out shadow-lg shadow-[#00c8d7]/20 hover:shadow-xl hover:shadow-[#00c8d7]/30 hover:-translate-y-1">
                        <i class="fa-solid fa-phone"></i>
                        Llamar ahora
                    </a>
                    <?php endif; ?>
                    <a href="mailto:<?= !empty($contactos) && !empty($contactos[0]['correo']) ? e($contactos[0]['correo']) : 'contacto@willsimport.com' ?>" 
                       class="inline-flex items-center gap-2 font-bold px-8 py-3.5 rounded-xl text-white border border-white/20 hover:bg-white/10 transition-all duration-300 hover:-translate-y-1">
                        <i class="fa-solid fa-envelope"></i>
                        Enviar email
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>