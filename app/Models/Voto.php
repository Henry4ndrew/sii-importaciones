<?php

class Voto
{
    // Devuelve true si el voto se registró, false si el usuario ya había votado
    public static function votar(int $usuarioId, int $productoId): bool
    {
        $stmt = db()->prepare('INSERT IGNORE INTO votos (usuario_id, producto_id) VALUES (?, ?)');
        $stmt->execute([$usuarioId, $productoId]);
        return $stmt->rowCount() > 0;
    }

    // Ids de productos que el usuario ya votó (para pintar el botón distinto)
    public static function productosVotadosPor(int $usuarioId): array
    {
        $stmt = db()->prepare('SELECT producto_id FROM votos WHERE usuario_id = ?');
        $stmt->execute([$usuarioId]);
        return array_column($stmt->fetchAll(), 'producto_id');
    }
}
