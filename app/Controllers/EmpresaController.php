<?php

class EmpresaController
{
    /**
     * Mostrar la página de Empresa (vista)
     */
    public function index(): void
    {
        if (!isset($_SESSION['administrador'])) {
            header('Location: ' . url('auth/login'));
            exit;
        }

        $empresa = Empresa::get();
        $totalAdministradores = $this->getTotalAdministradores();

        adminView('admin/empresa/index', [
            'titulo' => 'Gestión de Empresa',
            'admin' => $_SESSION['administrador'],
            'empresa' => $empresa,
            'totalAdministradores' => $totalAdministradores,
            'activePage' => 'empresa'
        ]);
    }

    /**
     * Actualizar los datos de Empresa
     */
    public function update(): void
    {
        if (!isset($_SESSION['administrador'])) {
            header('Location: ' . url('auth/login'));
            exit;
        }

        // Procesar WhatsApp - agregar +591 si no está presente
        $whatsapp = trim($_POST['whatsapp'] ?? '');
        if (!empty($whatsapp)) {
            // Eliminar cualquier prefijo existente
            $whatsapp = preg_replace('/^\+591/', '', $whatsapp);
            // Eliminar espacios y caracteres no numéricos
            $whatsapp = preg_replace('/[^0-9]/', '', $whatsapp);
            // Agregar +591
            if (!empty($whatsapp)) {
                $whatsapp = '+591' . $whatsapp;
            }
        }

        // Recolectar datos del formulario
        $data = [
            'descripcion_corporativa' => trim($_POST['descripcion_corporativa'] ?? ''),
            'email_principal' => trim($_POST['email_principal'] ?? ''),
            'direccion_textual' => trim($_POST['direccion_textual'] ?? ''),
            'enlace_gps' => trim($_POST['enlace_gps'] ?? ''),
            'facebook' => trim($_POST['facebook'] ?? ''),
            'instagram' => trim($_POST['instagram'] ?? ''),
            'tiktok' => trim($_POST['tiktok'] ?? ''),
            'youtube' => trim($_POST['youtube'] ?? ''),
            'whatsapp' => $whatsapp,
        ];

        try {
            Empresa::update($data);
            flash('exito', 'Datos de Empresa actualizados exitosamente.');
        } catch (PDOException $e) {
            flash('error', 'Error al actualizar: ' . $e->getMessage());
        }
        
        header('Location: ' . url('admin/empresa'));
        exit;
    }

    /**
     * Obtener total de administradores
     */
    private function getTotalAdministradores(): int
    {
        $stmt = db()->query('SELECT COUNT(*) as total FROM administradores');
        $result = $stmt->fetch();
        return (int) $result['total'];
    }
}