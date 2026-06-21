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

        // Iniciar sesión
        $_SESSION['usuario'] = [
            'id' => $usuario['id'],
            'email' => $usuario['email'],
        ];

        // Redirigir al dashboard
        header('Location: ' . url('/'));
        exit;
    }

    public function logout(): void
    {
        session_destroy();
        header('Location: ' . url('/'));
        exit;
    }
}