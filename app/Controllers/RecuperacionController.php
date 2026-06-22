<?php
require_once __DIR__ . '/../Models/Administrador.php';
require_once __DIR__ . '/../Models/RecuperacionToken.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class RecuperacionController
{
    /**
     * Mostrar formulario para solicitar recuperación
     */
    public function showSolicitar(): void
    {
        view('auth/recuperar-solicitar', ['titulo' => 'Recuperar Contraseña - SII Importaciones']);
    }

    /**
     * Procesar solicitud de recuperación
     */
    public function solicitar(): void
    {
        $email = trim($_POST['email'] ?? '');

        // Validar email
        if (empty($email)) {
            flash('error', 'Por favor, ingresa tu correo electrónico.');
            header('Location: ' . url('auth/recuperar'));
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            flash('error', 'Por favor, ingresa un correo electrónico válido.');
            header('Location: ' . url('auth/recuperar'));
            exit;
        }

        // Buscar administrador
        $admin = Administrador::buscarPorEmail($email);

        if (!$admin) {
            flash('error', 'No existe una cuenta de administrador con este correo.');
            header('Location: ' . url('auth/recuperar'));
            exit;
        }

        // Crear token
        $token = RecuperacionToken::crear($admin['id'], 1);

        if (!$token) {
            flash('error', 'Error al generar el token de recuperación. Intenta nuevamente.');
            header('Location: ' . url('auth/recuperar'));
            exit;
        }

        // Enviar email
        $enviado = $this->enviarEmailRecuperacion($admin['email'], $admin['nombre'], $token);

        if ($enviado) {
            flash('exito', 'Se ha enviado un enlace de recuperación a tu correo electrónico.');
        } else {
            flash('error', 'Error al enviar el correo. Por favor, intenta nuevamente.');
        }

        header('Location: ' . url('auth/recuperar'));
        exit;
    }

    /**
     * Mostrar formulario para restablecer contraseña
     */
    public function showRestablecer(): void
    {
        $token = $_GET['token'] ?? '';

        if (empty($token)) {
            flash('error', 'Token inválido.');
            header('Location: ' . url('auth/recuperar'));
            exit;
        }

        // Verificar token
        $tokenData = RecuperacionToken::buscarToken($token);

        if (!$tokenData) {
            flash('error', 'El enlace de recuperación ha expirado o es inválido.');
            header('Location: ' . url('auth/recuperar'));
            exit;
        }

        view('auth/recuperar-restablecer', [
            'titulo' => 'Restablecer Contraseña - SII Importaciones',
            'token' => $token
        ]);
    }

    /**
     * Procesar restablecimiento de contraseña
     */
    public function restablecer(): void
    {
        $token = trim($_POST['token'] ?? '');
        $password = $_POST['password'] ?? '';
        $passwordConfirm = $_POST['password_confirm'] ?? '';

        if (empty($token)) {
            flash('error', 'Token inválido.');
            header('Location: ' . url('auth/recuperar'));
            exit;
        }

        if (strlen($password) < 6) {
            flash('error', 'La contraseña debe tener al menos 6 caracteres.');
            header('Location: ' . url('auth/restablecer') . '?token=' . $token);
            exit;
        }

        if ($password !== $passwordConfirm) {
            flash('error', 'Las contraseñas no coinciden.');
            header('Location: ' . url('auth/restablecer') . '?token=' . $token);
            exit;
        }

        // Verificar token
        $tokenData = RecuperacionToken::buscarToken($token);

        if (!$tokenData) {
            flash('error', 'El enlace de recuperación ha expirado o es inválido.');
            header('Location: ' . url('auth/recuperar'));
            exit;
        }

        // Actualizar contraseña
        if (Administrador::actualizarPassword($tokenData['admin_id'], $password)) {
            RecuperacionToken::marcarComoUsado($tokenData['id']);
            flash('exito', 'Tu contraseña ha sido restablecida exitosamente. Ahora puedes iniciar sesión.');
            header('Location: ' . url('auth/login'));
        } else {
            flash('error', 'Error al restablecer la contraseña. Intenta nuevamente.');
            header('Location: ' . url('auth/restablecer') . '?token=' . $token);
        }
        exit;
    }

    /**
     * Enviar email de recuperación usando PHPMailer
     */
    private function enviarEmailRecuperacion(string $email, string $nombre, string $token): bool
    {
        // Limpiar tokens expirados
        RecuperacionToken::limpiarExpirados();

        $config = require __DIR__ . '/../../config/config.php';
        $mailConfig = $config['mail'] ?? [];

        $mail = new PHPMailer(true);

        try {
            // Configuración del servidor SMTP
            $mail->isSMTP();
            $mail->Host       = $mailConfig['host'] ?? 'mail.sii-importaciones.net';
            $mail->SMTPAuth   = true;
            $mail->Username   = $mailConfig['username'] ?? 'info@sii-importaciones.net';
            $mail->Password   = $mailConfig['password'] ?? '';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = 465;
            $mail->Timeout    = 30;

            $mail->SMTPOptions = array(
                'ssl' => array(
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                )
            );

            $mail->setFrom(
                $mailConfig['from_email'] ?? 'info@sii-importaciones.net',
                $mailConfig['from_name'] ?? 'SII Importaciones'
            );
            $mail->addAddress($email, $nombre);
            $mail->addReplyTo(
                $mailConfig['from_email'] ?? 'info@sii-importaciones.net',
                $mailConfig['from_name'] ?? 'SII Importaciones'
            );

            $mail->isHTML(true);
            $mail->CharSet = 'UTF-8';
            $mail->Subject = 'Recuperación de Contraseña - SII Importaciones';

            // ============================================
            // URL ABSOLUTA COMPLETA CON urlFull()
            // ============================================
            $resetUrl = urlFull('auth/restablecer') . '?token=' . $token;

            $mail->Body = $this->getEmailTemplate($nombre, $resetUrl);
            $mail->AltBody = "Hola $nombre,\n\nHaz clic en el siguiente enlace para restablecer tu contraseña:\n$resetUrl\n\nEste enlace expirará en 1 hora.\n\nSaludos,\nSII Importaciones";

            $mail->send();
            return true;

        } catch (Exception $e) {
            $errorLog = __DIR__ . '/../../logs/email_error.log';
            $logDir = dirname($errorLog);
            if (!is_dir($logDir)) {
                mkdir($logDir, 0755, true);
            }
            file_put_contents($errorLog, date('[Y-m-d H:i:s]') . " Error: " . $e->getMessage() . "\n", FILE_APPEND);
            return false;
        }
    }







    private function getEmailTemplate(string $nombre, string $resetUrl): string
    {
        return "
        <!DOCTYPE html>
        <html lang='es'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Recuperación de Contraseña - SII Importaciones</title>
            <style>
                * { box-sizing: border-box; margin: 0; padding: 0; }
                body { font-family: Arial, sans-serif; background-color: #F1EFE8; padding: 32px 16px; }
                .wrapper { max-width: 560px; margin: 0 auto; background: #ffffff; border-radius: 12px; border: 1px solid #E0DDD5; overflow: hidden; }

                .header { background: #0A1626; padding: 36px 32px; text-align: center; }
                .header-icon { width: 52px; height: 52px; border-radius: 12px; background: rgba(255,255,255,0.1); margin: 0 auto 16px; display: flex; align-items: center; justify-content: center; }
                .header-icon svg { width: 26px; height: 26px; stroke: #B8CCE3; fill: none; stroke-width: 1.5; stroke-linecap: round; stroke-linejoin: round; }
                .header h1 { font-size: 16px; font-weight: 500; color: #ffffff; letter-spacing: 2px; text-transform: uppercase; }
                .header p { margin-top: 6px; font-size: 12px; color: #6B8FAF; letter-spacing: 0.5px; }

                .content { padding: 36px 32px 28px; }

                .badge { display: flex; align-items: center; gap: 10px; margin-bottom: 20px; }
                .badge-icon { width: 36px; height: 36px; border-radius: 50%; background: #EBF4FF; flex-shrink: 0; display: flex; align-items: center; justify-content: center; }
                .badge-icon svg { width: 18px; height: 18px; stroke: #185FA5; fill: none; stroke-width: 1.5; stroke-linecap: round; stroke-linejoin: round; }
                .badge-label { font-size: 11px; font-weight: 500; color: #185FA5; text-transform: uppercase; letter-spacing: 1px; }

                .content h2 { font-size: 22px; font-weight: 500; color: #1A1A18; margin-bottom: 8px; }
                .content .body-text { font-size: 15px; color: #5F5E5A; line-height: 1.6; margin-bottom: 24px; }
                .content .body-text strong { color: #1A1A18; font-weight: 500; }

                .warning { background: #FAEEDA; border-left: 3px solid #BA7517; border-radius: 0 8px 8px 0; padding: 14px 16px; margin-bottom: 28px; display: flex; align-items: flex-start; gap: 10px; }
                .warning svg { width: 18px; height: 18px; stroke: #BA7517; fill: none; stroke-width: 1.5; flex-shrink: 0; margin-top: 1px; }
                .warning p { font-size: 13px; color: #633806; line-height: 1.5; }
                .warning strong { font-weight: 500; }

                .btn-wrap { text-align: center; margin-bottom: 28px; }
                .btn { display: inline-flex; align-items: center; gap: 8px; padding: 14px 32px; background: #0A1626; color: #ffffff; text-decoration: none; border-radius: 8px; font-size: 14px; font-weight: 500; letter-spacing: 0.3px; }
                .btn svg { width: 17px; height: 17px; stroke: #ffffff; fill: none; stroke-width: 1.5; stroke-linecap: round; stroke-linejoin: round; }

                .link-box { background: #F1EFE8; border-radius: 8px; padding: 14px 16px; margin-bottom: 24px; }
                .link-box .link-label { font-size: 12px; color: #888780; margin-bottom: 6px; }
                .link-box .link-row { display: flex; align-items: flex-start; gap: 8px; }
                .link-box .link-row svg { width: 15px; height: 15px; stroke: #888780; fill: none; stroke-width: 1.5; flex-shrink: 0; margin-top: 2px; }
                .link-box a { font-size: 11px; color: #185FA5; font-family: monospace; word-break: break-all; }

                .note { font-size: 13px; color: #888780; line-height: 1.6; }

                .footer { border-top: 1px solid #E0DDD5; padding: 20px 32px; display: flex; justify-content: space-between; align-items: center; gap: 16px; flex-wrap: wrap; }
                .footer p { font-size: 11px; color: #B4B2A9; }
            </style>
        </head>
        <body>
            <div class='wrapper'>

                <div class='header'>
                    <h1>SII Importaciones</h1>
                    <p>Sistema de Administración y Votación</p>
                </div>

                <div class='content'>

                    <div class='badge'>
                        <div class='badge-icon'>
                            <svg viewBox='0 0 24 24'><rect x='3' y='11' width='18' height='11' rx='2'/><path d='M7 11V7a5 5 0 0 1 10 0v4'/></svg>
                        </div>
                        <span class='badge-label'>Recuperación de contraseña</span>
                    </div>

                    <h2>Hola, $nombre</h2>
                    <p class='body-text'>
                        Hemos recibido una solicitud para restablecer la contraseña de tu cuenta de administrador en <strong>SII Importaciones</strong>.
                    </p>

                    <div class='warning'>
                        <svg viewBox='0 0 24 24'><circle cx='12' cy='12' r='10'/><polyline points='12 6 12 12 16 14'/></svg>
                        <p>Este enlace expirará en <strong>1 hora</strong> y solo puede ser usado una vez.</p>
                    </div>

                    <div class='btn-wrap'>
                        <a href='$resetUrl' class='btn' style='color:#ffffff !important;'>
                            <svg viewBox='0 0 24 24'><rect x='3' y='11' width='18' height='11' rx='2'/><path d='M7 11V7a5 5 0 0 1 9.9-1'/></svg>
                            Restablecer contraseña
                        </a>
                    </div>

                    <div class='link-box'>
                        <p class='link-label'>¿Problemas con el botón? Copia este enlace en tu navegador:</p>
                        <div class='link-row'>
                            <svg viewBox='0 0 24 24'><path d='M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71'/><path d='M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71'/></svg>
                            <a href='$resetUrl'>$resetUrl</a>
                        </div>
                    </div>

                    <p class='note'>Si no solicitaste este cambio, puedes ignorar este correo. Tu contraseña permanecerá sin cambios.</p>

                </div>

                <div class='footer'>
                    <p>© 2025 SII Importaciones</p>
                    <p>Mensaje automático — no responder</p>
                </div>

            </div>
        </body>
        </html>
        ";
    }

}