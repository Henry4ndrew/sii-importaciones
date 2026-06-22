<?php

class PublicacionController
{
    /**
     * Listar publicaciones con filtros
     */
    public function index(): void
    {
        if (!isset($_SESSION['administrador'])) {
            header('Location: ' . url('auth/login'));
            exit;
        }

        // Obtener filtros
        $filtroVotos = $_GET['votos'] ?? 'todos';
        $filtroFecha = $_GET['fecha'] ?? 'todos';
        $busqueda = trim($_GET['buscar'] ?? '');

        // Construir consulta base
        $sql = "
            SELECT p.*, u.email AS publicado_por,
                   (SELECT COUNT(*) FROM votos v WHERE v.producto_id = p.id) AS total_votos
            FROM productos p
            JOIN usuarios u ON u.id = p.usuario_id
            WHERE 1=1
        ";
        $params = [];

        // Filtro por votos
        if ($filtroVotos !== 'todos') {
            switch ($filtroVotos) {
                case '0':
                    $sql .= " AND (SELECT COUNT(*) FROM votos v WHERE v.producto_id = p.id) = 0";
                    break;
                case '1-5':
                    $sql .= " AND (SELECT COUNT(*) FROM votos v WHERE v.producto_id = p.id) BETWEEN 1 AND 5";
                    break;
                case '6-10':
                    $sql .= " AND (SELECT COUNT(*) FROM votos v WHERE v.producto_id = p.id) BETWEEN 6 AND 10";
                    break;
                case '10+':
                    $sql .= " AND (SELECT COUNT(*) FROM votos v WHERE v.producto_id = p.id) > 10";
                    break;
            }
        }

        // Filtro por fecha
        if ($filtroFecha !== 'todos') {
            switch ($filtroFecha) {
                case 'hoy':
                    $sql .= " AND DATE(p.created_at) = CURDATE()";
                    break;
                case 'semana':
                    $sql .= " AND p.created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
                    break;
                case 'mes':
                    $sql .= " AND p.created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)";
                    break;
            }
        }

        // Búsqueda por título o email
        if (!empty($busqueda)) {
            $sql .= " AND (p.nombre LIKE ? OR u.email LIKE ?)";
            $params[] = "%$busqueda%";
            $params[] = "%$busqueda%";
        }

        // Ordenar por fecha (más recientes primero)
        $sql .= " ORDER BY p.created_at DESC";

        // Ejecutar consulta
        $stmt = db()->prepare($sql);
        $stmt->execute($params);
        $publicaciones = $stmt->fetchAll();

        $totalAdministradores = $this->getTotalAdministradores();

        adminView('admin/publicaciones/index', [
            'titulo' => 'Gestión de Publicaciones',
            'admin' => $_SESSION['administrador'],
            'publicaciones' => $publicaciones,
            'totalAdministradores' => $totalAdministradores,
            'activePage' => 'publicaciones',
            'filtroVotos' => $filtroVotos,
            'filtroFecha' => $filtroFecha,
            'busqueda' => $busqueda
        ]);
    }

    /**
     * Ver detalle de una publicación
     */
    public function ver(): void
    {
        if (!isset($_SESSION['administrador'])) {
            header('Location: ' . url('auth/login'));
            exit;
        }

        $id = (int) ($_GET['id'] ?? 0);
        if ($id <= 0) {
            flash('error', 'ID de publicación inválido.');
            header('Location: ' . url('admin/publicaciones'));
            exit;
        }

        // Obtener producto con detalles
        $sql = "
            SELECT p.*, u.email AS publicado_por,
                   (SELECT COUNT(*) FROM votos v WHERE v.producto_id = p.id) AS total_votos
            FROM productos p
            JOIN usuarios u ON u.id = p.usuario_id
            WHERE p.id = ?
        ";
        $stmt = db()->prepare($sql);
        $stmt->execute([$id]);
        $publicacion = $stmt->fetch();

        if (!$publicacion) {
            flash('error', 'Publicación no encontrada.');
            header('Location: ' . url('admin/publicaciones'));
            exit;
        }

        // Obtener usuarios que votaron
        $sqlVotos = "
            SELECT u.id, u.email, v.created_at as fecha_voto
            FROM votos v
            JOIN usuarios u ON u.id = v.usuario_id
            WHERE v.producto_id = ?
            ORDER BY v.created_at DESC
        ";
        $stmt = db()->prepare($sqlVotos);
        $stmt->execute([$id]);
        $votantes = $stmt->fetchAll();

        $totalAdministradores = $this->getTotalAdministradores();

        adminView('admin/publicaciones/ver', [
            'titulo' => 'Ver Publicación',
            'admin' => $_SESSION['administrador'],
            'publicacion' => $publicacion,
            'votantes' => $votantes,
            'totalAdministradores' => $totalAdministradores,
            'activePage' => 'publicaciones'
        ]);
    }

    /**
     * Eliminar publicación
     */
    public function delete(): void
    {
        if (!isset($_SESSION['administrador'])) {
            header('Location: ' . url('auth/login'));
            exit;
        }

        $id = (int) ($_GET['id'] ?? 0);
        if ($id <= 0) {
            flash('error', 'ID de publicación inválido.');
            header('Location: ' . url('admin/publicaciones'));
            exit;
        }

        try {
            // Primero eliminar votos asociados (por clave foránea, se eliminan automáticamente)
            // Luego eliminar el producto
            $stmt = db()->prepare('DELETE FROM productos WHERE id = ?');
            $stmt->execute([$id]);
            
            flash('exito', 'Publicación eliminada exitosamente.');
        } catch (PDOException $e) {
            flash('error', 'Error al eliminar la publicación: ' . $e->getMessage());
        }
        header('Location: ' . url('admin/publicaciones'));
        exit;
    }

    /**
     * Obtener total de administradores
     */
    private function getTotalAdministradores(): int
    {
        $stmt = db()->query('SELECT COUNT(*) as total FROM administradores');
        $result = $stmt->fetch();
        return (int) $result['total'];
    }
}