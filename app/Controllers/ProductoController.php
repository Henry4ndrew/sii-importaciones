<?php
require_once __DIR__ . '/../Helpers/Extractor.php'; 


class ProductoController
{
    public function index(): void
    {
        view('productos.index', [
            'titulo' => 'Productos Recientes',
            'productos' => Producto::recientes(),
            'votados' => auth() ? Voto::productosVotadosPor(auth()['id']) : [],
        ]);
    }

    public function ranking(): void
    {
        view('productos.ranking', [
            'titulo' => 'Productos Más Votados (10+ votos)',
            'productos' => Producto::masVotados(10),
            'votados' => auth() ? Voto::productosVotadosPor(auth()['id']) : [],
        ]);
    }

    public function crear(): void
    {
        if (!auth()) {
            flash('error', 'Debes iniciar sesión para publicar productos.');
            redirect('login');
        }

        view('productos.crear', ['titulo' => 'Publicar Nuevo Producto']);
    }

    public function store(): void
    {
        if (!auth()) {
            redirect('login');
        }

        $url = trim($_POST['url'] ?? '');

        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            flash('error', 'Ingresa una URL válida del producto.');
            redirect('productos/crear');
        }

        // Anti-duplicados
        $existente = preg_match('/_(\d{6,})\.html/', $url, $m) ? Producto::buscarPorAlibabaId($m[1]) : null;

        if ($existente && $existente['imagen'] !== null) {
            flash('error', 'Ese producto ya está publicado.');
            redirect('dashboard'); 
        }

        if ($existente) {
            $id = (int) $existente['id'];
            flash('exito', 'Ese producto ya estaba publicado; completando sus detalles…');
        } else {
            // Crear producto con nombre desde URL
            $nombre = Extractor::nombreDesdeUrl($url);
            $id = Producto::crear(auth()['id'], $nombre, $url, null, null);
            flash('exito', '¡Producto publicado! La foto y el precio aparecerán en unos segundos…');
        }

        // Ejecutar en segundo plano con método mejorado
        self::completarEnSegundoPlano($id);
        redirect('dashboard'); // <-- CAMBIADO
    }

    // Método mejorado para ejecutar en segundo plano
    private static function completarEnSegundoPlano(int $productoId): void
    {
        $config = require __DIR__ . '/../../config/config.php';
        $php = $config['php_cli'] ?? 'php';
        $script = realpath(__DIR__ . '/../../scripts/completar_producto.php');
        
        // Verificar que el script existe
        if (!$script || !file_exists($script)) {
            error_log("ERROR: Script no encontrado: " . __DIR__ . '/../../scripts/completar_producto.php');
            return;
        }

        // Verificar que PHP CLI existe
        if (!file_exists($php)) {
            error_log("ERROR: PHP CLI no encontrado: $php");
            // Intentar con 'php' como fallback
            $php = 'php';
        }

        // Crear archivo de log para depuración
        $logFile = __DIR__ . '/../../logs/background_exec.log';
        $logDir = dirname($logFile);
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }

        // Comando para ejecutar en segundo plano
        $cmd = escapeshellarg($php) . ' ' . escapeshellarg($script) . ' ' . $productoId;
        
        if (DIRECTORY_SEPARATOR === '\\') {
            // Windows
            $comando = 'start /B "" ' . $cmd . ' > NUL 2>&1';
            pclose(popen($comando, 'r'));
        } else {
            // Linux/Unix - varias opciones para asegurar ejecución
            $comando = $cmd . ' > ' . escapeshellarg($logFile) . ' 2>&1 &';
            exec($comando);
            
            // También intentar con nohup como respaldo
            $comando2 = 'nohup ' . $cmd . ' >> ' . escapeshellarg($logFile) . ' 2>&1 &';
            exec($comando2);
        }

        // Registrar en log que se ejecutó
        file_put_contents($logFile, date('[Y-m-d H:i:s] ') . "Ejecutado: $cmd\n", FILE_APPEND);
        
        // Pequeña pausa para asegurar que el proceso inicia
        usleep(100000); // 0.1 segundos
    }

    // Recibe los datos capturados por el botón de 1 clic
    public function capturar(): void
    {
        if (!auth()) {
            flash('error', 'Inicia sesión y vuelve a presionar el botón en la página de Alibaba.');
            redirect('login');
        }

        $url = trim($_GET['url'] ?? '');

        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            flash('error', 'El botón no envió una URL válida.');
            redirect('productos/crear');
        }

        $nombre = trim($_GET['nombre'] ?? '') ?: Extractor::nombreDesdeUrl($url);
        $imagen = trim($_GET['imagen'] ?? '');
        $precio = trim($_GET['precio'] ?? '');
        $precioOriginal = trim($_GET['precio_original'] ?? '');
        $oferta = trim($_GET['oferta'] ?? '');
        $pedidoMinimo = trim($_GET['pedido_minimo'] ?? '');

        $imagen = $imagen !== '' && filter_var($imagen, FILTER_VALIDATE_URL) ? mb_substr($imagen, 0, 500) : null;
        $precio = $precio !== '' ? mb_substr($precio, 0, 100) : null;
        $precioOriginal = $precioOriginal !== '' ? mb_substr($precioOriginal, 0, 100) : null;
        $oferta = $oferta !== '' ? mb_substr($oferta, 0, 100) : null;
        $pedidoMinimo = $pedidoMinimo !== '' ? mb_substr($pedidoMinimo, 0, 100) : null;
        $nombre = mb_substr($nombre, 0, 255);

        $existente = preg_match('/_(\d{6,})\.html/', $url, $m) ? Producto::buscarPorAlibabaId($m[1]) : null;

        if ($existente) {
            Producto::actualizarDetalles((int) $existente['id'], $nombre, $imagen, $precio, $precioOriginal, $oferta, $pedidoMinimo);
            flash('exito', '¡Detalles del producto completados (foto, precio y más)!');
        } else {
            Producto::crear(auth()['id'], $nombre, mb_substr($url, 0, 500), $imagen, $precio, $precioOriginal, $oferta, $pedidoMinimo);
            flash('exito', '¡Producto capturado y publicado con todos sus detalles!');
        }

        redirect('dashboard'); 
    }
}