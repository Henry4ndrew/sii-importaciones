<?php

class VotoController
{
    public function votar(): void
    {
        if (!auth()) {
            flash('error', 'Debes iniciar sesión para votar.');
            redirect('login');
        }

        $productoId = (int) ($_POST['producto_id'] ?? 0);

        if ($productoId > 0 && Voto::votar(auth()['id'], $productoId)) {
            flash('exito', '¡Tu voto fue registrado!');
        } else {
            flash('error', 'Ya habías votado por este producto.');
        }

        // ============================================
        // REDIRIGIR AL DASHBOARD (listado de productos)
        // ============================================
        // Si viene de ranking, volver a ranking, sino al dashboard
        $volverA = $_POST['volver_a'] ?? 'dashboard';
        
        if ($volverA === 'ranking') {
            redirect('ranking');
        } else {
            // Redirigir al dashboard (listado de productos)
            redirect('dashboard');
        }
    }
}