<?php
require_once __DIR__ . '/../Helpers/UploadHelper.php';

class PortadaController
{
    /**
     * Listar portadas
     */
    public function index(): void
    {
        if (!isset($_SESSION['administrador'])) {
            header('Location: ' . url('auth/login'));
            exit;
        }

        $portadas = Portada::getAll();
        $totalAdministradores = $this->getTotalAdministradores();

        adminView('admin/portadas/index', [
            'titulo' => 'Gestión de Portadas',
            'admin' => $_SESSION['administrador'],
            'portadas' => $portadas,
            'totalAdministradores' => $totalAdministradores,
            'activePage' => 'portadas'
        ]);
    }

    /**
     * Ver detalle de una portada
     */
    public function ver(): void
    {
        if (!isset($_SESSION['administrador'])) {
            header('Location: ' . url('auth/login'));
            exit;
        }

        $id = (int) ($_GET['id'] ?? 0);
        $portada = Portada::getById($id);

        if (!$portada) {
            flash('error', 'Portada no encontrada.');
            header('Location: ' . url('admin/portadas'));
            exit;
        }

        $totalAdministradores = $this->getTotalAdministradores();

        adminView('admin/portadas/ver', [
            'titulo' => 'Ver Portada',
            'admin' => $_SESSION['administrador'],
            'portada' => $portada,
            'totalAdministradores' => $totalAdministradores,
            'activePage' => 'portadas'
        ]);
    }

    /**
     * Mostrar formulario para crear
     */
    public function crear(): void
    {
        if (!isset($_SESSION['administrador'])) {
            header('Location: ' . url('auth/login'));
            exit;
        }

        $ultimoOrden = Portada::getUltimoOrden();
        $totalAdministradores = $this->getTotalAdministradores();

        adminView('admin/portadas/crear', [
            'titulo' => 'Crear Portada',
            'admin' => $_SESSION['administrador'],
            'ultimoOrden' => $ultimoOrden,
            'totalAdministradores' => $totalAdministradores,
            'activePage' => 'portadas'
        ]);
    }

