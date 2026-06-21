<?php
require_once __DIR__ . '/../../config/database.php';

class Usuario
{
    public static function buscarPorEmail(string $email): ?array
    {
        $stmt = db()->prepare('SELECT * FROM usuarios WHERE email = ?');
        $stmt->execute([$email]);
        $usuario = $stmt->fetch();
        return $usuario ?: null;
    }

    public static function crear(string $email): int
    {
        $stmt = db()->prepare('INSERT INTO usuarios (email) VALUES (?)');
        $stmt->execute([$email]);
        return (int) db()->lastInsertId();
    }
}