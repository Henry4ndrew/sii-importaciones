<?php
echo "<h1>Test de Recuperación</h1>";

// Cargar configuración
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/app/Helpers/functions.php';
require_once __DIR__ . '/app/Models/Administrador.php';
require_once __DIR__ . '/app/Models/RecuperacionToken.php';

echo "<h2>1. Verificar administradores en BD</h2>";
$admin = Administrador::buscarPorEmail('henryandrew777@gmail.com');
if ($admin) {
    echo "✅ Administrador encontrado: " . $admin['nombre'] . " (" . $admin['email'] . ")<br>";
} else {
    echo "❌ Administrador NO encontrado<br>";
}

echo "<h2>2. Verificar creación de token</h2>";
$token = RecuperacionToken::crear($admin['id'], 1);
if ($token) {
    echo "✅ Token creado: " . $token . "<br>";
} else {
    echo "❌ Error al crear token<br>";
}

echo "<h2>3. Verificar búsqueda de token</h2>";
$tokenData = RecuperacionToken::buscarToken($token);
if ($tokenData) {
    echo "✅ Token válido<br>";
    echo "<pre>";
    print_r($tokenData);
    echo "</pre>";
} else {
    echo "❌ Token NO encontrado<br>";
}

echo "<h2>4. Verificar URL de restablecimiento</h2>";
$resetUrl = url('auth/restablecer') . '?token=' . $token;
echo "URL: <a href='$resetUrl' target='_blank'>$resetUrl</a><br>";

echo "<h2>5. Verificar PHPMailer</h2>";
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    echo "✅ Composer autoload encontrado<br>";
    require_once __DIR__ . '/vendor/autoload.php';
    echo "✅ PHPMailer cargado<br>";
    
    // Probar envío de email
    echo "<h3>Enviar email de prueba</h3>";
    $config = require __DIR__ . '/config/config.php';
    $mailConfig = $config['mail'] ?? [];
    
    echo "Host: " . ($mailConfig['host'] ?? 'No configurado') . "<br>";
    echo "Username: " . ($mailConfig['username'] ?? 'No configurado') . "<br>";
    echo "Port: " . ($mailConfig['port'] ?? 'No configurado') . "<br>";
    
    try {
        $mail = new PHPMailer\PHPMailer\PHPMailer(true);
        $mail->isSMTP();
        $mail->Host       = $mailConfig['host'] ?? 'mail.sii-importaciones.net';
        $mail->SMTPAuth   = true;
        $mail->Username   = $mailConfig['username'] ?? 'info@sii-importaciones.net';
        $mail->Password   = $mailConfig['password'] ?? '';
        $mail->SMTPSecure = $mailConfig['secure'] ?? PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port       = $mailConfig['port'] ?? 465;
        $mail->setFrom(
            $mailConfig['from_email'] ?? 'info@sii-importaciones.net',
            $mailConfig['from_name'] ?? 'SII Importaciones'
        );
        $mail->addAddress('test@example.com', 'Test');
        $mail->Subject = 'Test';
        $mail->Body = 'Test';
        $mail->send();
        echo "✅ Email enviado correctamente<br>";
    } catch (Exception $e) {
        echo "❌ Error al enviar email: " . $e->getMessage() . "<br>";
    }
} else {
    echo "❌ Composer autoload NO encontrado. Ejecuta: composer require phpmailer/phpmailer<br>";
}

echo "<h2>6. Enlaces útiles</h2>";
echo "<ul>";
echo "<li><a href='" . url('auth/recuperar') . "'>Formulario de recuperación</a></li>";
echo "<li><a href='" . url('auth/login') . "'>Login administrador</a></li>";
echo "<li><a href='" . url('/') . "'>Inicio</a></li>";
echo "</ul>";