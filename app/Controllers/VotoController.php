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

        // Volver a la página desde donde se votó
        $volverA = $_POST['volver_a'] ?? '/';
        redirect($volverA === 'ranking' ? 'ranking' : '/');
    }
}
