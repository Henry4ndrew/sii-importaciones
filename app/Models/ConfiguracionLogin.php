<?php
require_once __DIR__ . '/../../config/database.php';

class ConfiguracionLogin
{
    /**
     * Obtener la configuración actual del login
     */
    public static function getConfig(): array
    {
        try {
            $stmt = db()->query('SELECT * FROM configuraciones_login LIMIT 1');
            $config = $stmt->fetch();
            
            if (!$config) {
                // Si no existe, crear configuración por defecto
                self::crearConfiguracionDefault();
                $stmt = db()->query('SELECT * FROM configuraciones_login LIMIT 1');
                $config = $stmt->fetch();
            }
            
            return $config;
        } catch (PDOException $e) {
            error_log("Error en ConfiguracionLogin::getConfig: " . $e->getMessage());
            return [
                'id' => null,
                'login_habilitado' => true,
                'mensaje' => 'El sistema de acceso para usuarios se encuentra temporalmente deshabilitado.',
                'updated_at' => date('Y-m-d H:i:s')
            ];
        }
    }
    
    /**
     * Actualizar la configuración del login
     */
    public static function actualizar(bool $habilitado, string $mensaje): bool
    {
        try {
            $config = self::getConfig();
            
            // Convertir a entero para la base de datos
            $habilitadoInt = $habilitado ? 1 : 0;
            
            if ($config['id']) {
                $stmt = db()->prepare('UPDATE configuraciones_login SET login_habilitado = ?, mensaje = ? WHERE id = ?');
                return $stmt->execute([$habilitadoInt, $mensaje, $config['id']]);
            } else {
                $stmt = db()->prepare('INSERT INTO configuraciones_login (login_habilitado, mensaje) VALUES (?, ?)');
                return $stmt->execute([$habilitadoInt, $mensaje]);
            }
        } catch (PDOException $e) {
            error_log("Error en ConfiguracionLogin::actualizar: " . $e->getMessage());
            return false;
        }
    }
    /**
     * Crear configuración por defecto
     */
    private static function crearConfiguracionDefault(): void
    {
        try {
            $stmt = db()->prepare('INSERT INTO configuraciones_login (login_habilitado, mensaje) VALUES (1, ?)');
            $stmt->execute(['El sistema de acceso para usuarios se encuentra temporalmente deshabilitado. Por favor, intenta más tarde.']);
        } catch (PDOException $e) {
            error_log("Error en ConfiguracionLogin::crearConfiguracionDefault: " . $e->getMessage());
        }
    }
}