<?php
// Mapear campos para el diseño
$encabezado_titulo = $conocenos['encabezado_titulo'] ?? 'Sobre Nosotros';
$encabezado_descripcion = $conocenos['encabezado_descripcion'] ?? '';
$encabezado_imagen = !empty($conocenos['encabezado_imagen']) ? url('public/img/' . $conocenos['encabezado_imagen']) : '';

$mision_texto = $conocenos['mision_texto'] ?? '';
$mision_imagen = !empty($conocenos['mision_imagen']) ? url('public/img/' . $conocenos['mision_imagen']) : '';

$vision_texto = $conocenos['vision_texto'] ?? '';
$vision_imagen = !empty($conocenos['vision_imagen']) ? url('public/img/' . $conocenos['vision_imagen']) : '';

$historia_texto = $conocenos['historia_texto'] ?? '';
$historia_imagen = !empty($conocenos['historia_imagen']) ? url('public/img/' . $conocenos['historia_imagen']) : '';
?>

<!-- Hero Section - Full Width -->
<section class="relative w-screen left-1/2 right-1/2 -ml-[50vw] -mr-[50vw] min-h-[75vh] flex items-center overflow-hidden -mt-6">
    <!-- Background Image -->
    <?php if (!empty($encabezado_imagen)): ?>
    <div class="absolute inset-0 z-0">
        <img src="<?php echo htmlspecialchars($encabezado_imagen); ?>" alt="" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-gradient-to-b from-primary-900/80 via-primary-800/70 to-primary-900/90"></div>
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_center,rgba(0,200,215,0.1),transparent_70%)]"></div>
    </div>
    <?php else: ?>
    <div class="absolute inset-0 z-0 bg-gradient-to-br from-primary-900 via-primary-800 to-primary-900">
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_30%_50%,rgba(0,200,215,0.08),transparent_60%)]"></div>
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_70%_50%,rgba(47,90,138,0.1),transparent_60%)]"></div>
    </div>
    <?php endif; ?>

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
            <span class="text-[10px] font-black uppercase tracking-[0.2em] text-[#00c8d7]">SII Importaciones</span>
        </div>
        <h1 class="text-4xl md:text-6xl lg:text-7xl font-black text-white leading-[1.05] mb-6">
            <?php echo htmlspecialchars($encabezado_titulo); ?>
        </h1>
        <?php if (!empty($encabezado_descripcion)): ?>
        <p class="text-primary-200/90 text-lg md:text-xl max-w-3xl mx-auto leading-relaxed">
            <?php echo nl2br(htmlspecialchars($encabezado_descripcion)); ?>
        </p>
        <?php endif; ?>
        <div class="flex flex-wrap items-center justify-center gap-4 mt-8">
            <div class="flex items-center gap-2 text-primary-300 text-sm">
                <span class="w-16 h-px bg-gradient-to-r from-transparent to-primary-400"></span>
                <span class="text-[10px] font-medium uppercase tracking-[0.2em]">Conoce nuestra historia</span>
                <span class="w-16 h-px bg-gradient-to-l from-transparent to-primary-400"></span>
            </div>
        </div>
    </div>
</section>






<!-- Content Section -->
<div class="max-w-6xl mx-auto px-4 relative z-20">
    
    <!-- Misión y Visión -->
