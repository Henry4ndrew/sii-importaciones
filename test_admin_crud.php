<?php
echo "<h1>🔍 Diagnóstico - CRUD de Administradores</h1>";
echo "<hr>";

// Cargar configuración
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/app/Helpers/functions.php';
require_once __DIR__ . '/app/Models/Administrador.php';

echo "<h2>1. Verificar sesión</h2>";
session_start();
echo "Session ID: " . session_id() . "<br>";
echo "Contenido de sesión: <pre>";
print_r($_SESSION);
echo "</pre>";

if (!isset($_SESSION['administrador'])) {
    echo "❌ No hay sesión de administrador activa.<br>";
    echo "👉 <a href='" . url('auth/login') . "'>Iniciar sesión como administrador</a><br>";
} else {
    echo "✅ Sesión de administrador activa: " . $_SESSION['administrador']['email'] . "<br>";
}
echo "<hr>";

echo "<h2>2. Verificar tabla administradores</h2>";
try {
    $stmt = db()->query("SHOW TABLES LIKE 'administradores'");
    if ($stmt->rowCount() > 0) {
        echo "✅ Tabla 'administradores' existe<br>";
        
        // Verificar estructura
        $stmt = db()->query("DESCRIBE administradores");
        $columnas = $stmt->fetchAll();
        echo "<strong>Estructura de la tabla:</strong><br>";
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>Campo</th><th>Tipo</th><th>Null</th><th>Clave</th></tr>";
        foreach ($columnas as $col) {
            echo "<tr>";
            echo "<td>" . $col['Field'] . "</td>";
            echo "<td>" . $col['Type'] . "</td>";
            echo "<td>" . $col['Null'] . "</td>";
            echo "<td>" . $col['Key'] . "</td>";
            echo "</tr>";
        }
        echo "</table><br>";
        
        // Contar administradores
        $stmt = db()->query("SELECT COUNT(*) as total FROM administradores");
        $result = $stmt->fetch();
        echo "Total administradores: " . $result['total'] . "<br>";
        
    } else {
        echo "❌ Tabla 'administradores' NO existe<br>";
        echo "<strong>Ejecuta este SQL:</strong><br>";
        echo "<pre style='background:#f0f0f0;padding:10px;'>";
        echo "CREATE TABLE administradores (\n";
        echo "    id INT AUTO_INCREMENT PRIMARY KEY,\n";
        echo "    nombre VARCHAR(100) NOT NULL,\n";
        echo "    email VARCHAR(100) UNIQUE NOT NULL,\n";
        echo "    password VARCHAR(255) NOT NULL,\n";
        echo "    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP\n";
        echo ");";
        echo "</pre>";
    }
} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage() . "<br>";
}
echo "<hr>";

echo "<h2>3. Simular envío del formulario (POST)</h2>";

// Simular datos del formulario
$testData = [
    'nombre' => 'Test Admin',
    'email' => 'test_admin_' . time() . '@test.com',
    'password' => '123456',
    'password_confirm' => '123456'
];

echo "<strong>Datos de prueba:</strong><br>";
echo "<pre>";
print_r($testData);
echo "</pre>";

echo "<h3>3.1 Verificar validaciones</h3>";

// Validaciones
$errores = [];
if (empty($testData['nombre'])) {
    $errores[] = "❌ Nombre vacío";
} else {
    echo "✅ Nombre: " . $testData['nombre'] . "<br>";
}

if (empty($testData['email'])) {
    $errores[] = "❌ Email vacío";
} elseif (!filter_var($testData['email'], FILTER_VALIDATE_EMAIL)) {
    $errores[] = "❌ Email inválido";
} else {
    echo "✅ Email: " . $testData['email'] . "<br>";
}

if (strlen($testData['password']) < 6) {
    $errores[] = "❌ Contraseña menor a 6 caracteres";
} else {
    echo "✅ Contraseña: " . str_repeat('*', strlen($testData['password'])) . " (" . strlen($testData['password']) . " caracteres)<br>";
}

if ($testData['password'] !== $testData['password_confirm']) {
    $errores[] = "❌ Las contraseñas no coinciden";
} else {
    echo "✅ Contraseñas coinciden<br>";
}

if (empty($errores)) {
    echo "<br><span style='color:green;font-weight:bold;'>✅ Todas las validaciones pasaron</span><br>";
} else {
    echo "<br><span style='color:red;font-weight:bold;'>❌ Errores encontrados:</span><br>";
    foreach ($errores as $error) {
        echo " - $error<br>";
    }
}
echo "<hr>";

