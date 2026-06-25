# SII IMPORTACIONES — Sistema de Votación de Productos

Sistema de votación comunitaria para importaciones desde China hecho en **PHP puro con estructura tipo Laravel**, **MySQL (phpMyAdmin)** y **Tailwind CSS por CDN**.

## Funcionalidades

- Registro e inicio de sesión de usuarios (contraseñas hasheadas).
- **Captura de 1 clic (recomendado)**: botón de marcador que, desde la página
  del producto en Alibaba, captura foto, precio, precio original y descuento,
  y publica automáticamente. Se instala arrastrándolo a la barra de marcadores
  (está en la página "Publicar").
- **Publicar solo con la URL**: el sistema extrae los datos automáticamente
  (Open Graph). Si Alibaba bloquea la lectura (anti-bots por IP), el nombre se
  genera desde la propia URL y el producto se publica igual, sin pasos manuales.
- **Extracción completa con la URL (para producción)**: con la clave de
  https://www.scraperapi.com en `config/config.php` (`scraper_api_key`), pegar la
  URL publica el producto AL INSTANTE y la foto, el precio (rango limpio) y el
  pedido mínimo se completan en segundo plano (scripts/completar_producto.php);
  la página se refresca sola hasta que llegan. Anti-duplicados por el id numérico
  de la URL de Alibaba: repetir una URL no crea otra tarjeta. Funciona igual
  deployado (ajustar `php_cli` en config si el binario no es `php`).
- Votación: **un voto por usuario por producto** (garantizado con índice UNIQUE en la BD).
- "Productos Recientes": listado de lo último publicado.
- "Productos Más Votados (10+ votos)": ranking filtrado y ordenado por votos.

## Instalación (XAMPP)

1. Copiar la carpeta `sii-importaciones` en `C:\xampp\htdocs\`.
2. Iniciar **Apache** y **MySQL** desde el Panel de Control de XAMPP.
3. Abrir phpMyAdmin (http://localhost/phpmyadmin) → pestaña **Importar** →
   seleccionar `database.sql` → Continuar. (Crea la BD `sistema_votacion` con sus 3 tablas.)
4. Entrar a **http://localhost/sii-importaciones/**




## Base de datos
Ruta: config/database.php
<?php

function db(): PDO
{
    static $pdo = null;

    if ($pdo === null) {
        try {
            $pdo = new PDO(
                'mysql:host=localhost;dbname=sistema_votacion;charset=utf8mb4',
                'root',
                '',
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ]
            );
        } catch (PDOException $e) {
            // Si falla, mostrar error amigable
            die('Error de conexión a la base de datos. Por favor, verifica la configuración.');
        }
    }

    return $pdo;
}

## Datos globales
Ruta: config/config.php
<?php

// ============================================
// PREVENIR DEFINICIÓN MÚLTIPLE DE CONSTANTES
// ============================================

// ============================================
// DEFINIR BASE_URL
// ============================================
if (!defined('BASE_URL')) {
    // Detectar automáticamente el entorno
    $is_local = strpos($_SERVER['HTTP_HOST'] ?? '', 'localhost') !== false || 
                strpos($_SERVER['HTTP_HOST'] ?? '', '127.0.0.1') !== false;

    if ($is_local) {
        define('BASE_URL', '/sii-importaciones');
    } else {
        define('BASE_URL', ''); // Vacío en producción
    }
}

// ============================================
// DEFINIR BASE_URL_FULL
// ============================================
if (!defined('BASE_URL_FULL')) {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';

    if ($is_local ?? false) {
        define('BASE_URL_FULL', $protocol . $host . BASE_URL);
    } else {
        define('BASE_URL_FULL', 'https://tudominio.com');
    }
}

// ============================================
// CONFIGURACIÓN DE CORREO (SMTP)
// ============================================
if (!defined('SMTP_HOST')) define('SMTP_HOST', 'mail.tudominio.com');
if (!defined('SMTP_USERNAME')) define('SMTP_USERNAME', 'usuario@tudominio.com');
if (!defined('SMTP_PASSWORD')) define('SMTP_PASSWORD', 'contrasenaGmailCorporativo');
if (!defined('SMTP_PORT')) define('SMTP_PORT', 465);
if (!defined('SMTP_SECURE')) define('SMTP_SECURE', 'ssl');
if (!defined('SMTP_FROM_EMAIL')) define('SMTP_FROM_EMAIL', 'usuario@tudominio.com');
if (!defined('SMTP_FROM_NAME')) define('SMTP_FROM_NAME', 'Nombre visible Correo');

// Configuración general del sistema
return [
    'scraper_api_key' => 'clave numerica scraper',
    'php_cli' => '/opt/cpanel/ea-php82/root/usr/bin/php', //ruta para php 8.2
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