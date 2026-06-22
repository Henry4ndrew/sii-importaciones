<?php
require_once __DIR__ . '/../../config/database.php';

class Portada
{
    /**
     * Obtener todas las portadas
     */
    public static function getAll(): array
    {
        $stmt = db()->query('SELECT * FROM portadas ORDER BY orden ASC, created_at DESC');
        return $stmt->fetchAll();
    }

    /**
     * Obtener portada por ID
     */
    public static function getById(int $id): ?array
    {
        $stmt = db()->prepare('SELECT * FROM portadas WHERE id = ?');
        $stmt->execute([$id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    /**
     * Obtener portadas activas
     */
    public static function getActivas(): array
    {
        $stmt = db()->query('SELECT * FROM portadas WHERE activo = 1 ORDER BY orden ASC');
        return $stmt->fetchAll();
    }

    /**
     * Obtener el último orden
     */
    public static function getUltimoOrden(): int
    {
        $stmt = db()->query('SELECT MAX(orden) as max_orden FROM portadas');
        $result = $stmt->fetch();
        return (int) ($result['max_orden'] ?? 0) + 1;
    }

    /**
     * Verificar si un orden ya existe (excepto un ID específico)
     */
    public static function ordenExiste(int $orden, ?int $excluirId = null): bool
    {
        $sql = 'SELECT COUNT(*) as total FROM portadas WHERE orden = ?';
        $params = [$orden];
        
        if ($excluirId !== null) {
            $sql .= ' AND id != ?';
            $params[] = $excluirId;
        }
        
        $stmt = db()->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch();
        return (int) $result['total'] > 0;
    }

    /**
     * Reordenar portadas (para mantener orden consecutivo)
     */
    public static function reordenar(): void
    {
        // Obtener todas las portadas ordenadas por orden
        $stmt = db()->query('SELECT id FROM portadas ORDER BY orden ASC, created_at ASC');
        $portadas = $stmt->fetchAll();
        
        $nuevoOrden = 1;
        foreach ($portadas as $portada) {
            $update = db()->prepare('UPDATE portadas SET orden = ? WHERE id = ?');
            $update->execute([$nuevoOrden, $portada['id']]);
            $nuevoOrden++;
        }
    }

    /**
     * Crear portada
     */
    public static function create(array $data): int
    {
        // Si no se especifica orden, usar el último + 1
        if (!isset($data['orden']) || $data['orden'] === 0) {
            $data['orden'] = self::getUltimoOrden();
        }
        
        $stmt = db()->prepare('
            INSERT INTO portadas (titulo, descripcion, ruta_imagen, orden, activo) 
            VALUES (?, ?, ?, ?, ?)
        ');
        $stmt->execute([
            $data['titulo'],
            $data['descripcion'] ?? null,
            $data['ruta_imagen'],
            $data['orden'],
            $data['activo'] ?? 1
        ]);
        return (int) db()->lastInsertId();
    }

    /**
     * Actualizar portada
     */
    public static function update(int $id, array $data): bool
    {
        $stmt = db()->prepare('
            UPDATE portadas SET 
                titulo = ?, 
                descripcion = ?, 
                ruta_imagen = ?, 
                orden = ?, 
                activo = ? 
            WHERE id = ?
        ');
        return $stmt->execute([
            $data['titulo'],
            $data['descripcion'] ?? null,
            $data['ruta_imagen'],
            $data['orden'] ?? 0,
            $data['activo'] ?? 1,
            $id
        ]);
    }

    /**
     * Eliminar portada
     */
    public static function delete(int $id): bool
    {
        $stmt = db()->prepare('DELETE FROM portadas WHERE id = ?');
        return $stmt->execute([$id]);
    }

    /**
     * Cambiar estado (activo/inactivo)
     */
    public static function toggleEstado(int $id): bool
    {
        $portada = self::getById($id);
        if (!$portada) {
            return false;
        }
        
        $nuevoEstado = $portada['activo'] ? 0 : 1;
        $stmt = db()->prepare('UPDATE portadas SET activo = ? WHERE id = ?');
        return $stmt->execute([$nuevoEstado, $id]);
    }
}