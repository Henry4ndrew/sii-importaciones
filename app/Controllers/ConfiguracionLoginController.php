<?php
require_once __DIR__ . '/../Models/ConfiguracionLogin.php';

class ConfiguracionLoginController
{
    /**
     * Mostrar la página de configuración del login
     */
    public function index(): void
    {
        if (!isset($_SESSION['administrador'])) {
            header('Location: ' . url('auth/login'));
            exit;
        }

        $config = ConfiguracionLogin::getConfig();
        
        adminView('admin/configuracion/login', [
            'titulo' => 'Configuración de Acceso de Usuarios',
            'admin' => $_SESSION['administrador'],
            'config' => $config,
            'activePage' => 'configuracion'
        ]);
    }
    
    /**
     * Actualizar la configuración del login
     */
    public function update(): void
    {
        if (!isset($_SESSION['administrador'])) {
            header('Location: ' . url('auth/login'));
            exit;
        }

        $habilitado = isset($_POST['login_habilitado']) && $_POST['login_habilitado'] == 1;
        $mensaje = trim($_POST['mensaje'] ?? 'El sistema de acceso para usuarios se encuentra temporalmente deshabilitado. Por favor, intenta más tarde.');
        
        if (empty($mensaje)) {
            $mensaje = 'El sistema de acceso para usuarios se encuentra temporalmente deshabilitado. Por favor, intenta más tarde.';
        }
        
        $resultado = ConfiguracionLogin::actualizar($habilitado, $mensaje);
        
        if ($resultado) {
            flash('exito', 'Configuración del login actualizada exitosamente.');
        } else {
            flash('error', 'Error al actualizar la configuración del login.');
        }
        
        header('Location: ' . url('admin/configuracion/login'));
        exit;
    }
}