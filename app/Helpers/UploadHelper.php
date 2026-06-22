<?php

class UploadHelper
{
    /**
     * Subir una imagen al servidor
     */
    public static function uploadImage(array $file, string $carpeta = 'portadas'): ?string
    {
        // Validar que no haya errores
        if ($file['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        // Validar tamaño (máximo 5MB)
        if ($file['size'] > 5 * 1024 * 1024) {
            return null;
        }

        // Validar tipo de archivo
        $tiposPermitidos = ['image/jpeg', 'image/png', 'image/webp', 'image/avif', 'image/gif'];
        if (!in_array($file['type'], $tiposPermitidos)) {
            return null;
        }

        // Generar nombre único
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $nombreArchivo = uniqid('portada_') . '.' . $extension;

        // Crear carpeta si no existe
        $rutaDestino = __DIR__ . '/../../public/img/' . $carpeta . '/';
        if (!is_dir($rutaDestino)) {
            mkdir($rutaDestino, 0755, true);
        }

        // Mover archivo
        $rutaCompleta = $rutaDestino . $nombreArchivo;
        if (move_uploaded_file($file['tmp_name'], $rutaCompleta)) {
            return $carpeta . '/' . $nombreArchivo;
        }

        return null;
    }

    /**
     * Eliminar una imagen del servidor
     */
    public static function deleteImage(string $rutaImagen): bool
    {
        if (empty($rutaImagen)) {
            return true;
        }

        $rutaCompleta = __DIR__ . '/../../public/img/' . $rutaImagen;
        if (file_exists($rutaCompleta)) {
            return unlink($rutaCompleta);
        }

        return true;
    }
}