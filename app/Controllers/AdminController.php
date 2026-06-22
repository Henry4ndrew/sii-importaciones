<?php

class AdminController
{
    /**
     * Listar administradores
     */
public function index(): void
{
    if (!isset($_SESSION['administrador'])) {
        header('Location: ' . url('auth/login'));
        exit;
    }

    $administradores = $this->getAllAdministradores();
    $totalUsuarios = $this->getTotalUsuarios();
    $totalVotos = $this->getTotalVotos();
    $totalAdministradores = $this->getTotalAdministradores();

    adminView('admin/administradores/index', [
        'titulo' => 'Gestión de Administradores',
        'admin' => $_SESSION['administrador'],
        'administradores' => $administradores,
        'totalUsuarios' => $totalUsuarios,
        'totalVotos' => $totalVotos,
        'totalAdministradores' => $totalAdministradores,
        'activePage' => 'administradores'
    ]);
}


    /**
     * Mostrar formulario para crear administrador
     */
    public function crear(): void
    {
        if (!isset($_SESSION['administrador'])) {
            header('Location: ' . url('auth/login'));
            exit;
        }

        $totalUsuarios = $this->getTotalUsuarios();
        $totalVotos = $this->getTotalVotos();
        $totalAdministradores = $this->getTotalAdministradores();

        adminView('admin/administradores/crear', [
            'titulo' => 'Crear Administrador',
            'admin' => $_SESSION['administrador'],
            'totalUsuarios' => $totalUsuarios,
            'totalVotos' => $totalVotos,
            'totalAdministradores' => $totalAdministradores,
            'activePage' => 'administradores'
        ]);
    }

