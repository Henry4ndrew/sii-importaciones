<?php

namespace App\Core;

class Controller
{
    /**
     * Renderizar una vista
     */
    protected function view(string $view, array $data = []): void
    {
        view($view, $data);
    }

    /**
     * Redirigir a una ruta
     */
    protected function redirect(string $route): void
    {
        redirect($route);
    }

    /**
     * Obtener el usuario autenticado
     */
    protected function auth(): ?array
    {
        return auth();
    }

    /**
     * Verificar si el usuario está autenticado
     */
    protected function isAuthenticated(): bool
    {
        return auth() !== null;
    }

    /**
     * Requerir autenticación para acceder a esta página
     */
    protected function requireAuth(): void
    {
        if (!$this->isAuthenticated()) {
            flash('error', 'Debes iniciar sesión para acceder a esta página.');
            redirect('login');
        }
    }
}