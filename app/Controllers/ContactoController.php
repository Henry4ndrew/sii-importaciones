<?php
require_once __DIR__ . '/../Helpers/UploadHelper.php';

class ContactoController
{
    /**
     * Listar contactos
     */
    public function index(): void
    {
        if (!isset($_SESSION['administrador'])) {
            header('Location: ' . url('auth/login'));
            exit;
        }

        $contactos = Contacto::getAll();
        $totalAdministradores = $this->getTotalAdministradores();

        adminView('admin/contactos/index', [
            'titulo' => 'Gestión de Contactos',
            'admin' => $_SESSION['administrador'],
            'contactos' => $contactos,
            'totalAdministradores' => $totalAdministradores,
            'activePage' => 'contactos'
        ]);
    }

    /**
     * Ver detalle de un contacto
     */
    public function ver(): void
    {
        if (!isset($_SESSION['administrador'])) {
            header('Location: ' . url('auth/login'));
            exit;
        }

        $id = (int) ($_GET['id'] ?? 0);
        $contacto = Contacto::getById($id);

        if (!$contacto) {
            flash('error', 'Contacto no encontrado.');
            header('Location: ' . url('admin/contactos'));
            exit;
        }

        $totalAdministradores = $this->getTotalAdministradores();

        adminView('admin/contactos/ver', [
            'titulo' => 'Ver Contacto',
            'admin' => $_SESSION['administrador'],
            'contacto' => $contacto,
            'totalAdministradores' => $totalAdministradores,
            'activePage' => 'contactos'
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

        $ultimoOrden = Contacto::getUltimoOrden();
        $totalAdministradores = $this->getTotalAdministradores();

        adminView('admin/contactos/crear', [
            'titulo' => 'Crear Contacto',
            'admin' => $_SESSION['administrador'],
            'ultimoOrden' => $ultimoOrden,
            'totalAdministradores' => $totalAdministradores,
            'activePage' => 'contactos'
        ]);
    }

    /**
     * Guardar nuevo contacto
     */
    public function store(): void
    {
        if (!isset($_SESSION['administrador'])) {
            header('Location: ' . url('auth/login'));
            exit;
        }

        $ciudad = trim($_POST['ciudad'] ?? '');
        $telefono = trim($_POST['telefono'] ?? '');
        $correo = trim($_POST['correo'] ?? '');
        $horarios = trim($_POST['horarios'] ?? '');
        $orden = isset($_POST['orden']) && $_POST['orden'] !== '' ? (int) $_POST['orden'] : 0;
        $activo = isset($_POST['activo']) ? 1 : 0;

        if (empty($ciudad) || empty($telefono) || empty($correo)) {
            flash('error', 'Los campos ciudad, teléfono y correo son obligatorios.');
            header('Location: ' . url('admin/contactos/crear'));
            exit;
        }

        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            flash('error', 'Por favor, ingresa un correo electrónico válido.');
            header('Location: ' . url('admin/contactos/crear'));
            exit;
        }

        // Validar orden
        if ($orden > 0 && Contacto::ordenExiste($orden)) {
            flash('error', 'El número de orden "' . $orden . '" ya está en uso.');
            header('Location: ' . url('admin/contactos/crear'));
            exit;
        }

        // Subir imagen (opcional)
        $rutaImagen = null;
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $rutaImagen = UploadHelper::uploadImage($_FILES['imagen'], 'contactos');
            if (!$rutaImagen) {
                flash('error', 'Error al subir la imagen. Verifica que sea una imagen válida (JPG, PNG, WEBP, AVIF, GIF) y no pese más de 5MB.');
                header('Location: ' . url('admin/contactos/crear'));
                exit;
            }
        }

        try {
            $data = [
                'ciudad' => $ciudad,
                'telefono' => $telefono,
                'correo' => $correo,
                'horarios' => $horarios,
                'imagen' => $rutaImagen,
                'activo' => $activo
            ];
            
            if ($orden > 0) {
                $data['orden'] = $orden;
            }
            
            Contacto::create($data);
            Contacto::reordenar();
            
            flash('exito', 'Contacto creado exitosamente.');
            header('Location: ' . url('admin/contactos'));
        } catch (PDOException $e) {
            if ($rutaImagen) {
                UploadHelper::deleteImage($rutaImagen);
            }
            flash('error', 'Error al crear: ' . $e->getMessage());
            header('Location: ' . url('admin/contactos/crear'));
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
        $contacto = Contacto::getById($id);

        if (!$contacto) {
            flash('error', 'Contacto no encontrado.');
            header('Location: ' . url('admin/contactos'));
            exit;
        }

        $totalAdministradores = $this->getTotalAdministradores();

        adminView('admin/contactos/editar', [
            'titulo' => 'Editar Contacto',
            'admin' => $_SESSION['administrador'],
            'contacto' => $contacto,
            'totalAdministradores' => $totalAdministradores,
            'activePage' => 'contactos'
        ]);
    }

    /**
     * Actualizar contacto
     */
    public function update(): void
    {
        if (!isset($_SESSION['administrador'])) {
            header('Location: ' . url('auth/login'));
            exit;
        }

        $id = (int) ($_POST['id'] ?? 0);
        $ciudad = trim($_POST['ciudad'] ?? '');
        $telefono = trim($_POST['telefono'] ?? '');
        $correo = trim($_POST['correo'] ?? '');
        $horarios = trim($_POST['horarios'] ?? '');
        $nuevoOrden = (int) ($_POST['orden'] ?? 0);
        $activo = isset($_POST['activo']) ? 1 : 0;

        if ($id <= 0 || empty($ciudad) || empty($telefono) || empty($correo)) {
            flash('error', 'Los campos ciudad, teléfono y correo son obligatorios.');
            header('Location: ' . url('admin/contactos/editar') . '?id=' . $id);
            exit;
        }

        if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
            flash('error', 'Por favor, ingresa un correo electrónico válido.');
            header('Location: ' . url('admin/contactos/editar') . '?id=' . $id);
            exit;
        }

        // Obtener contacto actual
        $contacto = Contacto::getById($id);
        if (!$contacto) {
            flash('error', 'Contacto no encontrado.');
            header('Location: ' . url('admin/contactos'));
            exit;
        }

        // Intercambiar órdenes si cambió
        $ordenActual = (int) $contacto['orden'];
        if ($nuevoOrden > 0 && $nuevoOrden !== $ordenActual) {
            $stmt = db()->prepare('SELECT id FROM contactos WHERE orden = ? AND id != ?');
            $stmt->execute([$nuevoOrden, $id]);
            $contactoDestino = $stmt->fetch();

            if ($contactoDestino) {
                $stmt = db()->prepare('UPDATE contactos SET orden = ? WHERE id = ?');
                $stmt->execute([$ordenActual, $contactoDestino['id']]);
            }
        }

        // Subir nueva imagen (opcional)
        $rutaImagen = $contacto['imagen'];
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            if ($contacto['imagen']) {
                UploadHelper::deleteImage($contacto['imagen']);
            }
            
            $nuevaRuta = UploadHelper::uploadImage($_FILES['imagen'], 'contactos');
            if (!$nuevaRuta) {
                flash('error', 'Error al subir la nueva imagen.');
                header('Location: ' . url('admin/contactos/editar') . '?id=' . $id);
                exit;
            }
            $rutaImagen = $nuevaRuta;
        }

