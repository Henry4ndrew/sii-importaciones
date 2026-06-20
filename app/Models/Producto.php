<?php

class Producto
{
    private const SELECT_BASE = '
        SELECT p.*, u.nombre AS publicado_por,
               (SELECT COUNT(*) FROM votos v WHERE v.producto_id = p.id) AS total_votos
        FROM productos p
        JOIN usuarios u ON u.id = p.usuario_id';

    public static function recientes(): array
    {
        return db()->query(self::SELECT_BASE . ' ORDER BY p.created_at DESC')->fetchAll();
    }

    public static function masVotados(int $minimoVotos = 10): array
    {
        $stmt = db()->prepare(
            self::SELECT_BASE . '
            HAVING total_votos >= ?
            ORDER BY total_votos DESC, p.created_at DESC'
        );
        $stmt->execute([$minimoVotos]);
        return $stmt->fetchAll();
    }

    // Busca un producto por el id numérico que Alibaba incluye en la URL (..._1601612407706.html)
    public static function buscarPorAlibabaId(string $alibabaId): ?array
    {
        $stmt = db()->prepare("SELECT * FROM productos WHERE url LIKE ? LIMIT 1");
        $stmt->execute(['%_' . $alibabaId . '.html%']);
        $producto = $stmt->fetch();
        return $producto ?: null;
    }

    // Completa los detalles de un producto ya publicado (lo usa la captura de 1 clic)
    public static function actualizarDetalles(int $id, string $nombre, ?string $imagen, ?string $precio, ?string $precioOriginal, ?string $oferta, ?string $pedidoMinimo, ?array $imagenes = null): void
    {
        $stmt = db()->prepare(
            'UPDATE productos SET nombre = ?, imagen = COALESCE(?, imagen), imagenes = COALESCE(?, imagenes),
             precio = COALESCE(?, precio), precio_original = COALESCE(?, precio_original), oferta = COALESCE(?, oferta),
             pedido_minimo = COALESCE(?, pedido_minimo) WHERE id = ?'
        );
        $stmt->execute([$nombre, $imagen, $imagenes ? json_encode($imagenes) : null, $precio, $precioOriginal, $oferta, $pedidoMinimo, $id]);
    }

    public static function crear(int $usuarioId, string $nombre, string $url, ?string $imagen, ?string $precio, ?string $precioOriginal = null, ?string $oferta = null, ?string $pedidoMinimo = null): int
    {
        $stmt = db()->prepare(
            'INSERT INTO productos (usuario_id, nombre, url, imagen, precio, precio_original, oferta, pedido_minimo) VALUES (?, ?, ?, ?, ?, ?, ?, ?)'
        );
        $stmt->execute([$usuarioId, $nombre, $url, $imagen, $precio, $precioOriginal, $oferta, $pedidoMinimo]);
        return (int) db()->lastInsertId();
    }
}
