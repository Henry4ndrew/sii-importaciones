<?php

class Usuario
{
    public static function buscarPorEmail(string $email): ?array
    {
        $stmt = db()->prepare('SELECT * FROM usuarios WHERE email = ?');
        $stmt->execute([$email]);
        $usuario = $stmt->fetch();
        return $usuario ?: null;
    }

    public static function crear(string $nombre, string $email, string $password): int
    {
        $stmt = db()->prepare('INSERT INTO usuarios (nombre, email, password) VALUES (?, ?, ?)');
        $stmt->execute([$nombre, $email, password_hash($password, PASSWORD_DEFAULT)]);
        return (int) db()->lastInsertId();
    }
}
