# WILLS IMPORT — Sistema de Votación de Productos

Sistema de votación comunitaria para importaciones desde China, inspirado en
https://sistemadevotacionwills.blogspot.com/

Hecho en **PHP puro con estructura tipo Laravel**, **MySQL (phpMyAdmin)** y **Tailwind CSS por CDN**.

## Funcionalidades

- Registro e inicio de sesión de usuarios (contraseñas hasheadas).
- **Captura de 1 clic (recomendado)**: botón de marcador que, desde la página
  del producto en Alibaba, captura foto, precio, precio original y descuento,
  y publica automáticamente. Se instala arrastrándolo a la barra de marcadores
  (está en la página "Publicar").
- **Publicar solo con la URL**: el sistema extrae los datos automáticamente
  (Open Graph). Si Alibaba bloquea la lectura (anti-bots por IP), el nombre se
  genera desde la propia URL y el producto se publica igual, sin pasos manuales.
- **Extracción completa con la URL (para producción)**: con la clave de
  https://www.scraperapi.com en `config/config.php` (`scraper_api_key`), pegar la
  URL publica el producto AL INSTANTE y la foto, el precio (rango limpio) y el
  pedido mínimo se completan en segundo plano (scripts/completar_producto.php);
  la página se refresca sola hasta que llegan. Anti-duplicados por el id numérico
  de la URL de Alibaba: repetir una URL no crea otra tarjeta. Funciona igual
  deployado (ajustar `php_cli` en config si el binario no es `php`).
- Votación: **un voto por usuario por producto** (garantizado con índice UNIQUE en la BD).
- "Productos Recientes": listado de lo último publicado.
- "Productos Más Votados (10+ votos)": ranking filtrado y ordenado por votos.

## Instalación (XAMPP)

1. Copiar la carpeta `sistema-votacion` en `C:\xampp\htdocs\` (ya está ahí).
2. Iniciar **Apache** y **MySQL** desde el Panel de Control de XAMPP.
3. Abrir phpMyAdmin (http://localhost/phpmyadmin) → pestaña **Importar** →
   seleccionar `database.sql` → Continuar. (Crea la BD `sistema_votacion` con sus 3 tablas.)
4. Entrar a **http://localhost/sistema-votacion/**

## Estructura del proyecto (estilo Laravel)

```
sistema-votacion/
├── public/index.php        ← punto de entrada único (front controller + router)
├── routes/web.php          ← definición de rutas
├── config/database.php     ← conexión PDO a MySQL
├── app/
│   ├── Controllers/        ← AuthController, ProductoController, VotoController
│   ├── Models/             ← Usuario, Producto, Voto (consultas con PDO preparado)
│   └── Helpers/            ← funciones view(), url(), auth() + Extractor de URLs
├── resources/views/        ← vistas PHP con Tailwind (layout + parciales)
└── database.sql            ← script para importar en phpMyAdmin
```

## Base de datos

| Tabla     | Campos clave                                                        |
|-----------|---------------------------------------------------------------------|
| usuarios  | id, nombre, email (UNIQUE), password (hash), created_at             |
| productos | id, usuario_id (FK), nombre, url, imagen, precio, precio_original, oferta, created_at |
| votos     | id, usuario_id (FK), producto_id (FK), UNIQUE(usuario_id, producto_id) |