        try {
            $ordenFinal = $nuevoOrden > 0 ? $nuevoOrden : $ordenActual;
            
            Contacto::update($id, [
                'ciudad' => $ciudad,
                'telefono' => $telefono,
                'correo' => $correo,
                'horarios' => $horarios,
                'imagen' => $rutaImagen,
                'orden' => $ordenFinal,
                'activo' => $activo
            ]);
            
            Contacto::reordenar();
            
            flash('exito', 'Contacto actualizado exitosamente.');
            header('Location: ' . url('admin/contactos'));
        } catch (PDOException $e) {
            flash('error', 'Error al actualizar: ' . $e->getMessage());
            header('Location: ' . url('admin/contactos/editar') . '?id=' . $id);
        }
        exit;
    }

    /**
     * Eliminar contacto
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
            header('Location: ' . url('admin/contactos'));
            exit;
        }

        $contacto = Contacto::getById($id);
        if ($contacto && $contacto['imagen']) {
            UploadHelper::deleteImage($contacto['imagen']);
        }

        try {
            Contacto::delete($id);
            Contacto::reordenar();
            flash('exito', 'Contacto eliminado exitosamente.');
        } catch (PDOException $e) {
            flash('error', 'Error al eliminar: ' . $e->getMessage());
        }
        header('Location: ' . url('admin/contactos'));
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
            header('Location: ' . url('admin/contactos'));
            exit;
        }

        try {
            Contacto::toggleEstado($id);
            flash('exito', 'Estado del contacto actualizado.');
        } catch (PDOException $e) {
            flash('error', 'Error al cambiar estado: ' . $e->getMessage());
        }
        header('Location: ' . url('admin/contactos'));
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

        $existe = Contacto::ordenExiste($orden, $excluirId);
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