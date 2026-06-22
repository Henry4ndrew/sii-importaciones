<?php

class HomeController
{
    /**
     * Página "Conócenos"
     */
    public function conocenos(): void
    {
        view('conocenos', ['titulo' => 'Conócenos - Sii importaciones']);
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