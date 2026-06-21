<?php

// ============================================
// DEFINIR BASE_URL - DEBE IR ANTES DEL RETURN
// ============================================

// Cambia según entorno:
// Local:   define('BASE_URL', '/sii-importaciones');
// Producción: define('BASE_URL', '');
define('BASE_URL', '/sii-importaciones');

// Configuración general del sistema
return [
    // Clave de ScraperAPI
    'scraper_api_key' => '5782fa172d094dcf55a54af832470191',

    // Ruta del PHP de línea de comandos
    'php_cli' => 'C:\\xampp\\php\\php.exe', 
    // 'php_cli' => '/opt/cpanel/ea-php82/root/usr/bin/php', // CPANEL
];