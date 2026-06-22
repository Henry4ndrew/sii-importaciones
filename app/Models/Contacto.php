<?php
require_once __DIR__ . '/../../config/database.php';

class Contacto
{
    /**
     * Obtener todos los contactos
     */
    public static function getAll(): array
    {
        $stmt = db()->query('SELECT * FROM contactos ORDER BY orden ASC, created_at DESC');
        return $stmt->fetchAll();
    }

    /**
     * Obtener contacto por ID
     */
    public static function getById(int $id): ?array
    {
        $stmt = db()->prepare('SELECT * FROM contactos WHERE id = ?');
        $stmt->execute([$id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    /**
     * Obtener contactos activos
     */
    public static function getActivos(): array
    {
        $stmt = db()->query('SELECT * FROM contactos WHERE activo = 1 ORDER BY orden ASC');
        return $stmt->fetchAll();
    }

    /**
     * Obtener el último orden
     */
    public static function getUltimoOrden(): int
    {
        $stmt = db()->query('SELECT MAX(orden) as max_orden FROM contactos');
        $result = $stmt->fetch();
        return (int) ($result['max_orden'] ?? 0) + 1;
    }

    /**
     * Verificar si un orden ya existe (excepto un ID específico)
     */
    public static function ordenExiste(int $orden, ?int $excluirId = null): bool
    {
        $sql = 'SELECT COUNT(*) as total FROM contactos WHERE orden = ?';
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
     * Reordenar contactos
     */
    public static function reordenar(): void
    {
        $stmt = db()->query('SELECT id FROM contactos ORDER BY orden ASC, created_at ASC');
        $contactos = $stmt->fetchAll();
        
        $nuevoOrden = 1;
        foreach ($contactos as $contacto) {
            $update = db()->prepare('UPDATE contactos SET orden = ? WHERE id = ?');
            $update->execute([$nuevoOrden, $contacto['id']]);
            $nuevoOrden++;
        }
    }

    /**
     * Crear contacto
     */
    public static function create(array $data): int
    {
        if (!isset($data['orden']) || $data['orden'] === 0) {
            $data['orden'] = self::getUltimoOrden();
        }
        
        $stmt = db()->prepare('
            INSERT INTO contactos (ciudad, telefono, correo, horarios, imagen, orden, activo) 
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ');
        $stmt->execute([
            $data['ciudad'],
            $data['telefono'],
            $data['correo'],
            $data['horarios'] ?? null,
            $data['imagen'] ?? null,
            $data['orden'],
            $data['activo'] ?? 1
        ]);
        return (int) db()->lastInsertId();
    }

    /**
     * Actualizar contacto
     */
    public static function update(int $id, array $data): bool
    {
        $stmt = db()->prepare('
            UPDATE contactos SET 
                ciudad = ?, 
                telefono = ?, 
                correo = ?, 
                horarios = ?, 
                imagen = ?, 
                orden = ?, 
                activo = ? 
            WHERE id = ?
        ');
        return $stmt->execute([
            $data['ciudad'],
            $data['telefono'],
            $data['correo'],
            $data['horarios'] ?? null,
            $data['imagen'] ?? null,
            $data['orden'] ?? 0,
            $data['activo'] ?? 1,
            $id
        ]);
    }

    /**
     * Eliminar contacto
     */
    public static function delete(int $id): bool
    {
        $stmt = db()->prepare('DELETE FROM contactos WHERE id = ?');
        return $stmt->execute([$id]);
    }

    /**
     * Cambiar estado (activo/inactivo)
     */
    public static function toggleEstado(int $id): bool
    {
        $contacto = self::getById($id);
        if (!$contacto) {
            return false;
        }
        
        $nuevoEstado = $contacto['activo'] ? 0 : 1;
        $stmt = db()->prepare('UPDATE contactos SET activo = ? WHERE id = ?');
        return $stmt->execute([$nuevoEstado, $id]);
    }
}