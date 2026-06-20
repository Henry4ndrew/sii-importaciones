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
        $password = $_POST['password'] ?? '';

        $usuario = Usuario::buscarPorEmail($email);

        if (!$usuario || !password_verify($password, $usuario['password'])) {
            flash('error', 'Correo o contraseña incorrectos.');
            redirect('login');
        }

        $_SESSION['usuario'] = [
            'id' => $usuario['id'],
            'nombre' => $usuario['nombre'],
            'email' => $usuario['email'],
        ];

        redirect('/');
    }

    public function showRegister(): void
    {
        view('auth.register', ['titulo' => 'Crear Cuenta']);
    }

    public function register(): void
    {
        $nombre = trim($_POST['nombre'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        if ($nombre === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 6) {
            flash('error', 'Completa todos los campos (la contraseña debe tener al menos 6 caracteres).');
            redirect('register');
        }

        if (Usuario::buscarPorEmail($email)) {
            flash('error', 'Ese correo ya está registrado.');
            redirect('register');
        }

        $id = Usuario::crear($nombre, $email, $password);
        $_SESSION['usuario'] = ['id' => $id, 'nombre' => $nombre, 'email' => $email];

        flash('exito', '¡Bienvenido, ' . $nombre . '! Tu cuenta fue creada.');
        redirect('/');
    }

    public function logout(): void
    {
        session_destroy();
        redirect('/');
    }
}
