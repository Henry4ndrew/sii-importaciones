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

<style>
.conocenos-hero {
    position: relative; 
    padding: 6rem 2rem; 
    text-align: center;
    background: linear-gradient(135deg, #0A1626 0%, #1C3956 50%, #0A1626 100%);
    overflow: hidden;
    border-radius: 0 0 2rem 2rem;
    margin-bottom: 2rem;
}
.conocenos-hero.has-bg {
    background: none; 
    position: relative;
}
.conocenos-hero-bg {
    position: absolute; 
    inset: 0; 
    z-index: 0;
}
.conocenos-hero-bg img {
    width: 100%; 
    height: 100%; 
    object-fit: cover; 
    filter: brightness(0.3) saturate(1.1);
}
.conocenos-hero::before {
    content: ''; 
    position: absolute; 
    top: -50%; 
    left: -50%; 
    width: 200%; 
    height: 200%;
    background: radial-gradient(circle at 30% 40%, rgba(47, 90, 138, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 70% 60%, rgba(47, 90, 138, 0.10) 0%, transparent 50%);
    pointer-events: none; 
    z-index: 1;
}
.conocenos-hero h1 {
    font-size: clamp(2rem, 4vw, 3rem); 
    font-weight: 900; 
    color: #ffffff;
    position: relative; 
    z-index: 1; 
    margin: 0 0 1rem 0; 
    letter-spacing: -0.02em;
}
.conocenos-hero p {
    font-size: 1.1rem; 
    color: #B8CCE3; 
    max-width: 640px; 
    margin: 0 auto;
    position: relative; 
    z-index: 1; 
    line-height: 1.7;
}
.conocenos-section {
    max-width: 1100px; 
    margin: 0 auto; 
    padding: 2rem 1.5rem 4rem;
}
.conocenos-grid {
    display: grid; 
    grid-template-columns: 1fr 1fr; 
    gap: 2rem; 
    margin-bottom: 3rem;
}
.conocenos-card {
    background: #ffffff;
    border: 1px solid rgba(47, 90, 138, 0.1);
    border-radius: 1rem; 
    padding: 2.5rem; 
    box-shadow: 0 4px 20px rgba(0,0,0,0.05);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.conocenos-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 20px 40px -15px rgba(47, 90, 138, 0.2);
}
.conocenos-card-icon {
    width: 48px; 
    height: 48px; 
    border-radius: 12px; 
    display: flex;
    align-items: center; 
    justify-content: center; 
    margin-bottom: 1.25rem;
    font-size: 1.3rem;
}
.conocenos-card h3 { 
    color: #0A1626; 
    margin: 0 0 0.75rem 0; 
    font-size: 1.25rem; 
    font-weight: 700; 
}
.conocenos-card p { 
    color: #475569; 
    margin: 0; 
    line-height: 1.7; 
    font-size: 0.95rem; 
}

.conocenos-history {
    background: #ffffff;
    border: 1px solid rgba(47, 90, 138, 0.1);
    border-radius: 1rem; 
    padding: 2.5rem; 
    box-shadow: 0 4px 20px rgba(0,0,0,0.05);
    margin-bottom: 3rem;
}
.conocenos-history h3 { 
    color: #0A1626; 
    margin: 0 0 1rem 0; 
    font-size: 1.25rem; 
    font-weight: 700; 
}
.conocenos-history p { 
    color: #475569; 
    margin: 0; 
    line-height: 1.8; 
    font-size: 0.95rem; 
}

.team-section {
    text-align: center; 
    margin-top: 3rem;
}
.team-section h2 {
    color: #0A1626; 
    font-size: 1.75rem; 
    font-weight: 800; 
    margin: 0 0 0.5rem 0;
}
.team-section p {
    color: #64748b; 
    margin: 0 0 2rem 0; 
    font-size: 1.05rem;
}
.team-grid {
    display: grid; 
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 1.5rem; 
    margin-top: 0.5rem;
}
.team-card {
    background: #ffffff;
    border: 1px solid rgba(47, 90, 138, 0.1);
    border-radius: 1rem; 
    padding: 2rem 1.5rem; 
    text-align: center;
    box-shadow: 0 4px 15px rgba(0,0,0,0.04);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.team-card:hover { 
    transform: translateY(-4px);
    box-shadow: 0 15px 30px -15px rgba(47, 90, 138, 0.15);
}
.team-avatar {
    width: 80px; 
    height: 80px; 
    border-radius: 50%; 
    margin: 0 auto 1rem;
    background: linear-gradient(135deg, #2F5A8A, #5D89BF);
    display: flex; 
    align-items: center; 
    justify-content: center;
    color: #fff; 
    font-size: 1.8rem; 
    font-weight: 700;
    overflow: hidden;
}
.team-avatar img {
    width: 100%; 
    height: 100%; 
    object-fit: cover;
}
.team-card h4 { 
    color: #0A1626; 
    margin: 0 0 0.25rem 0; 
    font-size: 1rem; 
    font-weight: 700; 
}
.team-card span { 
    color: #2F5A8A; 
    font-size: 0.85rem; 
    font-weight: 500; 
}

@media (max-width: 768px) {
    .conocenos-hero { 
        padding: 3rem 1.2rem; 
        border-radius: 0 0 1.5rem 1.5rem;
    }
    .conocenos-hero h1 { 
        font-size: 1.6rem; 
    }
    .conocenos-hero p { 
        font-size: 0.9rem; 
    }
    .conocenos-section { 
        padding: 1rem 1rem 2rem; 
    }
    .conocenos-grid { 
        grid-template-columns: 1fr; 
        gap: 1.25rem; 
    }
    .conocenos-card { 
        padding: 1.5rem; 
    }
    .conocenos-card h3 { 
        font-size: 1.1rem; 
    }
    .conocenos-card p { 
        font-size: 0.88rem; 
    }
    .conocenos-card-icon { 
        width: 40px; 
        height: 40px; 
        font-size: 1.1rem; 
    }
    .conocenos-history { 
        padding: 1.5rem; 
    }
    .conocenos-history h3 { 
        font-size: 1.1rem; 
    }
    .conocenos-history p { 
        font-size: 0.88rem; 
    }
    .team-grid { 
        grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); 
        gap: 1rem; 
    }
    .team-card { 
        padding: 1.25rem 1rem; 
    }
    .team-avatar { 
        width: 60px; 
        height: 60px; 
        font-size: 1.3rem; 
    }
    .team-card h4 { 
        font-size: 0.9rem; 
    }
    .team-card span { 
        font-size: 0.78rem; 
    }
    .team-section h2 {
        font-size: 1.4rem;
    }
}
@media (max-width: 400px) {
    .team-grid { 
        grid-template-columns: 1fr 1fr; 
    }
    .conocenos-hero h1 { 
        font-size: 1.4rem; 
    }
}
</style>

<!-- Hero -->
<section class="conocenos-hero<?php echo !empty($encabezado_imagen) ? ' has-bg' : ''; ?>">
    <?php if (!empty($encabezado_imagen)): ?>
    <div class="conocenos-hero-bg">
        <img src="<?php echo htmlspecialchars($encabezado_imagen); ?>" alt="">
    </div>
    <?php endif; ?>
    <h1><?php echo htmlspecialchars($encabezado_titulo); ?></h1>
    <?php if (!empty($encabezado_descripcion)): ?>
    <p><?php echo nl2br(htmlspecialchars($encabezado_descripcion)); ?></p>
    <?php endif; ?>
</section>

<div class="conocenos-section">
    <!-- Misión y Visión -->
    <?php if (!empty($mision_texto) || !empty($vision_texto)): ?>
    <div class="conocenos-grid">
        <?php if (!empty($mision_texto)): ?>
        <div class="conocenos-card">
            <?php if (!empty($mision_imagen)): ?>
            <div style="border-radius: 0.6rem; overflow: hidden; margin-bottom: 1.25rem; height: 160px;">
                <img src="<?php echo htmlspecialchars($mision_imagen); ?>" alt="Misión" style="width:100%; height:100%; object-fit:cover;">
            </div>
            <?php endif; ?>
            <div class="conocenos-card-icon" style="background: rgba(47, 90, 138, 0.1); color: #2F5A8A;">
                <i class="fa-solid fa-bullseye"></i>
            </div>
            <h3>Misión</h3>
            <p><?php echo nl2br(htmlspecialchars($mision_texto)); ?></p>
        </div>
        <?php endif; ?>

        <?php if (!empty($vision_texto)): ?>
        <div class="conocenos-card">
            <?php if (!empty($vision_imagen)): ?>
            <div style="border-radius: 0.6rem; overflow: hidden; margin-bottom: 1.25rem; height: 160px;">
                <img src="<?php echo htmlspecialchars($vision_imagen); ?>" alt="Visión" style="width:100%; height:100%; object-fit:cover;">
            </div>
            <?php endif; ?>
            <div class="conocenos-card-icon" style="background: rgba(47, 90, 138, 0.1); color: #2F5A8A;">
                <i class="fa-solid fa-eye"></i>
            </div>
            <h3>Visión</h3>
            <p><?php echo nl2br(htmlspecialchars($vision_texto)); ?></p>
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <!-- Nuestra Historia -->
    <?php if (!empty($historia_texto)): ?>
    <div class="conocenos-history" style="<?php echo !empty($historia_imagen) ? ' display: grid; grid-template-columns: 1fr 280px; gap: 2rem; align-items: center;' : ''; ?>">
        <div>
            <h3><i class="fas fa-book-open mr-2" style="color: #2F5A8A;"></i> Nuestra Historia</h3>
            <p><?php echo nl2br(htmlspecialchars($historia_texto)); ?></p>
        </div>
        <?php if (!empty($historia_imagen)): ?>
        <div style="border-radius: 0.75rem; overflow: hidden; height: 200px;">
            <img src="<?php echo htmlspecialchars($historia_imagen); ?>" alt="Nuestra Historia" style="width:100%; height:100%; object-fit:cover;">
        </div>
        <?php endif; ?>
    </div>
    <?php endif; ?>

    <!-- Nuestro Equipo -->
    <?php if (!empty($equipo)): ?>
    <div class="team-section">
        <h2><i class="fas fa-users mr-2" style="color: #2F5A8A;"></i> Nuestro Equipo</h2>
        <p>Contamos con un equipo comprometido y altamente capacitado.</p>
    </div>
    <div class="team-grid">
        <?php foreach ($equipo as $miembro): 
            $foto = !empty($miembro['imagen']) ? url('public/img/' . $miembro['imagen']) : '';
            $nombre = $miembro['nombre'] ?? '';
            $cargo = $miembro['cargo'] ?? '';
            
            // Iniciales para avatar
            $iniciales = '';
            $partes = explode(' ', trim($nombre));
            $iniciales = strtoupper(mb_substr($partes[0] ?? '', 0, 1) . (mb_substr($partes[count($partes)-1] ?? '', 0, 1)));
            if (strlen($iniciales) < 2) $iniciales = strtoupper(mb_substr($nombre, 0, 2));
        ?>
        <div class="team-card">
            <?php if (!empty($foto)): ?>
            <div class="team-avatar" style="overflow: hidden; padding: 0; background: none;">
                <img src="<?php echo htmlspecialchars($foto); ?>" alt="<?php echo htmlspecialchars($nombre); ?>" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
            </div>
            <?php else: ?>
            <div class="team-avatar"><?php echo htmlspecialchars($iniciales); ?></div>
            <?php endif; ?>
            <h4><?php echo htmlspecialchars($nombre); ?></h4>
            <span><?php echo htmlspecialchars($cargo); ?></span>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>