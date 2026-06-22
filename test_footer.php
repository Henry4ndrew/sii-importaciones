<?php
echo "<h1>🔍 Test de Footer - Layout</h1>";
echo "<hr>";

// Cargar configuración
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/app/Helpers/functions.php';

echo "<h2>1. Verificar modelo Empresa</h2>";
require_once __DIR__ . '/app/Models/Empresa.php';

if (class_exists('Empresa')) {
    echo "✅ Clase Empresa existe<br>";
    $empresaData = Empresa::get();
    
    if ($empresaData) {
        echo "✅ Datos obtenidos:<br>";
        echo "<pre>";
        print_r($empresaData);
        echo "</pre>";
        
        echo "<h2>2. Datos procesados para el footer</h2>";
        
        // Procesar WhatsApp
        $whatsappMostrar = '';
        $whatsappNumero = '';
        if (!empty($empresaData['whatsapp'])) {
            $whatsappNumero = preg_replace('/[^0-9]/', '', $empresaData['whatsapp']);
            if (strpos($empresaData['whatsapp'], '+591') === 0) {
                $whatsappMostrar = $empresaData['whatsapp'];
            } else {
                $whatsappMostrar = '+591 ' . $whatsappNumero;
            }
        }
        
        echo "<h3>WhatsApp:</h3>";
        echo "Número original: " . ($empresaData['whatsapp'] ?? 'vacío') . "<br>";
        echo "Número limpio: " . $whatsappNumero . "<br>";
        echo "Mostrar: " . $whatsappMostrar . "<br>";
        
        echo "<h3>Email principal:</h3>";
        echo $empresaData['email_principal'] ?: 'vacío' . "<br>";
        
        echo "<h3>Dirección:</h3>";
        echo $empresaData['direccion_textual'] ?: 'vacío' . "<br>";
        
        echo "<h3>Enlace GPS:</h3>";
        echo $empresaData['enlace_gps'] ?: 'vacío' . "<br>";
        
        echo "<h3>Redes Sociales:</h3>";
        echo "Facebook: " . ($empresaData['facebook'] ?: 'vacío') . "<br>";
        echo "Instagram: " . ($empresaData['instagram'] ?: 'vacío') . "<br>";
        echo "TikTok: " . ($empresaData['tiktok'] ?: 'vacío') . "<br>";
        echo "YouTube: " . ($empresaData['youtube'] ?: 'vacío') . "<br>";
        
    } else {
        echo "❌ No hay datos<br>";
    }
} else {
    echo "❌ Clase Empresa NO existe<br>";
}

echo "<hr>";

echo "<h2>3. Verificar ruta del logo</h2>";
$logoPath = __DIR__ . '/public/img/logo-SII.avif';
if (file_exists($logoPath)) {
    echo "✅ Logo existe en: $logoPath<br>";
    echo '<img src="' . url('public/img/logo-SII.avif') . '" alt="Logo" style="max-height:50px;">';
} else {
    echo "❌ Logo NO existe en: $logoPath<br>";
}

echo "<hr>";

echo "<h2>4. Enlaces útiles</h2>";
echo "<ul>";
echo "<li><a href='" . url('/') . "'>Página de inicio</a></li>";
echo "<li><a href='" . url('admin/empresa') . "'>Admin - Empresa</a></li>";
echo "</ul>";