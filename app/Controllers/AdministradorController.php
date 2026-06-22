<?php

class AdministradorController
{
    /**
     * Mostrar formulario de login de administrador
     */
    public function showLogin(): void
    {
        // Si ya está logueado como administrador, redirigir al dashboard admin
        if (isset($_SESSION['administrador'])) {
            header('Location: ' . url('admin/dashboard'));
            exit;
        }

        view('auth/login', ['titulo' => 'Acceso Administrador']);
    }

    /**
     * Procesar login de administrador
     */
    public function login(): void
    {
        // ============================================
        // DEBUG - Verificar que el método se está ejecutando
        // ============================================
        error_log("=== AdministradorController::login() ejecutado ===");
        error_log("POST: " . print_r($_POST, true));
        
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        // Validar campos
        if (empty($email) || empty($password)) {
            flash('error', 'Por favor, ingresa tu correo y contraseña.');
            header('Location: ' . url('auth/login'));
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            flash('error', 'Por favor, ingresa un correo electrónico válido.');
            header('Location: ' . url('auth/login'));
            exit;
        }

        // Buscar administrador
        $admin = Administrador::buscarPorEmail($email);

        if (!$admin) {
            flash('error', 'Credenciales incorrectas. Verifica tu correo y contraseña.');
            header('Location: ' . url('auth/login'));
            exit;
        }

        // Verificar contraseña
        if (!Administrador::verificarPassword($password, $admin['password'])) {
            flash('error', 'Credenciales incorrectas. Verifica tu correo y contraseña.');
            header('Location: ' . url('auth/login'));
            exit;
        }

        // ============================================
        // CERRAR SESIÓN DE USUARIO NORMAL SI EXISTE
        // ============================================
        if (isset($_SESSION['usuario'])) {
            unset($_SESSION['usuario']);
        }

        // ============================================
        // INICIAR SESIÓN COMO ADMINISTRADOR
        // ============================================
        $_SESSION['administrador'] = [
            'id' => $admin['id'],
            'email' => $admin['email'],
            'nombre' => $admin['nombre'],
        ];

        flash('exito', '¡Bienvenido administrador, ' . $admin['nombre'] . '!');
        
        // ============================================
        // REDIRIGIR AL DASHBOARD DE ADMINISTRADOR
        // ============================================
        error_log("=== Redirigiendo a admin/dashboard ===");
        header('Location: ' . url('admin/dashboard'));
        exit;
    }

    /**
     * Cerrar sesión de administrador
     */
    public function logout(): void
    {
        // Cerrar sesión de administrador
        unset($_SESSION['administrador']);
        
        flash('exito', 'Sesión de administrador cerrada correctamente.');
        header('Location: ' . url('auth/login'));
        exit;
    }

    /**
     * Dashboard del administrador
     */
    public function dashboard(): void
    {
        error_log("=== AdministradorController::dashboard() ejecutado ===");
        error_log("SESSION: " . print_r($_SESSION, true));
        
        // Verificar que sea administrador
        if (!isset($_SESSION['administrador'])) {
            error_log("=== No hay sesión de administrador, redirigiendo a login ===");
            header('Location: ' . url('auth/login'));
            exit;
        }

        // Si existe sesión de usuario normal, cerrarla
        if (isset($_SESSION['usuario'])) {
            unset($_SESSION['usuario']);
        }

        // Obtener estadísticas
        $totalUsuarios = $this->getTotalUsuarios();
        $totalProductos = $this->getTotalProductos();
        $totalVotos = $this->getTotalVotos();

        // Usar adminView para el layout de administrador
        adminView('admin/dashboard', [
            'titulo' => 'Panel de Administrador',
            'admin' => $_SESSION['administrador'],
            'totalUsuarios' => $totalUsuarios,
            'totalProductos' => $totalProductos,
            'totalVotos' => $totalVotos,
            'activePage' => 'dashboard'
        ]);
    }

    /**
     * Lista de usuarios (para administradores)
     */
    public function usuarios(): void
    {
        // Verificar que sea administrador
        if (!isset($_SESSION['administrador'])) {
            header('Location: ' . url('auth/login'));
            exit;
        }

        // Si existe sesión de usuario normal, cerrarla
        if (isset($_SESSION['usuario'])) {
            unset($_SESSION['usuario']);
        }

        $usuarios = $this->getAllUsuarios();
        $totalUsuarios = $this->getTotalUsuarios();
        $totalVotos = $this->getTotalVotos();

        adminView('admin/usuarios', [
            'titulo' => 'Gestión de Usuarios',
            'admin' => $_SESSION['administrador'],
            'usuarios' => $usuarios,
            'totalUsuarios' => $totalUsuarios,
            'totalVotos' => $totalVotos,
            'activePage' => 'usuarios'
        ]);
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
     * Obtener total de productos
     */
    private function getTotalProductos(): int
    {
        $stmt = db()->query('SELECT COUNT(*) as total FROM productos');
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

    /**
     * Obtener todos los usuarios
     */
    private function getAllUsuarios(): array
    {
        $stmt = db()->query('SELECT * FROM usuarios ORDER BY created_at DESC');
        return $stmt->fetchAll();
    }
}