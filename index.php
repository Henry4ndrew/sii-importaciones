<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/app/Helpers/functions.php';
require_once __DIR__ . '/app/Models/Usuario.php';

session_start();

// ============================================
// PROCESAR LOGIN (si es POST)
// ============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    $email = trim($_POST['email'] ?? '');
    
    // Validar email
    if (empty($email)) {
        flash('error', 'Por favor, ingresa tu correo electrónico.');
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        flash('error', 'Por favor, ingresa un correo electrónico válido.');
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }
    
    // Buscar usuario por email
    $usuario = Usuario::buscarPorEmail($email);
    
    // Si no existe, crear el usuario automáticamente
    if (!$usuario) {
        $id = Usuario::crear($email);
        $usuario = Usuario::buscarPorEmail($email);
        flash('exito', '¡Bienvenido! Tu cuenta ha sido creada automáticamente.');
    }
    
    // Iniciar sesión
    $_SESSION['usuario'] = [
        'id' => $usuario['id'],
        'email' => $usuario['email'],
    ];
    
    // Redirigir al dashboard
    header('Location: ' . BASE_URL . '/dashboard');
    exit;
}

// ============================================
// SI YA ESTÁ AUTENTICADO, MOSTRAR DASHBOARD EN LA RAÍZ
// ============================================
if (auth()) {
    require_once __DIR__ . '/app/controllers/ProductoController.php';
    require_once __DIR__ . '/app/Models/Producto.php';
    require_once __DIR__ . '/app/Models/Voto.php';
    
    $controller = new ProductoController();
    $controller->index();
    exit;
}

// ============================================
// MOSTRAR FORMULARIO DE LOGIN (si NO está autenticado)
// ============================================
$titulo = 'Acceder al Sistema';

ob_start();
?>
<div class="max-w-md mx-auto mt-8">
    <div class="bg-white rounded-xl shadow p-8">
        <h1 class="text-2xl font-extrabold text-slate-800 text-center mb-1">Acceder al Sistema</h1>
        <p class="text-slate-500 text-sm text-center mb-6">Ingresa tu correo electrónico para acceder</p>

        <?php if ($flash = getFlash()): ?>
            <div class="mb-4 rounded-lg px-4 py-3 text-sm font-semibold <?= $flash['tipo'] === 'exito' ? 'bg-green-100 text-green-800 border border-green-300' : 'bg-red-100 text-red-800 border border-red-300' ?>">
                <?= e($flash['mensaje']) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="<?= $_SERVER['PHP_SELF'] ?>" class="space-y-4">
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-1">Correo electrónico</label>
                <input type="email" name="email" required
                       placeholder="tu@email.com"
                       class="w-full border border-slate-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-amber-500">
            </div>
            <button class="w-full bg-slate-900 text-white font-bold py-3 rounded-lg hover:bg-slate-700 transition">
                Acceder
            </button>
        </form>

        <div class="mt-4 text-xs text-center text-slate-400 border-t border-slate-200 pt-4">
            <p>💡 Al ingresar tu correo, se creará automáticamente tu cuenta si no existe</p>
        </div>
    </div>
</div>
<?php
$contenido = ob_get_clean();
require_once __DIR__ . '/resources/views/layout.php';