<?php if (!empty($mision_texto) || !empty($vision_texto)): ?>
    
    <!-- MISIÓN -->
    <?php if (!empty($mision_texto)): ?>
    <div class="w-screen left-1/2 right-1/2 -ml-[50vw] -mr-[50vw] relative overflow-hidden">
        <!-- Imagen de fondo difuminada -->
        <?php if (!empty($mision_imagen)): ?>
        <div class="absolute inset-0 z-0">
            <img src="<?php echo htmlspecialchars($mision_imagen); ?>" alt="Misión" class="w-full h-full object-cover blur-sm scale-105">
            <div class="absolute inset-0 bg-gradient-to-r from-primary-900/95 via-primary-900/80 to-primary-900/70"></div>
        </div>
        <?php else: ?>
        <div class="absolute inset-0 z-0 bg-gradient-to-br from-primary-800 to-primary-900">
            <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_center,rgba(0,200,215,0.05),transparent_70%)]"></div>
        </div>
        <?php endif; ?>

        <div class="relative z-10 max-w-6xl mx-auto px-4 py-16 md:py-20">
            <div class="grid md:grid-cols-2 gap-10 items-center">
                
                <!-- Contenido -->
                <div class="order-2 md:order-1">
                    <div class="inline-flex items-center gap-2 bg-[#00c8d7]/10 border border-[#00c8d7]/30 rounded-full px-4 py-1.5 mb-4">
                        <span class="text-[10px] font-black uppercase tracking-[0.2em] text-[#00c8d7]">Nuestra razón de ser</span>
                    </div>
                    <h2 class="text-3xl md:text-4xl font-black text-white mb-4">Misión</h2>
                    <p class="text-primary-200/90 text-base md:text-lg leading-relaxed max-w-xl">
                        <?php echo nl2br(htmlspecialchars($mision_texto)); ?>
                    </p>
                    <div class="flex items-center gap-3 mt-6">
                        <div class="w-12 h-12 rounded-2xl bg-[#00c8d7]/10 border border-[#00c8d7]/20 flex items-center justify-center text-[#00c8d7] text-xl">
                            <i class="fa-solid fa-bullseye"></i>
                        </div>
                        <div>
                            <p class="text-xs text-primary-400 font-medium uppercase tracking-wider">Compromiso</p>
                            <p class="text-sm text-white font-semibold">Excelencia en cada importación</p>
                        </div>
                    </div>
                </div>

                <!-- Imagen lateral -->
                <div class="order-1 md:order-2">
                    <?php if (!empty($mision_imagen)): ?>
                    <div class="relative rounded-3xl overflow-hidden shadow-2xl shadow-[#00c8d7]/5 group">
                        <img src="<?php echo htmlspecialchars($mision_imagen); ?>" alt="Misión" class="w-full h-72 md:h-96 object-cover group-hover:scale-105 transition-transform duration-700">
                        <div class="absolute inset-0 bg-gradient-to-t from-primary-900/60 via-transparent to-transparent"></div>
                        <div class="absolute bottom-4 left-4 right-4">
                            <div class="inline-flex items-center gap-2 bg-primary-900/80 backdrop-blur-sm rounded-full px-4 py-1.5 border border-white/10">
                                <span class="relative flex h-2 w-2">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#00c8d7] opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2 w-2 bg-[#00c8d7]"></span>
                                </span>
                                <span class="text-[10px] text-primary-200 font-medium">Nuestra misión</span>
                            </div>
                        </div>
                    </div>
                    <?php else: ?>
                    <div class="relative rounded-3xl overflow-hidden bg-gradient-to-br from-primary-700/50 to-primary-900/50 border border-white/10 h-72 md:h-96 flex items-center justify-center">
                        <div class="text-center">
                            <div class="w-20 h-20 rounded-2xl bg-[#00c8d7]/10 border border-[#00c8d7]/20 flex items-center justify-center text-[#00c8d7] text-3xl mx-auto mb-4">
                                <i class="fa-solid fa-bullseye"></i>
                            </div>
                            <p class="text-primary-300 text-sm">Imagen de la misión</p>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- VISIÓN -->
    <?php if (!empty($vision_texto)): ?>
    <div class="w-screen left-1/2 right-1/2 -ml-[50vw] -mr-[50vw] relative overflow-hidden">
        <!-- Imagen de fondo difuminada -->
        <?php if (!empty($vision_imagen)): ?>
        <div class="absolute inset-0 z-0">
            <img src="<?php echo htmlspecialchars($vision_imagen); ?>" alt="Visión" class="w-full h-full object-cover blur-sm scale-105">
            <div class="absolute inset-0 bg-gradient-to-l from-primary-900/95 via-primary-900/80 to-primary-900/70"></div>
        </div>
        <?php else: ?>
        <div class="absolute inset-0 z-0 bg-gradient-to-br from-primary-900 to-primary-800">
            <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_center,rgba(0,200,215,0.05),transparent_70%)]"></div>
        </div>
        <?php endif; ?>

        <div class="relative z-10 max-w-6xl mx-auto px-4 py-16 md:py-20">
            <div class="grid md:grid-cols-2 gap-10 items-center">
                
                <!-- Imagen lateral -->
                <div class="order-1">
                    <?php if (!empty($vision_imagen)): ?>
                    <div class="relative rounded-3xl overflow-hidden shadow-2xl shadow-[#00c8d7]/5 group">
                        <img src="<?php echo htmlspecialchars($vision_imagen); ?>" alt="Visión" class="w-full h-72 md:h-96 object-cover group-hover:scale-105 transition-transform duration-700">
                        <div class="absolute inset-0 bg-gradient-to-t from-primary-900/60 via-transparent to-transparent"></div>
                        <div class="absolute bottom-4 left-4 right-4">
                            <div class="inline-flex items-center gap-2 bg-primary-900/80 backdrop-blur-sm rounded-full px-4 py-1.5 border border-white/10">
                                <span class="relative flex h-2 w-2">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#00c8d7] opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2 w-2 bg-[#00c8d7]"></span>
                                </span>
                                <span class="text-[10px] text-primary-200 font-medium">Nuestra visión</span>
                            </div>
                        </div>
                    </div>
                    <?php else: ?>
                    <div class="relative rounded-3xl overflow-hidden bg-gradient-to-br from-primary-700/50 to-primary-900/50 border border-white/10 h-72 md:h-96 flex items-center justify-center">
                        <div class="text-center">
                            <div class="w-20 h-20 rounded-2xl bg-[#00c8d7]/10 border border-[#00c8d7]/20 flex items-center justify-center text-[#00c8d7] text-3xl mx-auto mb-4">
                                <i class="fa-solid fa-eye"></i>
                            </div>
                            <p class="text-primary-300 text-sm">Imagen de la visión</p>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>

                <!-- Contenido -->
                <div class="order-2">
                    <div class="inline-flex items-center gap-2 bg-[#00c8d7]/10 border border-[#00c8d7]/30 rounded-full px-4 py-1.5 mb-4">
                        <span class="text-[10px] font-black uppercase tracking-[0.2em] text-[#00c8d7]">Nuestra meta</span>
                    </div>
                    <h2 class="text-3xl md:text-4xl font-black text-white mb-4">Visión</h2>
                    <p class="text-primary-200/90 text-base md:text-lg leading-relaxed max-w-xl">
                        <?php echo nl2br(htmlspecialchars($vision_texto)); ?>
                    </p>
                    <div class="flex items-center gap-3 mt-6">
                        <div class="w-12 h-12 rounded-2xl bg-[#00c8d7]/10 border border-[#00c8d7]/20 flex items-center justify-center text-[#00c8d7] text-xl">
                            <i class="fa-solid fa-eye"></i>
                        </div>
                        <div>
                            <p class="text-xs text-primary-400 font-medium uppercase tracking-wider">Aspiración</p>
                            <p class="text-sm text-white font-semibold">Liderazgo en importaciones</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <?php endif; ?>

