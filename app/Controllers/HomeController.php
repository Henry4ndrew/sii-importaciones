<?php

class HomeController
{
    /**
     * Página "Conócenos"
     */
    public function conocenos(): void
    {
        // Obtener datos de Conócenos
        require_once __DIR__ . '/../Models/Conocenos.php';
        $conocenos = Conocenos::get();
        $equipo = Conocenos::getEquipo();
        
        // Si no hay datos, usar valores por defecto
        if (!$conocenos) {
            $conocenos = [
                'encabezado_titulo' => 'Sobre Nosotros',
                'encabezado_descripcion' => 'Conoce quiénes somos y cómo trabajamos para ofrecerte los mejores productos importados.',
                'encabezado_imagen' => null,
                'mision_texto' => 'Nuestra misión es conectar a nuestros clientes con los mejores proveedores internacionales, garantizando calidad, confianza y transparencia en cada proceso de importación.',
                'mision_imagen' => null,
                'vision_texto' => 'Ser la empresa líder en importaciones en Latinoamérica, reconocida por nuestra excelencia en servicio y la calidad de nuestros productos.',
                'vision_imagen' => null,
                'historia_texto' => 'Fundada en 2020, comenzamos como un pequeño emprendimiento con el sueño de llevar productos de calidad desde China a todo Latinoamérica.',
                'historia_imagen' => null,
            ];
        }
        
        view('conocenos', [
            'titulo' => 'Conócenos - Sii importaciones',
            'conocenos' => $conocenos,
            'equipo' => $equipo
        ]);
    }

    /**
     * Página "Servicios"
     */
    public function servicios(): void
    {
        // Obtener servicios activos con sus subsecciones
        require_once __DIR__ . '/../Models/Servicio.php';
        $servicios = Servicio::getActivos();
        
        view('servicios', [
            'titulo' => 'Servicios - Sii importaciones',
            'servicios' => $servicios
        ]);
    }


    /**
     * Página "Contactos"
     */
    public function contactos(): void
    {
        // Obtener contactos activos desde la base de datos
        require_once __DIR__ . '/../Models/Contacto.php';
        $contactos = Contacto::getActivos();
        
        view('contactos', [
            'titulo' => 'Contactos - Sii importaciones',
            'contactos' => $contactos
        ]);
    }
}