echo "<h3>3.2 Verificar si el email ya existe</h3>";
$admin = Administrador::buscarPorEmail($testData['email']);
if ($admin) {
    echo "❌ El email ya está registrado: " . $admin['email'] . "<br>";
} else {
    echo "✅ El email está disponible<br>";
}
echo "<hr>";

echo "<h3>3.3 Simular inserción en la base de datos</h3>";
try {
    $hash = password_hash($testData['password'], PASSWORD_DEFAULT);
    echo "🔑 Hash generado: " . $hash . "<br>";
    
    $stmt = db()->prepare('INSERT INTO administradores (nombre, email, password) VALUES (?, ?, ?)');
    $stmt->execute([$testData['nombre'], $testData['email'], $hash]);
    
    $id = (int) db()->lastInsertId();
    echo "✅ Administrador insertado con ID: " . $id . "<br>";
    
    // Verificar inserción
    $stmt = db()->prepare('SELECT * FROM administradores WHERE id = ?');
    $stmt->execute([$id]);
    $nuevoAdmin = $stmt->fetch();
    echo "✅ Administrador creado:<br>";
    echo "<pre>";
    print_r($nuevoAdmin);
    echo "</pre>";
    
    // Limpiar datos de prueba (opcional)
    // $stmt = db()->prepare('DELETE FROM administradores WHERE id = ?');
    // $stmt->execute([$id]);
    // echo "🗑️ Administrador de prueba eliminado<br>";
    
} catch (PDOException $e) {
    echo "❌ Error al insertar: " . $e->getMessage() . "<br>";
}
echo "<hr>";

echo "<h2>4. Verificar rutas del CRUD</h2>";

$rutas = [
    'Listar administradores' => 'admin/administradores',
    'Crear administrador' => 'admin/administradores/crear',
    'Guardar administrador (POST)' => 'admin/administradores/guardar',
    'Editar administrador' => 'admin/administradores/editar?id=1',
    'Actualizar administrador (POST)' => 'admin/administradores/actualizar',
    'Eliminar administrador' => 'admin/administradores/eliminar?id=1',
];

echo "<ul>";
foreach ($rutas as $nombre => $ruta) {
    $url = url($ruta);
    echo "<li><strong>$nombre:</strong> <a href='$url' target='_blank'>$url</a></li>";
}
echo "</ul>";

echo "<hr>";

echo "<h2>5. Prueba de envío POST simulado</h2>";
?>
<form method="POST" action="<?= url('admin/administradores/guardar') ?>" target="_blank">
    <p><strong>Prueba de envío real:</strong></p>
    <input type="text" name="nombre" value="Test Admin" placeholder="Nombre">
    <input type="email" name="email" value="test_<?= time() ?>@test.com" placeholder="Email">
    <input type="password" name="password" value="123456" placeholder="Contraseña">
    <input type="password" name="password_confirm" value="123456" placeholder="Confirmar">
    <button type="submit">Enviar</button>
</form>

<p><strong>⚠️ Nota:</strong> Al enviar, se abrirá una nueva pestaña. Revisa el resultado y el mensaje flash.</p>

<?php
echo "<hr>";

echo "<h2>6. Verificar archivos necesarios</h2>";

$archivos = [
    '/app/controllers/AdminController.php' => __DIR__ . '/app/controllers/AdminController.php',
    '/app/Models/Administrador.php' => __DIR__ . '/app/Models/Administrador.php',
    '/resources/views/admin/administradores/index.php' => __DIR__ . '/resources/views/admin/administradores/index.php',
    '/resources/views/admin/administradores/crear.php' => __DIR__ . '/resources/views/admin/administradores/crear.php',
    '/resources/views/admin/administradores/editar.php' => __DIR__ . '/resources/views/admin/administradores/editar.php',
];

foreach ($archivos as $nombre => $ruta) {
    if (file_exists($ruta)) {
        echo "✅ $nombre existe<br>";
    } else {
        echo "❌ $nombre NO existe - Ruta: $ruta<br>";
    }
}

echo "<hr>";

echo "<h2>7. Enlaces útiles</h2>";
echo "<ul>";
echo "<li><a href='" . url('admin/administradores') . "'>Lista de administradores</a></li>";
echo "<li><a href='" . url('admin/administradores/crear') . "'>Crear administrador</a></li>";
echo "<li><a href='" . url('auth/login') . "'>Login administrador</a></li>";
echo "</ul>";