<?php endif; ?>











<!-- Nuestra Historia -->
<?php if (!empty($historia_texto)): ?>
<div class="w-screen left-1/2 right-1/2 -ml-[50vw] -mr-[50vw] relative overflow-hidden">
    
    <!-- Imagen de fondo difuminada -->
    <?php if (!empty($historia_imagen)): ?>
    <div class="absolute inset-0 z-0">
        <img src="<?php echo htmlspecialchars($historia_imagen); ?>" alt="Nuestra Historia" class="w-full h-full object-cover blur-sm scale-105">
        <div class="absolute inset-0 bg-gradient-to-r from-primary-900/95 via-primary-900/80 to-primary-900/70"></div>
    </div>
    <?php else: ?>
    <div class="absolute inset-0 z-0 bg-gradient-to-br from-primary-800 to-primary-900">
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_center,rgba(0,200,215,0.05),transparent_70%)]"></div>
    </div>
    <?php endif; ?>

    <!-- Efectos decorativos -->
    <div class="absolute inset-0 z-0 overflow-hidden opacity-30">
        <div class="absolute top-20 left-10 w-64 h-64 bg-[#00c8d7]/5 rounded-full blur-3xl animate-pulse"></div>
        <div class="absolute bottom-20 right-10 w-96 h-96 bg-primary-500/5 rounded-full blur-3xl animate-pulse delay-1000"></div>
    </div>

    <!-- Contenido -->
    <div class="relative z-10 max-w-6xl mx-auto px-4 py-16 md:py-20">
        <div class="grid md:grid-cols-2 gap-10 items-center">
            
            <!-- Texto -->
            <div class="order-2 md:order-1">
                <div class="inline-flex items-center gap-2 bg-[#00c8d7]/10 border border-[#00c8d7]/30 rounded-full px-4 py-1.5 mb-4">
                    <span class="text-[10px] font-black uppercase tracking-[0.2em] text-[#00c8d7]">Nuestra trayectoria</span>
                </div>
                <h2 class="text-3xl md:text-4xl font-black text-white mb-4">Nuestra Historia</h2>
                <p class="text-primary-200/90 text-base md:text-lg leading-relaxed max-w-xl">
                    <?php echo nl2br(htmlspecialchars($historia_texto)); ?>
                </p>
                <div class="flex items-center gap-3 mt-6">
                    <div class="w-12 h-12 rounded-2xl bg-[#00c8d7]/10 border border-[#00c8d7]/20 flex items-center justify-center text-[#00c8d7] text-xl">
                        <i class="fa-solid fa-timeline"></i>
                    </div>
                    <div>
                        <p class="text-xs text-primary-400 font-medium uppercase tracking-wider">Trayectoria</p>
                        <p class="text-sm text-white font-semibold">Construyendo el futuro desde 2020</p>
                    </div>
                </div>
            </div>

            <!-- Imagen lateral -->
            <div class="order-1 md:order-2">
                <?php if (!empty($historia_imagen)): ?>
                <div class="relative rounded-3xl overflow-hidden shadow-2xl shadow-[#00c8d7]/5 group">
                    <img src="<?php echo htmlspecialchars($historia_imagen); ?>" alt="Nuestra Historia" class="w-full h-72 md:h-96 object-cover group-hover:scale-105 transition-transform duration-700">
                    <div class="absolute inset-0 bg-gradient-to-t from-primary-900/60 via-transparent to-transparent"></div>
                    <div class="absolute bottom-4 left-4 right-4">
                        <div class="inline-flex items-center gap-2 bg-primary-900/80 backdrop-blur-sm rounded-full px-4 py-1.5 border border-white/10">
                            <span class="relative flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#00c8d7] opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-[#00c8d7]"></span>
                            </span>
                            <span class="text-[10px] text-primary-200 font-medium">Desde 2020</span>
                        </div>
                    </div>
                </div>
                <?php else: ?>
                <div class="relative rounded-3xl overflow-hidden bg-gradient-to-br from-primary-700/50 to-primary-900/50 border border-white/10 h-72 md:h-96 flex items-center justify-center">
                    <div class="text-center">
                        <div class="w-20 h-20 rounded-2xl bg-[#00c8d7]/10 border border-[#00c8d7]/20 flex items-center justify-center text-[#00c8d7] text-3xl mx-auto mb-4">
                            <i class="fa-solid fa-timeline"></i>
                        </div>
                        <p class="text-primary-300 text-sm">Imagen de nuestra historia</p>
                    </div>
                </div>
                <?php endif; ?>
            </div>

        </div>
    </div>
