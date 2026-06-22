<?php

class AuthController
{
    public function showLogin(): void
    {
        view('auth.login', ['titulo' => 'Acceder al Sistema']);
    }

    public function login(): void
    {        
        $email = trim($_POST['email'] ?? '');

        // Validar email
        if (empty($email)) {
            flash('error', 'Por favor, ingresa tu correo electrónico.');
            header('Location: ' . url('login'));
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            flash('error', 'Por favor, ingresa un correo electrónico válido.');
            header('Location: ' . url('login'));
            exit;
        }

        // Buscar usuario
        $usuario = Usuario::buscarPorEmail($email);

        // Si no existe, crear usuario automáticamente
        if (!$usuario) {
            $id = Usuario::crear($email);
            $usuario = Usuario::buscarPorEmail($email);
            flash('exito', '¡Bienvenido! Tu cuenta ha sido creada automáticamente.');
        }

        // ============================================
        // CERRAR SESIÓN DE ADMINISTRADOR SI EXISTE
        // ============================================
        if (isset($_SESSION['administrador'])) {
            unset($_SESSION['administrador']);
        }

        // ============================================
        // INICIAR SESIÓN COMO USUARIO NORMAL
        // ============================================
        $_SESSION['usuario'] = [
            'id' => $usuario['id'],
            'email' => $usuario['email'],
        ];

        // Redirigir al dashboard
        header('Location: ' . url('dashboard'));
        exit;
    }

    public function logout(): void
    {
        // Cerrar sesión de usuario normal
        if (isset($_SESSION['usuario'])) {
            unset($_SESSION['usuario']);
        }
        
        // Cerrar sesión de administrador si existe
        if (isset($_SESSION['administrador'])) {
            unset($_SESSION['administrador']);
        }
        
        // Destruir la sesión completamente
        session_destroy();
        
        header('Location: ' . url('/'));
        exit;
    }
}