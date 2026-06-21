<?php

class HomeController
{
    /**
     * Página "Conócenos"
     */
    public function conocenos(): void
    {
        view('conocenos', ['titulo' => 'Conócenos - WILLS IMPORT']);
    }

    /**
     * Página "Servicios"
     */
    public function servicios(): void
    {
        view('servicios', ['titulo' => 'Servicios - WILLS IMPORT']);
    }

    /**
     * Página "Contactos"
     */
    public function contactos(): void
    {
        view('contactos', ['titulo' => 'Contactos - WILLS IMPORT']);
    }
}