</div>
<?php endif; ?>





<!-- Nuestro Equipo -->
<?php if (!empty($equipo)): ?>
<div class="w-screen left-1/2 right-1/2 -ml-[50vw] -mr-[50vw] relative overflow-hidden">
    
    <!-- Fondo decorativo -->
    <div class="absolute inset-0 z-0">
        <div class="absolute inset-0 bg-gradient-to-b from-primary-900/50 via-primary-800/30 to-primary-900/50"></div>
        <div class="absolute top-20 left-10 w-64 h-64 bg-[#00c8d7]/5 rounded-full blur-3xl"></div>
        <div class="absolute bottom-20 right-10 w-80 h-80 bg-primary-500/5 rounded-full blur-3xl"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[400px] h-[400px] bg-[#00c8d7]/3 rounded-full blur-3xl"></div>
    </div>

    <!-- Contenido -->
    <div class="relative z-10 max-w-6xl mx-auto px-4 py-16 md:py-20">
        
        <!-- Encabezado -->
        <div class="text-center mb-14">
            <div class="inline-flex items-center gap-2 bg-[#00c8d7]/10 border border-[#00c8d7]/30 rounded-full px-5 py-1.5 mb-4">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-[#00c8d7] opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-[#00c8d7]"></span>
                </span>
                <span class="text-[10px] font-black uppercase tracking-[0.2em] text-[#00c8d7]">Talento humano</span>
            </div>
            <h2 class="text-3xl md:text-4xl lg:text-5xl font-black text-white">
                Nuestro <span class="text-[#00c8d7]">Equipo</span>
            </h2>
            <p class="text-primary-300 mt-3 max-w-2xl mx-auto text-base md:text-lg">
                Contamos con un equipo comprometido y altamente capacitado para ofrecerte el mejor servicio.
            </p>
            <div class="w-16 h-0.5 bg-gradient-to-r from-transparent via-[#00c8d7] to-transparent mx-auto mt-4 rounded-full"></div>
        </div>

        <!-- Grid de miembros -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php foreach ($equipo as $miembro): 
                $foto = !empty($miembro['imagen']) ? url('public/img/' . $miembro['imagen']) : '';
                $nombre = $miembro['nombre'] ?? '';
                $cargo = $miembro['cargo'] ?? '';
                
                $iniciales = '';
                $partes = explode(' ', trim($nombre));
                $iniciales = strtoupper(mb_substr($partes[0] ?? '', 0, 1) . (mb_substr($partes[count($partes)-1] ?? '', 0, 1)));
                if (strlen($iniciales) < 2) $iniciales = strtoupper(mb_substr($nombre, 0, 2));
            ?>
            <!-- Card Glassmorphism -->
            <div class="group relative bg-white/5 backdrop-blur-xl rounded-2xl border border-white/10 p-6 text-center hover:border-[#00c8d7]/40 hover:bg-white/10 transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl hover:shadow-[#00c8d7]/10">
                
                <!-- Efecto de brillo al hover -->
                <div class="absolute inset-0 bg-gradient-to-b from-[#00c8d7]/5 via-transparent to-transparent rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <div class="absolute -inset-px bg-gradient-to-r from-[#00c8d7]/0 via-[#00c8d7]/10 to-[#00c8d7]/0 rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-700 blur-sm"></div>
                
                <!-- Contenido -->
                <div class="relative">
                    
                    <!-- Avatar -->
                    <div class="relative inline-block">
                        <?php if (!empty($foto)): ?>
                        <div class="w-28 h-28 rounded-full mx-auto mb-4 overflow-hidden border-2 border-primary-700/50 group-hover:border-[#00c8d7]/50 transition-all duration-300 shadow-lg group-hover:shadow-[#00c8d7]/20">
                            <img src="<?php echo htmlspecialchars($foto); ?>" alt="<?php echo htmlspecialchars($nombre); ?>" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        </div>
                        <?php else: ?>
                        <div class="w-28 h-28 rounded-full mx-auto mb-4 bg-gradient-to-br from-primary-600 to-primary-800 flex items-center justify-center text-white text-3xl font-bold border-2 border-primary-700/50 group-hover:border-[#00c8d7]/50 transition-all duration-300 shadow-lg group-hover:shadow-[#00c8d7]/20">
                            <?php echo htmlspecialchars($iniciales); ?>
                        </div>
                        <?php endif; ?>
                        
                        <!-- Anillo decorativo al hover -->
                        <div class="absolute -inset-1 rounded-full border-2 border-[#00c8d7]/0 group-hover:border-[#00c8d7]/30 transition-all duration-500"></div>
                        
                        <!-- Badge de verificación -->
                        <div class="absolute -bottom-1 -right-1 w-7 h-7 rounded-full bg-[#00c8d7] border-2 border-primary-900 flex items-center justify-center shadow-lg shadow-[#00c8d7]/30 opacity-0 group-hover:opacity-100 transition-all duration-300 scale-75 group-hover:scale-100">
                            <i class="fa-solid fa-check text-primary-900 text-xs"></i>
                        </div>
                    </div>
                    
                    <!-- Información -->
                    <h4 class="text-lg font-bold text-white group-hover:text-[#00c8d7] transition-colors duration-300">
                        <?php echo htmlspecialchars($nombre); ?>
                    </h4>
                    <span class="text-sm text-primary-400 font-medium"><?php echo htmlspecialchars($cargo); ?></span>
                    
                    <!-- Línea decorativa -->
                    <div class="w-8 h-0.5 bg-gradient-to-r from-[#00c8d7]/0 via-[#00c8d7]/30 to-[#00c8d7]/0 mx-auto mt-3 opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        
    </div>
</div>
<?php endif; ?>





    <!-- CTA Final -->
    <div class="relative bg-gradient-to-r from-primary-800/80 via-primary-700/50 to-primary-800/80 backdrop-blur-sm rounded-3xl border border-white/10 p-10 md:p-14 text-center overflow-hidden mt-8">
        <div class="absolute -top-20 -right-20 w-64 h-64 bg-[#00c8d7]/5 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-20 -left-20 w-64 h-64 bg-primary-500/5 rounded-full blur-3xl"></div>
        
        <div class="relative z-10">
            <h3 class="text-2xl md:text-3xl font-bold text-white mb-4">
                ¿Quieres ser parte de <span class="text-[#00c8d7]">nuestro equipo</span>?
            </h3>
            <p class="text-primary-50 max-w-2xl mx-auto mb-6">
                Si compartes nuestra pasión por la importación y la innovación, ¡contáctanos!
            </p>
            <a href="<?= url('contactos') ?>" 
               class="inline-flex items-center gap-2 font-bold px-5 py-2.5 rounded-xl text-white bg-gradient-to-r from-[#00c8d7] to-primary-500 hover:from-primary-900 hover:to-[#00c8d7] transition-all duration-300 ease-in-out [text-shadow:0_1px_4px_rgba(0,0,0,0.4)]">
                <i class="fa-solid fa-paper-plane"></i>
                Contáctanos
            </a>
        </div>
    </div>
</div>