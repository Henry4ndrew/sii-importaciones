<?php
require_once __DIR__ . '/../../config/database.php';

class Administrador
{
    /**
     * Buscar administrador por email
     */
    public static function buscarPorEmail(string $email): ?array
    {
        try {
            $stmt = db()->prepare('SELECT * FROM administradores WHERE email = ?');
            $stmt->execute([$email]);
            $admin = $stmt->fetch();
            return $admin ?: null;
        } catch (PDOException $e) {
            self::logError("Error en buscarPorEmail: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Verificar contraseña
     */
    public static function verificarPassword(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }

    /**
     * Obtener administrador por ID
     */
    public static function buscarPorId(int $id): ?array
    {
        try {
            $stmt = db()->prepare('SELECT * FROM administradores WHERE id = ?');
            $stmt->execute([$id]);
            $admin = $stmt->fetch();
            return $admin ?: null;
        } catch (PDOException $e) {
            self::logError("Error en buscarPorId: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Actualizar contraseña de administrador
     */
    public static function actualizarPassword(int $id, string $nuevaPassword): bool
    {
        try {
            $hash = password_hash($nuevaPassword, PASSWORD_DEFAULT);
            $stmt = db()->prepare('UPDATE administradores SET password = ? WHERE id = ?');
            return $stmt->execute([$hash, $id]);
        } catch (PDOException $e) {
            self::logError("Error al actualizar password: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Función para registrar errores en un archivo de log
     */
    private static function logError(string $mensaje): void
    {
        $logFile = __DIR__ . '/../../logs/administrador.log';
        $logDir = dirname($logFile);
        
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
        
        $contenido = date('[Y-m-d H:i:s] ') . $mensaje . "\n";
        file_put_contents($logFile, $contenido, FILE_APPEND);
    }
}