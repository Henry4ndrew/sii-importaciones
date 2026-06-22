<?php
require_once __DIR__ . '/../../config/database.php';

class EmpresaSimple
{
    /**
     * Obtener los datos de Empresa
     */
    public static function get(): ?array
    {
        try {
            $stmt = db()->prepare("SELECT * FROM empresa LIMIT 1");
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ?: null;
        } catch (PDOException $e) {
            return null;
        }
    }
}