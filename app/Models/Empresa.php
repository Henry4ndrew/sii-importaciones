<?php
require_once __DIR__ . '/../../config/database.php';

class Empresa
{
    /**
     * Obtener los datos de Empresa (siempre un solo registro)
     */
    public static function get(): ?array
    {
        $stmt = db()->query('SELECT * FROM empresa LIMIT 1');
        $result = $stmt->fetch();
        return $result ?: null;
    }

    /**
     * Actualizar los datos de Empresa
     */
    public static function update(array $data): bool
    {
        $sql = 'UPDATE empresa SET 
            descripcion_corporativa = ?,
            email_principal = ?,
            direccion_textual = ?,
            enlace_gps = ?,
            facebook = ?,
            instagram = ?,
            tiktok = ?,
            youtube = ?,
            whatsapp = ?
        ';
        $stmt = db()->prepare($sql);
        return $stmt->execute([
            $data['descripcion_corporativa'] ?? null,
            $data['email_principal'] ?? null,
            $data['direccion_textual'] ?? null,
            $data['enlace_gps'] ?? null,
            $data['facebook'] ?? null,
            $data['instagram'] ?? null,
            $data['tiktok'] ?? null,
            $data['youtube'] ?? null,
            $data['whatsapp'] ?? null
        ]);
    }
}