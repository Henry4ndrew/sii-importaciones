<?php
require_once __DIR__ . '/../Helpers/UploadHelper.php';

class ConocenosController
{
    /**
     * Mostrar la página de Conócenos (vista)
     */
    public function index(): void
    {
        if (!isset($_SESSION['administrador'])) {
            header('Location: ' . url('auth/login'));
            exit;
        }

        $conocenos = Conocenos::get();
        $equipo = Conocenos::getEquipo();
        $totalAdministradores = $this->getTotalAdministradores();

        adminView('admin/conocenos/index', [
            'titulo' => 'Gestión de Conócenos',
            'admin' => $_SESSION['administrador'],
            'conocenos' => $conocenos,
            'equipo' => $equipo,
            'totalAdministradores' => $totalAdministradores,
            'activePage' => 'conocenos'
        ]);
    }

    /**
     * Actualizar los datos de Conócenos (incluyendo equipo)
     */
    public function update(): void
    {
        if (!isset($_SESSION['administrador'])) {
            header('Location: ' . url('auth/login'));
            exit;
        }

        // Obtener datos actuales
        $actual = Conocenos::get();

        // Recolectar datos del formulario
        $data = [
            'encabezado_titulo' => trim($_POST['encabezado_titulo'] ?? 'Sobre Nosotros'),
            'encabezado_descripcion' => trim($_POST['encabezado_descripcion'] ?? ''),
            'encabezado_imagen' => $actual['encabezado_imagen'] ?? null,
            'mision_texto' => trim($_POST['mision_texto'] ?? ''),
            'mision_imagen' => $actual['mision_imagen'] ?? null,
            'vision_texto' => trim($_POST['vision_texto'] ?? ''),
            'vision_imagen' => $actual['vision_imagen'] ?? null,
            'historia_texto' => trim($_POST['historia_texto'] ?? ''),
            'historia_imagen' => $actual['historia_imagen'] ?? null,
        ];

        // Procesar imágenes subidas
        $imagenes = ['encabezado_imagen', 'mision_imagen', 'vision_imagen', 'historia_imagen'];
        foreach ($imagenes as $campo) {
            if (isset($_FILES[$campo]) && $_FILES[$campo]['error'] === UPLOAD_ERR_OK) {
                if (!empty($actual[$campo])) {
                    UploadHelper::deleteImage($actual[$campo]);
                }
                
                $nuevaRuta = UploadHelper::uploadImage($_FILES[$campo], 'conocenos');
                if ($nuevaRuta) {
                    $data[$campo] = $nuevaRuta;
                }
            }
        }

        try {
            // Actualizar datos principales
            Conocenos::update($data);
            
            // ============================================
            // PROCESAR EQUIPO DESDE EL FORMULARIO PRINCIPAL
            // ============================================
            // Obtener los IDs del equipo enviados
            $equipoIds = $_POST['equipo_id'] ?? [];
            
            if (!empty($equipoIds) && is_array($equipoIds)) {
                foreach ($equipoIds as $id) {
                    $id = (int) $id;
                    if ($id <= 0) continue;
                    
                    $nombre = trim($_POST['equipo_nombre_' . $id] ?? '');
                    $cargo = trim($_POST['equipo_cargo_' . $id] ?? '');
                    
                    if (empty($nombre) || empty($cargo)) {
                        continue; // Saltar si falta nombre o cargo
                    }
                    
                    // Obtener miembro actual
                    $miembro = Conocenos::getEquipoById($id);
                    if (!$miembro) continue;
                    
                    $rutaImagen = $miembro['imagen'];
                    
                    // Verificar si se subió una nueva imagen para este miembro
                    $campoImagen = 'equipo_imagen_' . $id;
                    if (isset($_FILES[$campoImagen]) && $_FILES[$campoImagen]['error'] === UPLOAD_ERR_OK) {
                        if ($miembro['imagen']) {
                            UploadHelper::deleteImage($miembro['imagen']);
                        }
                        $nuevaRuta = UploadHelper::uploadImage($_FILES[$campoImagen], 'conocenos/equipo');
                        if ($nuevaRuta) {
                            $rutaImagen = $nuevaRuta;
                        }
                    }
                    
                    // Actualizar miembro
                    Conocenos::updateEquipo($id, [
                        'nombre' => $nombre,
                        'cargo' => $cargo,
                        'imagen' => $rutaImagen
                    ]);
                }
            }
            
            flash('exito', 'Datos de Conócenos actualizados exitosamente.');
        } catch (PDOException $e) {
            flash('error', 'Error al actualizar: ' . $e->getMessage());
        }
        
        header('Location: ' . url('admin/conocenos'));
        exit;
    }


    
    /**
     * Agregar miembro al equipo - DEVUELVE JSON
     */
    public function equipoStore(): void
    {
        if (!isset($_SESSION['administrador'])) {
            echo json_encode(['success' => false, 'message' => 'No autorizado']);
            exit;
        }

        $nombre = trim($_POST['nombre'] ?? '');
        $cargo = trim($_POST['cargo'] ?? '');

        if (empty($nombre) || empty($cargo)) {
            echo json_encode(['success' => false, 'message' => 'Nombre y cargo son obligatorios.']);
            exit;
        }

        $rutaImagen = null;
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $rutaImagen = UploadHelper::uploadImage($_FILES['imagen'], 'conocenos/equipo');
            if (!$rutaImagen) {
                echo json_encode(['success' => false, 'message' => 'Error al subir la imagen.']);
                exit;
            }
        }

