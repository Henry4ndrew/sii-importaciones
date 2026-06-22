<?php
echo "<h1>🔍 Test de Datos de Empresa</h1>";
echo "<hr>";

// Cargar configuración
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/app/Helpers/functions.php';

echo "<h2>1. Verificar conexión a la base de datos</h2>";
try {
    $stmt = db()->query("SELECT 1");
    echo "✅ Conexión a BD exitosa<br>";
} catch (Exception $e) {
    echo "❌ Error de conexión: " . $e->getMessage() . "<br>";
}

echo "<hr>";

echo "<h2>2. Verificar tabla empresa</h2>";
try {
    $stmt = db()->query("SHOW TABLES LIKE 'empresa'");
    if ($stmt->rowCount() > 0) {
        echo "✅ Tabla 'empresa' existe<br>";
        
        // Ver estructura
        $stmt = db()->query("DESCRIBE empresa");
        $columnas = $stmt->fetchAll();
        echo "<strong>Estructura de la tabla:</strong><br>";
        echo "<table border='1' cellpadding='5' cellspacing='0'>";
        echo "<tr><th>Campo</th><th>Tipo</th></tr>";
        foreach ($columnas as $col) {
            echo "<tr><td>" . $col['Field'] . "</td><td>" . $col['Type'] . "</td></tr>";
        }
        echo "</table><br>";
    } else {
        echo "❌ Tabla 'empresa' NO existe<br>";
    }
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "<br>";
}

echo "<hr>";

echo "<h2>3. Datos de la tabla empresa</h2>";
try {
    $stmt = db()->query("SELECT * FROM empresa LIMIT 1");
    $empresa = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($empresa) {
        echo "✅ Datos encontrados:<br>";
        echo "<table border='1' cellpadding='5' cellspacing='0'>";
        foreach ($empresa as $key => $value) {
            echo "<tr>";
            echo "<td><strong>" . $key . "</strong></td>";
            echo "<td>" . htmlspecialchars($value ?? 'NULL') . "</td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "❌ No hay datos en la tabla empresa<br>";
        echo "<strong>Ejecuta este SQL para insertar datos de ejemplo:</strong><br>";
        echo "<pre style='background:#f0f0f0;padding:10px;'>";
        echo "INSERT INTO empresa (descripcion_corporativa, email_principal, direccion_textual, enlace_gps, facebook, instagram, tiktok, youtube, whatsapp) VALUES (\n";
        echo "  'SII Importaciones es una empresa dedicada a la importación de productos de alta calidad desde China.',\n";
        echo "  'contacto@willsimport.com',\n";
        echo "  'Av. Principal 123, Lima, Perú',\n";
        echo "  'https://maps.google.com/?q=-12.0464,-77.0428',\n";
        echo "  'https://facebook.com/siiimportaciones',\n";
        echo "  'https://instagram.com/siiimportaciones',\n";
        echo "  'https://tiktok.com/@siiimportaciones',\n";
        echo "  'https://youtube.com/siiimportaciones',\n";
        echo "  '76543210'\n";
        echo ");";
        echo "</pre>";
    }
} catch (Exception $e) {
    echo "❌ Error al consultar: " . $e->getMessage() . "<br>";
}

echo "<hr>";

echo "<h2>4. Verificar modelo Empresa</h2>";
$empresaPath = __DIR__ . '/app/Models/Empresa.php';
if (file_exists($empresaPath)) {
    echo "✅ Archivo existe: $empresaPath<br>";
    require_once $empresaPath;
    if (class_exists('Empresa')) {
        echo "✅ Clase Empresa existe<br>";
        
        try {
            $empresaData = Empresa::get();
            if ($empresaData) {
                echo "✅ Empresa::get() devuelve datos:<br>";
                echo "<pre>";
                print_r($empresaData);
                echo "</pre>";
            } else {
                echo "❌ Empresa::get() devuelve NULL<br>";
            }
        } catch (Exception $e) {
            echo "❌ Error en Empresa::get(): " . $e->getMessage() . "<br>";
        }
    } else {
        echo "❌ Clase Empresa NO existe<br>";
    }
} else {
    echo "❌ Archivo NO existe: $empresaPath<br>";
}

echo "<hr>";

echo "<h2>5. Verificar carga en layout.php</h2>";
echo "<p>Para verificar que el layout está cargando los datos, revisa el footer de la página principal.</p>";

echo "<h3>Enlaces útiles:</h3>";
echo "<ul>";
echo "<li><a href='" . url('/') . "'>Página de inicio</a></li>";
echo "<li><a href='" . url('admin/empresa') . "'>Admin - Empresa</a></li>";
echo "</ul>";