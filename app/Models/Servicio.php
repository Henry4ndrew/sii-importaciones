<?php
require_once __DIR__ . '/../../config/database.php';

class Servicio
{
    /**
     * Obtener todos los servicios
     */
    public static function getAll(): array
    {
        $stmt = db()->query('SELECT * FROM servicios ORDER BY orden ASC, created_at DESC');
        return $stmt->fetchAll();
    }

    /**
     * Obtener servicio por ID con sus subsecciones
     */
    public static function getById(int $id): ?array
    {
        $stmt = db()->prepare('SELECT * FROM servicios WHERE id = ?');
        $stmt->execute([$id]);
        $servicio = $stmt->fetch();
        
        if ($servicio) {
            $servicio['subsecciones'] = self::getSubsecciones($id);
        }
        
        return $servicio ?: null;
    }

    /**
     * Obtener subsecciones de un servicio
     */
    public static function getSubsecciones(int $servicioId): array
    {
        $stmt = db()->prepare('SELECT * FROM servicio_subsecciones WHERE servicio_id = ? ORDER BY orden ASC');
        $stmt->execute([$servicioId]);
        return $stmt->fetchAll();
    }

    /**
     * Obtener servicios activos
     */
    public static function getActivos(): array
    {
        $stmt = db()->query('SELECT * FROM servicios WHERE activo = 1 ORDER BY orden ASC');
        $servicios = $stmt->fetchAll();
        
        foreach ($servicios as &$servicio) {
            $servicio['subsecciones'] = self::getSubsecciones($servicio['id']);
        }
        
        return $servicios;
    }

    /**
     * Obtener el último orden
     */
    public static function getUltimoOrden(): int
    {
        $stmt = db()->query('SELECT MAX(orden) as max_orden FROM servicios');
        $result = $stmt->fetch();
        return (int) ($result['max_orden'] ?? 0) + 1;
    }

    /**
     * Verificar si un orden ya existe
     */
    public static function ordenExiste(int $orden, ?int $excluirId = null): bool
    {
        $sql = 'SELECT COUNT(*) as total FROM servicios WHERE orden = ?';
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
     * Reordenar servicios
     */
    public static function reordenar(): void
    {
        $stmt = db()->query('SELECT id FROM servicios ORDER BY orden ASC, created_at ASC');
        $servicios = $stmt->fetchAll();
        
        $nuevoOrden = 1;
        foreach ($servicios as $servicio) {
            $update = db()->prepare('UPDATE servicios SET orden = ? WHERE id = ?');
            $update->execute([$nuevoOrden, $servicio['id']]);
            $nuevoOrden++;
        }
    }

    /**
     * Crear servicio
     */
    public static function create(array $data): int
    {
        if (!isset($data['orden']) || $data['orden'] === 0) {
            $data['orden'] = self::getUltimoOrden();
        }
        
        $stmt = db()->prepare('
            INSERT INTO servicios (titulo, imagen, orden, activo) 
            VALUES (?, ?, ?, ?)
        ');
        $stmt->execute([
            $data['titulo'],
            $data['imagen'] ?? null,
            $data['orden'],
            $data['activo'] ?? 1
        ]);
        return (int) db()->lastInsertId();
    }

    /**
     * Actualizar servicio
     */
    public static function update(int $id, array $data): bool
    {
        $stmt = db()->prepare('
            UPDATE servicios SET 
                titulo = ?, 
                imagen = ?, 
                orden = ?, 
                activo = ? 
            WHERE id = ?
        ');
        return $stmt->execute([
            $data['titulo'],
            $data['imagen'] ?? null,
            $data['orden'] ?? 0,
            $data['activo'] ?? 1,
            $id
        ]);
    }

    /**
     * Eliminar servicio (y sus subsecciones por CASCADE)
     */
    public static function delete(int $id): bool
    {
        $stmt = db()->prepare('DELETE FROM servicios WHERE id = ?');
        return $stmt->execute([$id]);
    }

    /**
     * Cambiar estado (activo/inactivo)
     */
    public static function toggleEstado(int $id): bool
    {
        $servicio = self::getById($id);
        if (!$servicio) {
            return false;
        }
        
        $nuevoEstado = $servicio['activo'] ? 0 : 1;
        $stmt = db()->prepare('UPDATE servicios SET activo = ? WHERE id = ?');
        return $stmt->execute([$nuevoEstado, $id]);
    }
}