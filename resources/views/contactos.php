<?php
// Asegurar que el estilo sea consistente con SII Importaciones
$primaryColor = '#2F5A8A';
$secondaryColor = '#22d3ee';
?>

<div style="max-width: 1200px; margin: 2rem auto; padding: 0 1.5rem; font-family: 'Inter', system-ui, -apple-system, sans-serif;">
    
    <!-- Hero Header -->
    <div class="contactos-hero" style="text-align: center; margin-bottom: 4rem;">
        <span style="background: rgba(47, 90, 138, 0.15); color: <?= $secondaryColor ?>; padding: 0.5rem 1.5rem; border-radius: 9999px; font-size: 0.85rem; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; border: 1px solid rgba(47, 90, 138, 0.25);">
            <i class="fas fa-headset mr-2"></i> Atención al Cliente
        </span>
        <h1 style="color: #0A1626; font-size: 2.75rem; font-weight: 800; margin: 1rem 0 0.5rem 0; letter-spacing: -0.025em;">
            Nuestros Contactos y Sucursales
        </h1>
        <p style="color: #64748b; font-size: 1.1rem; max-width: 600px; margin: 0 auto; line-height: 1.6;">
            Encuentra la sucursal más cercana o contáctanos por teléfono y correo electrónico. Estamos listos para ayudarte.
        </p>
    </div>

    <!-- Contact Cards Grid -->
    <?php if(empty($contactos)): ?>
        <div style="background: rgba(241, 245, 249, 0.6); border-radius: 1rem; border: 1px solid rgba(47, 90, 138, 0.1); padding: 5rem 2rem; text-align: center;">
            <i class="fas fa-address-book" style="font-size: 3.5rem; color: #94a3b8; margin-bottom: 1.5rem; display: block;"></i>
            <h3 style="color: #334155; margin: 0 0 0.5rem 0; font-size: 1.3rem; font-weight: 700;">No hay información de contacto registrada</h3>
            <p style="color: #64748b; margin: 0; font-size: 0.95rem;">Por favor, vuelve más tarde.</p>
        </div>
    <?php else: ?>
        <div class="contactos-grid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 2.5rem;">
            <?php foreach($contactos as $contacto): 
                // Determinar la URL de la imagen
                $imgUrl = !empty($contacto['imagen']) ? url('public/img/' . $contacto['imagen']) : 'https://images.unsplash.com/photo-1560518883-ce09059eeffa?w=800';
            ?>
                <div class="contact-card" style="background: #ffffff; border-radius: 1.25rem; border: 1px solid rgba(47, 90, 138, 0.12); overflow: hidden; box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.08); display: flex; flex-direction: column; transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);" 
                     onmouseover="this.style.borderColor='#2F5A8A'; this.style.transform='translateY(-6px)'; this.style.boxShadow='0 20px 40px -15px rgba(47, 90, 138, 0.2)';" 
                     onmouseout="this.style.borderColor='rgba(47, 90, 138, 0.12)'; this.style.transform='none'; this.style.boxShadow='0 10px 30px -10px rgba(0, 0, 0, 0.08)';">
                    
                    <!-- Branch Image -->
                    <div style="width: 100%; height: 220px; overflow: hidden; position: relative;">
                        <img src="<?= $imgUrl ?>" alt="<?= e($contacto['ciudad']) ?>" 
                             style="width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s;" 
                             onmouseover="this.style.transform='scale(1.05)';" 
                             onmouseout="this.style.transform='none';">
                        <div style="position: absolute; bottom: 0; left: 0; right: 0; height: 80px; background: linear-gradient(180deg, rgba(0,0,0,0) 0%, rgba(255,255,255,0.95) 100%);"></div>
                    </div>

                    <!-- Card Body -->
                    <div style="padding: 1.75rem; flex-grow: 1; display: flex; flex-direction: column; justify-content: space-between;">
                        <div>
                            <!-- City Name -->
                            <h2 style="margin: 0 0 1.25rem 0; font-size: 1.5rem; font-weight: 800; color: #0A1626; display: flex; align-items: center; gap: 0.65rem;">
                                <i class="fas fa-location-dot" style="color: #2F5A8A;"></i>
                                <?= e($contacto['ciudad']) ?>
                            </h2>

                            <!-- Contact details -->
                            <div style="display: flex; flex-direction: column; gap: 1rem; margin-bottom: 1.5rem;">
                                <div style="display: flex; align-items: center; gap: 0.75rem; font-size: 0.95rem; color: #334155;">
                                    <div style="width: 2rem; height: 2rem; border-radius: 50%; display: flex; align-items: center; justify-content: center; background: rgba(47, 90, 138, 0.08); border: 1px solid rgba(47, 90, 138, 0.12); color: #2F5A8A; flex-shrink: 0;">
                                        <i class="fas fa-phone"></i>
                                    </div>
                                    <div>
                                        <div style="font-size: 0.7rem; color: #94a3b8; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em;">Teléfono</div>
                                        <span style="font-weight: 600; color: #1e293b;"><?= e($contacto['telefono']) ?></span>
                                    </div>
                                </div>

                                <div style="display: flex; align-items: center; gap: 0.75rem; font-size: 0.95rem; color: #334155;">
                                    <div style="width: 2rem; height: 2rem; border-radius: 50%; display: flex; align-items: center; justify-content: center; background: rgba(47, 90, 138, 0.08); border: 1px solid rgba(47, 90, 138, 0.12); color: #2F5A8A; flex-shrink: 0;">
                                        <i class="fas fa-envelope"></i>
                                    </div>
                                    <div>
                                        <div style="font-size: 0.7rem; color: #94a3b8; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em;">Correo</div>
                                        <a href="mailto:<?= e($contacto['correo']) ?>" style="color: #2F5A8A; text-decoration: none; font-weight: 600; transition: color 0.2s;" 
                                           onmouseover="this.style.color='#1C3956'" 
                                           onmouseout="this.style.color='#2F5A8A'"><?= e($contacto['correo']) ?></a>
                                    </div>
                                </div>
                            </div>

                            <!-- Schedule Panel -->
                            <?php if (!empty($contacto['horarios'])): ?>
                            <div style="background: #f8fafc; border-radius: 0.75rem; padding: 1rem 1.25rem; border: 1px solid rgba(47, 90, 138, 0.06);">
                                <div style="font-size: 0.7rem; text-transform: uppercase; color: #94a3b8; font-weight: 800; margin-bottom: 0.5rem; letter-spacing: 0.05em; display: flex; align-items: center; gap: 0.35rem;">
                                    <i class="fas fa-clock" style="color: #2F5A8A;"></i> Horarios de Atención
                                </div>
                                <div style="color: #475569; font-size: 0.9rem; line-height: 1.6; white-space: pre-line;">
                                    <?= nl2br(e($contacto['horarios'])) ?>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>

                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</div>

<style>
@media (max-width: 480px) {
    .contactos-hero h1 { 
        font-size: 1.7rem !important; 
    }
    .contactos-grid { 
        grid-template-columns: 1fr !important; 
        gap: 1.5rem !important; 
    }
    .contact-card { 
        margin: 0 !important; 
    }
}

/* Animación suave al cargar */
.contact-card {
    animation: fadeInUp 0.6s ease both;
}

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

/* Aplicar delay a cada tarjeta */
.contact-card:nth-child(1) { animation-delay: 0.1s; }
.contact-card:nth-child(2) { animation-delay: 0.2s; }
.contact-card:nth-child(3) { animation-delay: 0.3s; }
.contact-card:nth-child(4) { animation-delay: 0.4s; }
.contact-card:nth-child(5) { animation-delay: 0.5s; }
.contact-card:nth-child(6) { animation-delay: 0.6s; }
</style>