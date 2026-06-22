<?php
require_once __DIR__ . '/../../config/database.php';

class RecuperacionToken
{
    /**
     * Crear un token de recuperación
     */
    public static function crear(int $adminId, int $horasExpiracion = 1): ?string
    {
        $token = bin2hex(random_bytes(32));
        $expiracion = date('Y-m-d H:i:s', strtotime("+{$horasExpiracion} hours"));
        
        try {
            $stmt = db()->prepare('
                INSERT INTO recuperacion_tokens (admin_id, token, expiracion) 
                VALUES (?, ?, ?)
            ');
            $stmt->execute([$adminId, $token, $expiracion]);
            return $token;
        } catch (PDOException $e) {
            error_log("Error al crear token: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Buscar un token válido
     */
    public static function buscarToken(string $token): ?array
    {
        try {
            $stmt = db()->prepare('
                SELECT * FROM recuperacion_tokens 
                WHERE token = ? 
                AND usado = 0 
                AND expiracion > NOW()
                LIMIT 1
            ');
            $stmt->execute([$token]);
            $result = $stmt->fetch();
            return $result ?: null;
        } catch (PDOException $e) {
            error_log("Error al buscar token: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Marcar token como usado
     */
    public static function marcarComoUsado(int $tokenId): bool
    {
        try {
            $stmt = db()->prepare('UPDATE recuperacion_tokens SET usado = 1 WHERE id = ?');
            return $stmt->execute([$tokenId]);
        } catch (PDOException $e) {
            error_log("Error al marcar token como usado: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Eliminar tokens expirados
     */
    public static function limpiarExpirados(): void
    {
        try {
            $stmt = db()->prepare('DELETE FROM recuperacion_tokens WHERE expiracion < NOW() OR usado = 1');
            $stmt->execute();
        } catch (PDOException $e) {
            error_log("Error al limpiar tokens expirados: " . $e->getMessage());
        }
    }
}