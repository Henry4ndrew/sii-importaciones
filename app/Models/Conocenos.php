<?php
require_once __DIR__ . '/../../config/database.php';

class Conocenos
{
    /**
     * Obtener los datos de Conócenos (siempre un solo registro)
     */
    public static function get(): ?array
    {
        $stmt = db()->query('SELECT * FROM conocenos LIMIT 1');
        $result = $stmt->fetch();
        return $result ?: null;
    }

    /**
     * Actualizar los datos de Conócenos
     */
    public static function update(array $data): bool
    {
        $sql = 'UPDATE conocenos SET 
            encabezado_titulo = ?,
            encabezado_descripcion = ?,
            encabezado_imagen = ?,
            mision_texto = ?,
            mision_imagen = ?,
            vision_texto = ?,
            vision_imagen = ?,
            historia_texto = ?,
            historia_imagen = ?
        ';
        $stmt = db()->prepare($sql);
        return $stmt->execute([
            $data['encabezado_titulo'],
            $data['encabezado_descripcion'] ?? null,
            $data['encabezado_imagen'] ?? null,
            $data['mision_texto'] ?? null,
            $data['mision_imagen'] ?? null,
            $data['vision_texto'] ?? null,
            $data['vision_imagen'] ?? null,
            $data['historia_texto'] ?? null,
            $data['historia_imagen'] ?? null
        ]);
    }

    /**
     * Obtener el equipo
     */
    public static function getEquipo(): array
    {
        $stmt = db()->query('SELECT * FROM conocenos_equipo ORDER BY orden ASC');
        return $stmt->fetchAll();
    }

    /**
     * Agregar miembro del equipo
     */
    public static function addEquipo(array $data): int
    {
        // Obtener el último orden
        $stmt = db()->query('SELECT MAX(orden) as max_orden FROM conocenos_equipo');
        $result = $stmt->fetch();
        $orden = (int) ($result['max_orden'] ?? 0) + 1;

        $stmt = db()->prepare('
            INSERT INTO conocenos_equipo (nombre, cargo, imagen, orden) 
            VALUES (?, ?, ?, ?)
        ');
        $stmt->execute([$data['nombre'], $data['cargo'], $data['imagen'] ?? null, $orden]);
        return (int) db()->lastInsertId();
    }

    /**
     * Actualizar miembro del equipo
     */
    public static function updateEquipo(int $id, array $data): bool
    {
        $stmt = db()->prepare('
            UPDATE conocenos_equipo SET 
                nombre = ?,
                cargo = ?,
                imagen = ?
            WHERE id = ?
        ');
        return $stmt->execute([$data['nombre'], $data['cargo'], $data['imagen'] ?? null, $id]);
    }

    /**
     * Eliminar miembro del equipo
     */
    public static function deleteEquipo(int $id): bool
    {
        $stmt = db()->prepare('DELETE FROM conocenos_equipo WHERE id = ?');
        return $stmt->execute([$id]);
    }

    /**
     * Obtener miembro del equipo por ID
     */
    public static function getEquipoById(int $id): ?array
    {
        $stmt = db()->prepare('SELECT * FROM conocenos_equipo WHERE id = ?');
        $stmt->execute([$id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    /**
     * Reordenar equipo
     */
    public static function reordenarEquipo(): void
    {
        $stmt = db()->query('SELECT id FROM conocenos_equipo ORDER BY orden ASC');
        $miembros = $stmt->fetchAll();
        
        $nuevoOrden = 1;
        foreach ($miembros as $miembro) {
            $update = db()->prepare('UPDATE conocenos_equipo SET orden = ? WHERE id = ?');
            $update->execute([$nuevoOrden, $miembro['id']]);
            $nuevoOrden++;
        }
    }
}