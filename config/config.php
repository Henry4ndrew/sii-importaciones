<?php

// Configuración general del sistema
return [
    // Clave de ScraperAPI (https://www.scraperapi.com — plan gratuito disponible).
    // Con la clave puesta, pegar la URL de Alibaba extrae foto, precio y detalles
    // automáticamente, tanto en local como en producción.
    // Sin clave, la extracción directa se intenta igual y el nombre sale de la URL.
    'scraper_api_key' => '5782fa172d094dcf55a54af832470191',

    // Ruta del PHP de línea de comandos (para completar detalles en segundo plano).
    // En Linux/producción normalmente basta con 'php'.
    'php_cli' => 'C:\\xampp\\php\\php.exe', 
    //CPANEL 'php_cli' => '/opt/cpanel/ea-php82/root/usr/bin/php', 
];
