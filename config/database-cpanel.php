<?php

function db(): PDO
{
    static $pdo = null;

    if ($pdo === null) {
        try {
            $pdo = new PDO(
                'mysql:host=localhost;dbname=morelia_sii;charset=utf8mb4',
                'morelia_importacion',
                'luisalberto2024',
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]
            );
        } catch (PDOException $e) {
            // Si falla, mostrar error amigable
            die('Error de conexión a la base de datos. Por favor, verifica la configuración.');
        }
    }

    return $pdo;
}