        try {
            $id = Conocenos::addEquipo([
                'nombre' => $nombre,
                'cargo' => $cargo,
                'imagen' => $rutaImagen
            ]);
            Conocenos::reordenarEquipo();
            echo json_encode(['success' => true, 'id' => $id]);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        exit;
    }

    /**
     * Actualizar miembro del equipo - DEVUELVE JSON
     */
    public function equipoUpdate(): void
    {
        if (!isset($_SESSION['administrador'])) {
            echo json_encode(['success' => false, 'message' => 'No autorizado']);
            exit;
        }

        $id = (int) ($_POST['id'] ?? 0);
        $nombre = trim($_POST['nombre'] ?? '');
        $cargo = trim($_POST['cargo'] ?? '');

        if ($id <= 0 || empty($nombre) || empty($cargo)) {
            echo json_encode(['success' => false, 'message' => 'Datos inválidos.']);
            exit;
        }

        $miembro = Conocenos::getEquipoById($id);
        if (!$miembro) {
            echo json_encode(['success' => false, 'message' => 'Miembro no encontrado.']);
            exit;
        }

        $rutaImagen = $miembro['imagen'];
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            if ($miembro['imagen']) {
                UploadHelper::deleteImage($miembro['imagen']);
            }
            $nuevaRuta = UploadHelper::uploadImage($_FILES['imagen'], 'conocenos/equipo');
            if ($nuevaRuta) {
                $rutaImagen = $nuevaRuta;
            }
        }

        try {
            Conocenos::updateEquipo($id, [
                'nombre' => $nombre,
                'cargo' => $cargo,
                'imagen' => $rutaImagen
            ]);
            echo json_encode(['success' => true]);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        exit;
    }

    /**
     * Eliminar miembro del equipo - DEVUELVE JSON
     */
    public function equipoDelete(): void
    {
        if (!isset($_SESSION['administrador'])) {
            echo json_encode(['success' => false, 'message' => 'No autorizado']);
            exit;
        }

        $id = (int) ($_GET['id'] ?? 0);
        if ($id <= 0) {
            echo json_encode(['success' => false, 'message' => 'ID inválido.']);
            exit;
        }

        $miembro = Conocenos::getEquipoById($id);
        if ($miembro && $miembro['imagen']) {
            UploadHelper::deleteImage($miembro['imagen']);
        }

        try {
            Conocenos::deleteEquipo($id);
            Conocenos::reordenarEquipo();
            echo json_encode(['success' => true]);
        } catch (PDOException $e) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
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