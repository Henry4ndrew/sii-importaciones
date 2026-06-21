<?php
echo "<h1>Test de Login</h1>";
echo "<pre>";
echo "REQUEST_METHOD: " . $_SERVER['REQUEST_METHOD'] . "\n";
echo "REQUEST_URI: " . $_SERVER['REQUEST_URI'] . "\n";
echo "POST data: " . print_r($_POST, true) . "\n";
echo "</pre>";

// Si es POST, procesar
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    echo "<h2>Datos recibidos:</h2>";
    echo "Email: " . ($_POST['email'] ?? 'NO ENVIADO') . "<br>";
    echo "Password: " . ($_POST['password'] ?? 'NO ENVIADO') . "<br>";
}
?>