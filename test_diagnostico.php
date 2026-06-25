<?php
/**
 * ARCHIVO DE DIAGNÓSTICO PARA EL PROBLEMA DEL LOGIN
 * 
 * Este archivo ayuda a identificar por qué falla el POST cuando se deshabilita el login
 */

echo "<!DOCTYPE html>
<html>
<head>
    <title>Diagnóstico de Configuración del Login</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        h1 { color: #333; border-bottom: 2px solid #007bff; padding-bottom: 10px; }
        .section { margin: 20px 0; padding: 15px; background: #f8f9fa; border-radius: 5px; border-left: 4px solid #007bff; }
        .success { border-left-color: #28a745; }
        .error { border-left-color: #dc3545; }
        .warning { border-left-color: #ffc107; }
        pre { background: #333; color: #fff; padding: 10px; border-radius: 5px; overflow-x: auto; }
        .test-form { background: #e9ecef; padding: 15px; border-radius: 5px; margin: 15px 0; }
        .test-form input, .test-form textarea { width: 100%; padding: 8px; margin: 5px 0; border: 1px solid #ddd; border-radius: 4px; }
        .test-form button { background: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 4px; cursor: pointer; }
        .test-form button:hover { background: #0056b3; }
        table { width: 100%; border-collapse: collapse; }
        td, th { padding: 8px; border: 1px solid #ddd; text-align: left; }
        th { background: #007bff; color: white; }
        .badge { display: inline-block; padding: 3px 8px; border-radius: 3px; font-size: 12px; font-weight: bold; }
        .badge-success { background: #28a745; color: white; }
        .badge-danger { background: #dc3545; color: white; }
        .badge-warning { background: #ffc107; color: black; }
    </style>
</head>
<body>
    <div class=\"container\">
        <h1>🔍 Diagnóstico: Configuración del Login</h1>
        
        <div class=\"section\">
            <h2>📋 Información del Sistema</h2>
            <table>
                <tr><td><strong>PHP Version:</strong></td><td>" . phpversion() . "</td></tr>
                <tr><td><strong>Document Root:</strong></td><td>" . $_SERVER['DOCUMENT_ROOT'] . "</td></tr>
                <tr><td><strong>Script Directory:</strong></td><td>" . __DIR__ . "</td></tr>
                <tr><td><strong>Request Method:</strong></td><td>" . $_SERVER['REQUEST_METHOD'] . "</td></tr>
                <tr><td><strong>Request URI:</strong></td><td>" . ($_SERVER['REQUEST_URI'] ?? 'N/A') . "</td></tr>
            </table>
        </div>";

// ============================================
// 1. VERIFICAR CONEXIÓN A BASE DE DATOS
// ============================================
echo "<div class=\"section\">
    <h2>🗄️ Verificación de Base de Datos</h2>";

try {
    require_once __DIR__ . '/config/database.php';
    $db = db();
    echo "<p class=\"success\">✅ Conexión a la base de datos exitosa</p>";
    
    // Verificar tabla configuraciones_login
    try {
        $stmt = $db->query("SHOW TABLES LIKE 'configuraciones_login'");
        if ($stmt->rowCount() > 0) {
            echo "<p class=\"success\">✅ Tabla 'configuraciones_login' existe</p>";
            
            // Mostrar datos actuales
            $stmt = $db->query("SELECT * FROM configuraciones_login LIMIT 1");
            $config = $stmt->fetch();
            if ($config) {
                echo "<p class=\"success\">✅ Datos de configuración encontrados:</p>";
                echo "<pre>" . print_r($config, true) . "</pre>";
            } else {
                echo "<p class=\"warning\">⚠️ No hay datos en la tabla configuraciones_login</p>";
            }
        } else {
            echo "<p class=\"error\">❌ Tabla 'configuraciones_login' NO existe</p>";
        }
    } catch (PDOException $e) {
        echo "<p class=\"error\">❌ Error al verificar tabla: " . $e->getMessage() . "</p>";
    }
} catch (Exception $e) {
    echo "<p class=\"error\">❌ Error de conexión a la base de datos: " . $e->getMessage() . "</p>";
}
echo "</div>";

// ============================================
// 2. VERIFICAR ARCHIVOS NECESARIOS
// ============================================
echo "<div class=\"section\">
    <h2>📁 Verificación de Archivos</h2>
    <table>";

$archivos = [
    'Modelo ConfiguracionLogin' => 'app/Models/ConfiguracionLogin.php',
    'Controlador PublicacionController' => 'app/controllers/PublicacionController.php',
    'Vista de Publicaciones' => 'resources/views/admin/publicaciones/index.php',
    'Archivo de Rutas' => 'routes/web.php',
    'Bootstrap' => 'bootstrap/app.php',
];

foreach ($archivos as $nombre => $ruta) {
    $existe = file_exists(__DIR__ . '/' . $ruta);
    echo "<tr>
        <td><strong>" . $nombre . "</strong></td>
        <td>" . $ruta . "</td>
        <td>" . ($existe ? "<span class=\"badge badge-success\">✅ EXISTE</span>" : "<span class=\"badge badge-danger\">❌ NO EXISTE</span>") . "</td>
    </tr>";
}
echo "</table></div>";

// ============================================
// 3. SIMULAR POST
// ============================================
echo "<div class=\"section\">
    <h2>🧪 Simular POST de Configuración</h2>
    <div class=\"test-form\">
        <p><strong>Esta sección simula el envío del formulario de configuración</strong></p>
        <form method=\"POST\" action=\"test_diagnostico.php\">
            <input type=\"hidden\" name=\"login_habilitado\" value=\"0\">
            <input type=\"hidden\" name=\"mensaje\" value=\"Test de diagnóstico: Login deshabilitado\">
            <button type=\"submit\">🔧 Simular POST (Deshabilitar Login)</button>
        </form>
        <br>
        <form method=\"POST\" action=\"test_diagnostico.php\">
            <input type=\"hidden\" name=\"login_habilitado\" value=\"1\">
            <input type=\"hidden\" name=\"mensaje\" value=\"Test de diagnóstico: Login habilitado\">
            <button type=\"submit\">🔧 Simular POST (Habilitar Login)</button>
        </form>
    </div>";

// Procesar POST simulado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login_habilitado']) && isset($_POST['mensaje'])) {
    echo "<div class=\"section success\">
        <h3>✅ POST Recibido correctamente</h3>
        <p><strong>Valores recibidos:</strong></p>
        <pre>";
    echo "login_habilitado: " . ($_POST['login_habilitado'] == 1 ? 'HABILITADO (1)' : 'DESHABILITADO (0)') . "\n";
    echo "mensaje: " . $_POST['mensaje'] . "\n";
    echo "REQUEST_URI: " . $_SERVER['REQUEST_URI'] . "\n";
    echo "SCRIPT_NAME: " . $_SERVER['SCRIPT_NAME'];
    echo "</pre>";
    
    // Intentar procesar con la lógica real
    echo "<p><strong>Intentando procesar con la lógica real...</strong></p>";
    try {
        require_once __DIR__ . '/app/Models/ConfiguracionLogin.php';
        $habilitado = $_POST['login_habilitado'] == 1;
        $mensaje = $_POST['mensaje'];
        
        $resultado = ConfiguracionLogin::actualizar($habilitado, $mensaje);
        if ($resultado) {
            echo "<p class=\"success\">✅ Configuración actualizada exitosamente en la base de datos</p>";
            
            // Mostrar datos actualizados
            $config = ConfiguracionLogin::getConfig();
            echo "<p><strong>Configuración actual:</strong></p>";
            echo "<pre>" . print_r($config, true) . "</pre>";
        } else {
            echo "<p class=\"error\">❌ Error al actualizar la configuración</p>";
        }
    } catch (Exception $e) {
        echo "<p class=\"error\">❌ Error: " . $e->getMessage() . "</p>";
        echo "<pre>" . $e->getTraceAsString() . "</pre>";
    }
}
echo "</div>";

// ============================================
// 4. VERIFICAR REDIRECCIONES
// ============================================
echo "<div class=\"section\">
    <h2>🔄 Verificación de Redirecciones</h2>
    <table>
        <tr>
            <th>Desde</th>
            <th>Hacia</th>
            <th>Estado</th>
        </tr>
        <tr>
            <td><code>admin/publicaciones/configuracion/actualizar</code></td>
            <td><code>admin/publicaciones</code></td>
            <td id=\"redirect-status\"><span class=\"badge badge-warning\">⏳ Pendiente de prueba</span></td>
        </tr>
    </table>
    <p><strong>Prueba de redirección:</strong></p>
    <a href=\"" . ($_SERVER['REQUEST_URI'] ?? '') . "\" target=\"_blank\">Refrescar página</a>
</div>";

// ============================================
// 5. DIAGNÓSTICO DEL PROBLEMA
// ============================================
echo "<div class=\"section error\">
    <h2>⚠️ Diagnóstico del Problema</h2>
    <div id=\"diagnostico\">
        <p><strong>Problema reportado:</strong> El POST funciona cuando se HABILITA el login, pero falla cuando se DESHABILITA.</p>
        <p><strong>Posibles causas:</strong></p>
        <ul>
            <li><strong>El checkbox no envía valor cuando está desmarcado</strong> - En HTML, un checkbox desmarcado NO se envía en el POST</li>
            <li><strong>La ruta está siendo interceptada por el router</strong> - El router de Laravel podría estar procesando la ruta</li>
            <li><strong>Error en la redirección después del POST</strong> - La redirección podría tener un error</li>
        </ul>
    </div>
</div>";

// ============================================
// 6. PRUEBA CON CHECKBOX (SOLUCIÓN)
// ============================================
echo "<div class=\"section warning\">
    <h2>💡 SOLUCIÓN PROPUESTA</h2>
    <p><strong>El problema más probable:</strong> Cuando el checkbox está DESMARCADO, no se envía en el POST.</p>
    <p><strong>Solución:</strong> Agregar un campo oculto que siempre se envíe.</p>
    
    <h3>Ejemplo corregido:</h3>
    <pre>
&lt;!-- Campo oculto que SIEMPRE se envía --&gt;
&lt;input type=\"hidden\" name=\"login_habilitado\" value=\"0\"&gt;

&lt;!-- Checkbox que SOLO se envía cuando está marcado --&gt;
&lt;input type=\"checkbox\" name=\"login_habilitado\" value=\"1\"&gt;
    </pre>
    
    <h3>En tu vista actual, el checkbox es:</h3>
    <pre>
&lt;input type=\"checkbox\" name=\"login_habilitado\" value=\"1\" ... &gt;
    </pre>
    
    <p><strong>Debe cambiarse a:</strong></p>
    <pre>
&lt;input type=\"hidden\" name=\"login_habilitado\" value=\"0\"&gt;
&lt;input type=\"checkbox\" name=\"login_habilitado\" value=\"1\" ... &gt;
    </pre>
</div>";

// ============================================
// 7. PRUEBA CON CREDENCIALES
// ============================================
echo "<div class=\"section\">
    <h2>🔑 Información de Sesión</h2>";
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (isset($_SESSION['administrador'])) {
    echo "<p class=\"success\">✅ Sesión de administrador activa</p>";
    echo "<pre>" . print_r($_SESSION['administrador'], true) . "</pre>";
} else {
    echo "<p class=\"warning\">⚠️ No hay sesión de administrador activa</p>";
}
echo "</div>";

// ============================================
// 8. DEBUG DE .HTACCESS
// ============================================
echo "<div class=\"section\">
    <h2>📝 Archivo .htaccess</h2>
    <p><strong>Ubicación:</strong> " . __DIR__ . "/.htaccess</p>";
if (file_exists(__DIR__ . '/.htaccess')) {
    $htaccess = file_get_contents(__DIR__ . '/.htaccess');
    echo "<p class=\"success\">✅ Archivo .htaccess existe</p>";
    echo "<details><summary>Ver contenido</summary><pre>" . htmlspecialchars($htaccess) . "</pre></details>";
} else {
    echo "<p class=\"error\">❌ Archivo .htaccess NO existe</p>";
}
echo "</div>";

echo "
    </div>
</body>
</html>";