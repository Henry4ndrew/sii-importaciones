<?php

// ============================================
// DEFINIR BASE_URL - DEBE IR ANTES DEL RETURN
// ============================================

// Cambia según entorno:
// Local:   define('BASE_URL', '/sii-importaciones');
// Producción: define('BASE_URL', '');
if (!defined('BASE_URL')) {
    define('BASE_URL', '/sii-importaciones');
}

// ============================================
// DEFINIR BASE_URL_FULL - PARA URLs ABSOLUTAS (EMAILS)
// ============================================

// Detectar el host automáticamente
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
$host = $_SERVER['HTTP_HOST'] ?? 'localhost';

// Para producción (dominio real)
if (strpos($host, 'sii-importaciones.net') !== false) {
    define('BASE_URL_FULL', 'https://sii-importaciones.net');
} else {
    // Para local (localhost)
    define('BASE_URL_FULL', 'http://localhost' . BASE_URL);
}

// ============================================
// CONFIGURACIÓN DE CORREO (SMTP)
// ============================================
define('SMTP_HOST', 'mail.sii-importaciones.net');
define('SMTP_USERNAME', 'info@sii-importaciones.net');
define('SMTP_PASSWORD', 'luisalberto2024');
define('SMTP_PORT', 465);
define('SMTP_SECURE', 'ssl');
define('SMTP_FROM_EMAIL', 'info@sii-importaciones.net');
define('SMTP_FROM_NAME', 'SII Importaciones');



// Configuración general del sistema
return [
    // Clave de ScraperAPI
    'scraper_api_key' => '5782fa172d094dcf55a54af832470191',

    // Ruta del PHP de línea de comandos
    'php_cli' => 'C:\\xampp\\php\\php.exe', 
    // 'php_cli' => '/opt/cpanel/ea-php82/root/usr/bin/php', // CPANEL

    // Configuración de correo
    'mail' => [
        'host' => SMTP_HOST,
        'username' => SMTP_USERNAME,
        'password' => SMTP_PASSWORD,
        'port' => SMTP_PORT,
        'secure' => SMTP_SECURE,
        'from_email' => SMTP_FROM_EMAIL,
        'from_name' => SMTP_FROM_NAME,
    ],
];