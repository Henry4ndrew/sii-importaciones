<?php
require_once __DIR__ . '/../Helpers/UploadHelper.php';

class ServicioController
{
    /**
     * Listar servicios
     */
    public function index(): void
    {
        if (!isset($_SESSION['administrador'])) {
            header('Location: ' . url('auth/login'));
            exit;
        }

        $servicios = Servicio::getAll();
        $totalAdministradores = $this->getTotalAdministradores();

        adminView('admin/servicios/index', [
            'titulo' => 'Gestión de Servicios',
            'admin' => $_SESSION['administrador'],
            'servicios' => $servicios,
            'totalAdministradores' => $totalAdministradores,
            'activePage' => 'servicios'
        ]);
    }

    /**
     * Ver detalle de un servicio
     */
    public function ver(): void
    {
        if (!isset($_SESSION['administrador'])) {
            header('Location: ' . url('auth/login'));
            exit;
        }

        $id = (int) ($_GET['id'] ?? 0);
        $servicio = Servicio::getById($id);

        if (!$servicio) {
            flash('error', 'Servicio no encontrado.');
            header('Location: ' . url('admin/servicios'));
            exit;
        }

        $totalAdministradores = $this->getTotalAdministradores();

        adminView('admin/servicios/ver', [
            'titulo' => 'Ver Servicio',
            'admin' => $_SESSION['administrador'],
            'servicio' => $servicio,
            'totalAdministradores' => $totalAdministradores,
            'activePage' => 'servicios'
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

        $ultimoOrden = Servicio::getUltimoOrden();
        $totalAdministradores = $this->getTotalAdministradores();

        adminView('admin/servicios/crear', [
            'titulo' => 'Crear Servicio',
            'admin' => $_SESSION['administrador'],
            'ultimoOrden' => $ultimoOrden,
            'totalAdministradores' => $totalAdministradores,
            'activePage' => 'servicios'
        ]);
    }

 /**
 * Guardar nuevo servicio
 */
public function store(): void
{
    if (!isset($_SESSION['administrador'])) {
        header('Location: ' . url('auth/login'));
        exit;
    }

    $titulo = trim($_POST['titulo'] ?? '');
    $orden = isset($_POST['orden']) && $_POST['orden'] !== '' ? (int) $_POST['orden'] : 0;
    $activo = isset($_POST['activo']) ? 1 : 0;
    
    // Subsecciones desde el JSON (enviado por el formulario)
    $subsecciones = [];
    if (isset($_POST['subsecciones']) && !empty($_POST['subsecciones'])) {
        $subsecciones = json_decode($_POST['subsecciones'], true);
    }

    if (empty($titulo)) {
        flash('error', 'El título es obligatorio.');
        header('Location: ' . url('admin/servicios/crear'));
        exit;
    }

    // Validar orden
    if ($orden > 0 && Servicio::ordenExiste($orden)) {
        flash('error', 'El número de orden "' . $orden . '" ya está en uso.');
        header('Location: ' . url('admin/servicios/crear'));
        exit;
    }

    // Subir imagen
    $rutaImagen = null;
    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $rutaImagen = UploadHelper::uploadImage($_FILES['imagen'], 'servicios');
        if (!$rutaImagen) {
            flash('error', 'Error al subir la imagen. Verifica que sea una imagen válida (JPG, PNG, WEBP, AVIF, GIF) y no pese más de 5MB.');
            header('Location: ' . url('admin/servicios/crear'));
            exit;
        }
    }

    try {
        $data = [
            'titulo' => $titulo,
            'imagen' => $rutaImagen,
            'activo' => $activo
        ];
        
        if ($orden > 0) {
            $data['orden'] = $orden;
        }
        
        $servicioId = Servicio::create($data);
        Servicio::reordenar();

        // ============================================
        // GUARDAR SUBSECCIONES - CORREGIDO
        // ============================================
        if (!empty($subsecciones) && is_array($subsecciones)) {
            // Filtrar subsecciones vacías
            $subseccionesFiltradas = array_filter($subsecciones, function($sub) {
                return !empty(trim($sub['subtitulo'] ?? '')) && !empty(trim($sub['descripcion'] ?? ''));
            });

            if (!empty($subseccionesFiltradas)) {
                $ordenSub = 1;
                foreach ($subseccionesFiltradas as $sub) {
                    $stmt = db()->prepare('
                        INSERT INTO servicio_subsecciones (servicio_id, subtitulo, descripcion, orden) 
                        VALUES (?, ?, ?, ?)
                    ');
                    $stmt->execute([$servicioId, trim($sub['subtitulo']), trim($sub['descripcion']), $ordenSub]);
                    $ordenSub++;
                }
            }
        }

        flash('exito', 'Servicio creado exitosamente.');
        header('Location: ' . url('admin/servicios'));
    } catch (PDOException $e) {
        if ($rutaImagen) {
            UploadHelper::deleteImage($rutaImagen);
        }
        flash('error', 'Error al crear: ' . $e->getMessage());
        header('Location: ' . url('admin/servicios/crear'));
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
        $servicio = Servicio::getById($id);

        if (!$servicio) {
            flash('error', 'Servicio no encontrado.');
            header('Location: ' . url('admin/servicios'));
            exit;
        }

        $totalAdministradores = $this->getTotalAdministradores();

        adminView('admin/servicios/editar', [
            'titulo' => 'Editar Servicio',
            'admin' => $_SESSION['administrador'],
            'servicio' => $servicio,
            'totalAdministradores' => $totalAdministradores,
            'activePage' => 'servicios'
        ]);
    }

    /**
     * Actualizar servicio
     */
    public function update(): void
    {
        if (!isset($_SESSION['administrador'])) {
            header('Location: ' . url('auth/login'));
            exit;
        }

        $id = (int) ($_POST['id'] ?? 0);
        $titulo = trim($_POST['titulo'] ?? '');
        $nuevoOrden = (int) ($_POST['orden'] ?? 0);
        $activo = isset($_POST['activo']) ? 1 : 0;
        
        // Subsecciones desde el JSON
        $subsecciones = isset($_POST['subsecciones']) ? json_decode($_POST['subsecciones'], true) : [];

        if ($id <= 0 || empty($titulo)) {
            flash('error', 'El título es obligatorio.');
            header('Location: ' . url('admin/servicios/editar') . '?id=' . $id);
            exit;
        }

        // Obtener servicio actual
        $servicio = Servicio::getById($id);
        if (!$servicio) {
            flash('error', 'Servicio no encontrado.');
            header('Location: ' . url('admin/servicios'));
            exit;
        }

        // Intercambiar órdenes si cambió
        $ordenActual = (int) $servicio['orden'];
        if ($nuevoOrden > 0 && $nuevoOrden !== $ordenActual) {
            $stmt = db()->prepare('SELECT id FROM servicios WHERE orden = ? AND id != ?');
            $stmt->execute([$nuevoOrden, $id]);
            $servicioDestino = $stmt->fetch();

            if ($servicioDestino) {
                $stmt = db()->prepare('UPDATE servicios SET orden = ? WHERE id = ?');
                $stmt->execute([$ordenActual, $servicioDestino['id']]);
            }
        }

        // Subir nueva imagen
        $rutaImagen = $servicio['imagen'];
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            if ($servicio['imagen']) {
                UploadHelper::deleteImage($servicio['imagen']);
            }
            
            $nuevaRuta = UploadHelper::uploadImage($_FILES['imagen'], 'servicios');
            if (!$nuevaRuta) {
                flash('error', 'Error al subir la nueva imagen.');
                header('Location: ' . url('admin/servicios/editar') . '?id=' . $id);
                exit;
            }
            $rutaImagen = $nuevaRuta;
        }

        try {
            $ordenFinal = $nuevoOrden > 0 ? $nuevoOrden : $ordenActual;
            
            Servicio::update($id, [
                'titulo' => $titulo,
                'imagen' => $rutaImagen,
                'orden' => $ordenFinal,
                'activo' => $activo
            ]);
            
            Servicio::reordenar();

            // Actualizar subsecciones: eliminar las existentes y crear nuevas
            $stmt = db()->prepare('DELETE FROM servicio_subsecciones WHERE servicio_id = ?');
            $stmt->execute([$id]);

            if (!empty($subsecciones)) {
                foreach ($subsecciones as $index => $sub) {
                    if (!empty($sub['subtitulo']) && !empty($sub['descripcion'])) {
                        $stmt = db()->prepare('
                            INSERT INTO servicio_subsecciones (servicio_id, subtitulo, descripcion, orden) 
                            VALUES (?, ?, ?, ?)
                        ');
                        $stmt->execute([$id, $sub['subtitulo'], $sub['descripcion'], $index + 1]);
                    }
                }
            }

            flash('exito', 'Servicio actualizado exitosamente.');
            header('Location: ' . url('admin/servicios'));
        } catch (PDOException $e) {
            flash('error', 'Error al actualizar: ' . $e->getMessage());
            header('Location: ' . url('admin/servicios/editar') . '?id=' . $id);
        }
        exit;
    }

    /**
     * Eliminar servicio
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
            header('Location: ' . url('admin/servicios'));
            exit;
        }

        $servicio = Servicio::getById($id);
        if ($servicio && $servicio['imagen']) {
            UploadHelper::deleteImage($servicio['imagen']);
        }

        try {
            Servicio::delete($id);
            Servicio::reordenar();
            flash('exito', 'Servicio eliminado exitosamente.');
        } catch (PDOException $e) {
            flash('error', 'Error al eliminar: ' . $e->getMessage());
        }
        header('Location: ' . url('admin/servicios'));
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
            header('Location: ' . url('admin/servicios'));
            exit;
        }

        try {
            Servicio::toggleEstado($id);
            flash('exito', 'Estado del servicio actualizado.');
        } catch (PDOException $e) {
            flash('error', 'Error al cambiar estado: ' . $e->getMessage());
        }
        header('Location: ' . url('admin/servicios'));
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