    /**
     * Guardar nueva portada
     */
    public function store(): void
    {
        if (!isset($_SESSION['administrador'])) {
            header('Location: ' . url('auth/login'));
            exit;
        }

        $titulo = trim($_POST['titulo'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');
        $orden = isset($_POST['orden']) && $_POST['orden'] !== '' ? (int) $_POST['orden'] : 0;
        $activo = isset($_POST['activo']) ? 1 : 0;

        if (empty($titulo)) {
            flash('error', 'El título es obligatorio.');
            header('Location: ' . url('admin/portadas/crear'));
            exit;
        }

        if (!isset($_FILES['imagen']) || $_FILES['imagen']['error'] !== UPLOAD_ERR_OK) {
            flash('error', 'Debes seleccionar una imagen para la portada.');
            header('Location: ' . url('admin/portadas/crear'));
            exit;
        }

        // Validar orden (si se proporcionó)
        if ($orden > 0 && Portada::ordenExiste($orden)) {
            flash('error', 'El número de orden "' . $orden . '" ya está en uso. El sistema asignará automáticamente el siguiente disponible.');
            header('Location: ' . url('admin/portadas/crear'));
            exit;
        }

        $rutaImagen = UploadHelper::uploadImage($_FILES['imagen'], 'portadas');
        if (!$rutaImagen) {
            flash('error', 'Error al subir la imagen. Verifica que sea una imagen válida (JPG, PNG, WEBP, AVIF, GIF) y no pese más de 5MB.');
            header('Location: ' . url('admin/portadas/crear'));
            exit;
        }

        try {
            $data = [
                'titulo' => $titulo,
                'descripcion' => $descripcion,
                'ruta_imagen' => $rutaImagen,
                'activo' => $activo
            ];
            
            if ($orden > 0) {
                $data['orden'] = $orden;
            }
            
            Portada::create($data);
            Portada::reordenar();
            
            flash('exito', 'Portada creada exitosamente.');
            header('Location: ' . url('admin/portadas'));
        } catch (PDOException $e) {
            UploadHelper::deleteImage($rutaImagen);
            flash('error', 'Error al crear: ' . $e->getMessage());
            header('Location: ' . url('admin/portadas/crear'));
        }
        exit;
    }

    /**
     * Mostrar formulario para editar
     */
    public function editar(): void
    {
        if (!isset($_SESSION['administrador'])) {
            header('Location: ' . url('auth/login'));
            exit;
        }

        $id = (int) ($_GET['id'] ?? 0);
        $portada = Portada::getById($id);

        if (!$portada) {
            flash('error', 'Portada no encontrada.');
            header('Location: ' . url('admin/portadas'));
            exit;
        }

        $totalAdministradores = $this->getTotalAdministradores();

        adminView('admin/portadas/editar', [
            'titulo' => 'Editar Portada',
            'admin' => $_SESSION['administrador'],
            'portada' => $portada,
            'totalAdministradores' => $totalAdministradores,
            'activePage' => 'portadas'
        ]);
    }

    /**
     * Actualizar portada (permite intercambiar órdenes)
     */
    public function update(): void
    {
        if (!isset($_SESSION['administrador'])) {
            header('Location: ' . url('auth/login'));
            exit;
        }

        $id = (int) ($_POST['id'] ?? 0);
        $titulo = trim($_POST['titulo'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');
        $nuevoOrden = (int) ($_POST['orden'] ?? 0);
        $activo = isset($_POST['activo']) ? 1 : 0;

        if ($id <= 0 || empty($titulo)) {
            flash('error', 'El título es obligatorio.');
            header('Location: ' . url('admin/portadas/editar') . '?id=' . $id);
            exit;
        }

        // Obtener portada actual
        $portada = Portada::getById($id);
        if (!$portada) {
            flash('error', 'Portada no encontrada.');
            header('Location: ' . url('admin/portadas'));
            exit;
        }

        $ordenActual = (int) $portada['orden'];

        // Si el orden cambió
        if ($nuevoOrden > 0 && $nuevoOrden !== $ordenActual) {
            // Buscar la portada que tiene el orden destino
            $stmt = db()->prepare('SELECT id FROM portadas WHERE orden = ? AND id != ?');
            $stmt->execute([$nuevoOrden, $id]);
            $portadaDestino = $stmt->fetch();

            if ($portadaDestino) {
                // Intercambiar órdenes: la portada destino toma el orden actual
                $stmt = db()->prepare('UPDATE portadas SET orden = ? WHERE id = ?');
                $stmt->execute([$ordenActual, $portadaDestino['id']]);
            }
        }

        $rutaImagen = $portada['ruta_imagen'];

        // Si se subió una nueva imagen
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            UploadHelper::deleteImage($portada['ruta_imagen']);
            
            $nuevaRuta = UploadHelper::uploadImage($_FILES['imagen'], 'portadas');
            if (!$nuevaRuta) {
                flash('error', 'Error al subir la nueva imagen. Verifica que sea una imagen válida (JPG, PNG, WEBP, AVIF, GIF) y no pese más de 5MB.');
                header('Location: ' . url('admin/portadas/editar') . '?id=' . $id);
                exit;
            }
            $rutaImagen = $nuevaRuta;
        }

        try {
            // Actualizar la portada con el nuevo orden (si se especificó)
            $ordenFinal = $nuevoOrden > 0 ? $nuevoOrden : $ordenActual;
            
            Portada::update($id, [
                'titulo' => $titulo,
                'descripcion' => $descripcion,
                'ruta_imagen' => $rutaImagen,
                'orden' => $ordenFinal,
                'activo' => $activo
            ]);
            
            // Reordenar para mantener secuencia consecutiva
            Portada::reordenar();
            
            flash('exito', 'Portada actualizada exitosamente.');
            header('Location: ' . url('admin/portadas'));
        } catch (PDOException $e) {
            flash('error', 'Error al actualizar: ' . $e->getMessage());
            header('Location: ' . url('admin/portadas/editar') . '?id=' . $id);
        }
        exit;
    }

    /**
     * Eliminar portada
     */
    public function delete(): void
    {
        if (!isset($_SESSION['administrador'])) {
            header('Location: ' . url('auth/login'));
            exit;
        }

        $id = (int) ($_GET['id'] ?? 0);
        if ($id <= 0) {
            flash('error', 'ID inválido.');
            header('Location: ' . url('admin/portadas'));
            exit;
        }

        $portada = Portada::getById($id);
        if ($portada) {
            UploadHelper::deleteImage($portada['ruta_imagen']);
        }

        try {
            Portada::delete($id);
            Portada::reordenar();
            flash('exito', 'Portada eliminada exitosamente.');
        } catch (PDOException $e) {
            flash('error', 'Error al eliminar: ' . $e->getMessage());
        }
        header('Location: ' . url('admin/portadas'));
        exit;
    }

    /**
     * Cambiar estado (activo/inactivo)
     */
    public function toggle(): void
    {
        if (!isset($_SESSION['administrador'])) {
            header('Location: ' . url('auth/login'));
            exit;
        }

        $id = (int) ($_GET['id'] ?? 0);
        if ($id <= 0) {
            flash('error', 'ID inválido.');
            header('Location: ' . url('admin/portadas'));
            exit;
        }

        try {
            Portada::toggleEstado($id);
            flash('exito', 'Estado de la portada actualizado.');
        } catch (PDOException $e) {
            flash('error', 'Error al cambiar estado: ' . $e->getMessage());
        }
        header('Location: ' . url('admin/portadas'));
        exit;
    }

    /**
     * Verificar si un orden existe (para AJAX)
     */
    public function verificarOrden(): void
    {
        if (!isset($_SESSION['administrador'])) {
            http_response_code(403);
            echo json_encode(['error' => 'No autorizado']);
            exit;
        }

        $orden = (int) ($_GET['orden'] ?? 0);
        $excluirId = isset($_GET['excluir']) ? (int) $_GET['excluir'] : null;

        if ($orden <= 0) {
            echo json_encode(['existe' => false]);
            exit;
        }

        $existe = Portada::ordenExiste($orden, $excluirId);
        echo json_encode(['existe' => $existe]);
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