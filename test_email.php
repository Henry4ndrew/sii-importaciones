<?php
echo "<h1>📧 Test de Envío de Email - SII Importaciones</h1>";
echo "<hr>";

// Cargar configuración
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

echo "<h2>1. Configuración SMTP</h2>";

$config = require __DIR__ . '/config/config.php';
$mailConfig = $config['mail'] ?? [];

echo "Host: " . ($mailConfig['host'] ?? 'No configurado') . "<br>";
echo "Username: " . ($mailConfig['username'] ?? 'No configurado') . "<br>";
echo "Password: " . (isset($mailConfig['password']) ? '********' : 'No configurado') . "<br>";
echo "Port: " . ($mailConfig['port'] ?? 'No configurado') . "<br>";
echo "Secure: " . ($mailConfig['secure'] ?? 'No configurado') . "<br>";

echo "<hr>";

echo "<h2>2. Probando conexión SMTP</h2>";

$mail = new PHPMailer(true);

try {
    // Configuración del servidor
    $mail->isSMTP();
    $mail->Host       = 'mail.sii-importaciones.net';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'info@sii-importaciones.net';
    $mail->Password   = 'luisalberto2024';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port       = 465;
    
    // Timeout más largo
    $mail->Timeout = 30;
    
    // Desactivar verificación SSL para pruebas (NO en producción)
    $mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    );

    // Configurar debug
    $mail->SMTPDebug = SMTP::DEBUG_SERVER;
    $mail->Debugoutput = function($str, $level) {
        echo "Debug: $str<br>";
    };

    // Configurar email
    $mail->setFrom('info@sii-importaciones.net', 'SII Importaciones');
    $mail->addAddress('henryandrew777@gmail.com', 'Henry');
    $mail->addReplyTo('info@sii-importaciones.net', 'SII Importaciones');
    
    $mail->isHTML(true);
    $mail->CharSet = 'UTF-8';
    $mail->Subject = 'Test de Conexión SMTP - SII Importaciones';
    $mail->Body = '
        <h1>Test de Conexión SMTP</h1>
        <p>Este es un email de prueba para verificar que la configuración SMTP funciona correctamente.</p>
        <p><strong>Fecha:</strong> ' . date('Y-m-d H:i:s') . '</p>
        <p><strong>Servidor:</strong> mail.sii-importaciones.net</p>
    ';
    
    echo "<br><strong>Enviando email...</strong><br>";
    
    if ($mail->send()) {
        echo "<br><span style='color:green;font-weight:bold;'>✅ Email enviado exitosamente a henryandrew777@gmail.com</span><br>";
    } else {
        echo "<br><span style='color:red;font-weight:bold;'>❌ Error al enviar email: " . $mail->ErrorInfo . "</span><br>";
    }

} catch (Exception $e) {
    echo "<br><span style='color:red;font-weight:bold;'>❌ Error: " . $e->getMessage() . "</span><br>";
    echo "<br><strong>Detalles del error:</strong><br>";
    echo "<pre style='background:#f0f0f0;padding:10px;'>";
    echo $e->getTraceAsString();
    echo "</pre>";
}

echo "<hr>";

echo "<h2>3. Probando con diferentes puertos</h2>";

$puertos = [
    ['port' => 465, 'secure' => PHPMailer::ENCRYPTION_SMTPS, 'nombre' => 'SSL (465)'],
    ['port' => 587, 'secure' => PHPMailer::ENCRYPTION_STARTTLS, 'nombre' => 'TLS (587)'],
    ['port' => 25, 'secure' => '', 'nombre' => 'Sin SSL (25)'],
];

foreach ($puertos as $p) {
    echo "<strong>Probando puerto {$p['nombre']}:</strong> ";
    
    $mailTest = new PHPMailer(true);
    try {
        $mailTest->isSMTP();
        $mailTest->Host = 'mail.sii-importaciones.net';
        $mailTest->SMTPAuth = true;
        $mailTest->Username = 'info@sii-importaciones.net';
        $mailTest->Password = 'luisalberto2024';
        if ($p['secure']) {
            $mailTest->SMTPSecure = $p['secure'];
        }
        $mailTest->Port = $p['port'];
        $mailTest->Timeout = 10;
        
        $mailTest->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );
        
        $mailTest->setFrom('info@sii-importaciones.net', 'SII Importaciones');
        $mailTest->addAddress('test@example.com', 'Test');
        $mailTest->Subject = 'Test';
        $mailTest->Body = 'Test';
        
        // Conectar y verificar
        $mailTest->smtpConnect();
        echo "✅ Conexión exitosa<br>";
        $mailTest->smtpClose();
        
    } catch (Exception $e) {
        echo "❌ Error: " . $e->getMessage() . "<br>";
    }
}

echo "<hr>";

echo "<h2>4. Verificar DNS del servidor</h2>";
echo "Dominio: mail.sii-importaciones.net<br>";
$ip = gethostbyname('mail.sii-importaciones.net');
echo "IP Resuelta: " . $ip . "<br>";

if ($ip === 'mail.sii-importaciones.net') {
    echo "❌ No se pudo resolver el DNS<br>";
    echo "<strong>Sugerencia:</strong> Verifica que el dominio esté configurado correctamente.<br>";
} else {
    echo "✅ DNS resuelto correctamente<br>";
}

echo "<hr>";

echo "<h2>5. Enlaces útiles</h2>";
echo "<ul>";
echo "<li><a href='" . url('auth/recuperar') . "'>Formulario de recuperación</a></li>";
echo "<li><a href='" . url('auth/login') . "'>Login administrador</a></li>";
echo "</ul>";