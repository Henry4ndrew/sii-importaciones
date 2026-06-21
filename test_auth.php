<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/app/Helpers/functions.php';
require_once __DIR__ . '/app/Models/Usuario.php';
require_once __DIR__ . '/app/controllers/AuthController.php';

// Iniciar sesión
session_start();

echo "<h1>Test de Autenticación</h1>";

// Datos de prueba
$email = 'henryandrew777@gmail.com';
$password = '123456';

echo "<h2>Probando login con:</h2>";
echo "Email: " . $email . "<br>";
echo "Password: " . $password . "<br><br>";

// 1. Buscar usuario en la base de datos
echo "<h3>1. Buscando usuario en BD:</h3>";
$usuario = Usuario::buscarPorEmail($email);

if ($usuario) {
    echo "✅ Usuario encontrado<br>";
    echo "ID: " . $usuario['id'] . "<br>";
    echo "Nombre: " . $usuario['nombre'] . "<br>";
    echo "Email: " . $usuario['email'] . "<br>";
    echo "Password hash: " . $usuario['password'] . "<br>";
    
    // 2. Verificar contraseña
    echo "<h3>2. Verificando contraseña:</h3>";
    $passwordVerify = password_verify($password, $usuario['password']);
    echo "Password verify: " . ($passwordVerify ? '✅ CORRECTA' : '❌ INCORRECTA') . "<br>";
    
    if ($passwordVerify) {
        echo "<h3 style='color:green'>✅ LOGIN EXITOSO</h3>";
        
        // 3. Simular login (sin redirigir)
        echo "<h3>3. Simulando login (sin redirección):</h3>";
        $_SESSION['usuario'] = [
            'id' => $usuario['id'],
            'nombre' => $usuario['nombre'],
            'email' => $usuario['email'],
        ];
        echo "✅ Sesión creada:<br>";
        echo "<pre>";
        print_r($_SESSION);
        echo "</pre>";
        
        echo "<h3>4. Verificar autenticación:</h3>";
        echo "auth(): " . (auth() ? '✅ Autenticado' : '❌ No autenticado') . "<br>";
        echo "Usuario: " . (auth() ? auth()['nombre'] : 'N/A') . "<br>";
    }
} else {
    echo "❌ Usuario NO encontrado<br>";
}

// Mostrar estado de la sesión
echo "<h3>Estado de la sesión:</h3>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

// Enlaces útiles
echo "<h3>Enlaces útiles:</h3>";
echo "<a href='" . url('/') . "'>Ir al login</a><br>";
echo "<a href='" . url('dashboard') . "'>Ir al dashboard</a><br>";
?>