    /**
     * Guardar nuevo administrador
     */
/**
 * Guardar nuevo administrador
 */
/**
 * Guardar nuevo administrador
 */
public function store(): void
{
    // Debug temporal
    error_log("=== AdminController::store() ejecutado ===");
    error_log("POST: " . print_r($_POST, true));
    
    if (!isset($_SESSION['administrador'])) {
        header('Location: ' . url('auth/login'));
        exit;
    }

    $nombre = trim($_POST['nombre'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $passwordConfirm = $_POST['password_confirm'] ?? '';

    // Validaciones
    if (empty($nombre) || empty($email) || empty($password)) {
        flash('error', 'Todos los campos son obligatorios.');
        header('Location: ' . url('admin/administradores/crear'));
        exit;
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        flash('error', 'Por favor, ingresa un correo electrónico válido.');
        header('Location: ' . url('admin/administradores/crear'));
        exit;
    }

    if (strlen($password) < 6) {
        flash('error', 'La contraseña debe tener al menos 6 caracteres.');
        header('Location: ' . url('admin/administradores/crear'));
        exit;
    }

    if ($password !== $passwordConfirm) {
        flash('error', 'Las contraseñas no coinciden.');
        header('Location: ' . url('admin/administradores/crear'));
        exit;
    }

    // Verificar si el email ya existe
    $existing = Administrador::buscarPorEmail($email);
    if ($existing) {
        flash('error', 'Este correo electrónico ya está registrado como administrador.');
        header('Location: ' . url('admin/administradores/crear'));
        exit;
    }

    // Crear administrador
    $hash = password_hash($password, PASSWORD_DEFAULT);
    try {
        $stmt = db()->prepare('INSERT INTO administradores (nombre, email, password) VALUES (?, ?, ?)');
        $stmt->execute([$nombre, $email, $hash]);
        
        flash('exito', 'Administrador creado exitosamente.');
        header('Location: ' . url('admin/administradores'));
    } catch (PDOException $e) {
        flash('error', 'Error al crear el administrador: ' . $e->getMessage());
        header('Location: ' . url('admin/administradores/crear'));
    }
    exit;
}




    /**
     * Mostrar formulario para editar administrador
     */
    public function editar(): void
    {
        if (!isset($_SESSION['administrador'])) {
            header('Location: ' . url('auth/login'));
            exit;
        }

        $id = (int) ($_GET['id'] ?? 0);
        if ($id <= 0) {
            flash('error', 'ID de administrador inválido.');
            header('Location: ' . url('admin/administradores'));
            exit;
        }

        $administrador = $this->getAdministradorPorId($id);
        if (!$administrador) {
            flash('error', 'Administrador no encontrado.');
            header('Location: ' . url('admin/administradores'));
            exit;
        }

        $totalUsuarios = $this->getTotalUsuarios();
        $totalVotos = $this->getTotalVotos();
        $totalAdministradores = $this->getTotalAdministradores();

        adminView('admin/administradores/editar', [
            'titulo' => 'Editar Administrador',
            'admin' => $_SESSION['administrador'],
            'administrador' => $administrador,
            'totalUsuarios' => $totalUsuarios,
            'totalVotos' => $totalVotos,
            'totalAdministradores' => $totalAdministradores,
            'activePage' => 'administradores'
        ]);
    }

    /**
     * Actualizar administrador
     */
    public function update(): void
    {
        if (!isset($_SESSION['administrador'])) {
            header('Location: ' . url('auth/login'));
            exit;
        }

        $id = (int) ($_POST['id'] ?? 0);
        $nombre = trim($_POST['nombre'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $passwordConfirm = $_POST['password_confirm'] ?? '';

        if ($id <= 0 || empty($nombre) || empty($email)) {
            flash('error', 'Todos los campos son obligatorios.');
            header('Location: ' . url('admin/administradores/editar') . '?id=' . $id);
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            flash('error', 'Por favor, ingresa un correo electrónico válido.');
            header('Location: ' . url('admin/administradores/editar') . '?id=' . $id);
            exit;
        }

        // Verificar si el email ya existe en otro administrador
        $stmt = db()->prepare('SELECT * FROM administradores WHERE email = ? AND id != ?');
        $stmt->execute([$email, $id]);
        if ($stmt->fetch()) {
            flash('error', 'Este correo electrónico ya está registrado como administrador.');
            header('Location: ' . url('admin/administradores/editar') . '?id=' . $id);
            exit;
        }

        // Construir consulta
        $sql = 'UPDATE administradores SET nombre = ?, email = ?';
        $params = [$nombre, $email];

        // Si se proporcionó contraseña, actualizarla
        if (!empty($password)) {
            if (strlen($password) < 6) {
                flash('error', 'La contraseña debe tener al menos 6 caracteres.');
                header('Location: ' . url('admin/administradores/editar') . '?id=' . $id);
                exit;
            }
            if ($password !== $passwordConfirm) {
                flash('error', 'Las contraseñas no coinciden.');
                header('Location: ' . url('admin/administradores/editar') . '?id=' . $id);
                exit;
            }
            $sql .= ', password = ?';
            $params[] = password_hash($password, PASSWORD_DEFAULT);
        }

        $sql .= ' WHERE id = ?';
        $params[] = $id;

        try {
            $stmt = db()->prepare($sql);
            $stmt->execute($params);
            
            flash('exito', 'Administrador actualizado exitosamente.');
            header('Location: ' . url('admin/administradores'));
        } catch (PDOException $e) {
            flash('error', 'Error al actualizar el administrador: ' . $e->getMessage());
            header('Location: ' . url('admin/administradores/editar') . '?id=' . $id);
        }
        exit;
    }

    /**
     * Eliminar administrador
     */
    public function delete(): void
    {
        if (!isset($_SESSION['administrador'])) {
            header('Location: ' . url('auth/login'));
            exit;
        }

        $id = (int) ($_GET['id'] ?? 0);
        if ($id <= 0) {
            flash('error', 'ID de administrador inválido.');
            header('Location: ' . url('admin/administradores'));
            exit;
        }

        // No permitir eliminar al propio administrador
        if ($id == $_SESSION['administrador']['id']) {
            flash('error', 'No puedes eliminar tu propia cuenta.');
            header('Location: ' . url('admin/administradores'));
            exit;
        }

        try {
            $stmt = db()->prepare('DELETE FROM administradores WHERE id = ?');
            $stmt->execute([$id]);
            
            flash('exito', 'Administrador eliminado exitosamente.');
        } catch (PDOException $e) {
            flash('error', 'Error al eliminar el administrador: ' . $e->getMessage());
        }
        header('Location: ' . url('admin/administradores'));
        exit;
    }

    /**
     * Obtener todos los administradores
     */
    private function getAllAdministradores(): array
    {
        $stmt = db()->query('SELECT * FROM administradores ORDER BY created_at DESC');
        return $stmt->fetchAll();
    }

    /**
     * Obtener administrador por ID
     */
    private function getAdministradorPorId(int $id): ?array
    {
        $stmt = db()->prepare('SELECT * FROM administradores WHERE id = ?');
        $stmt->execute([$id]);
        $result = $stmt->fetch();
        return $result ?: null;
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

    /**
     * Obtener total de usuarios
     */
    private function getTotalUsuarios(): int
    {
        $stmt = db()->query('SELECT COUNT(*) as total FROM usuarios');
        $result = $stmt->fetch();
        return (int) $result['total'];
    }

    /**
     * Obtener total de votos
     */
    private function getTotalVotos(): int
    {
        $stmt = db()->query('SELECT COUNT(*) as total FROM votos');
        $result = $stmt->fetch();
        return (int) $result['total'];
    }
}