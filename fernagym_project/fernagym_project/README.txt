Fernagym - Estructura PHP + MySQL
=================================

Archivos principales:
- index.php           -> Frontend público con formulario.
- inscripcion.php     -> Procesa el formulario y guarda en MySQL.
- conexion.php        -> Conexión PDO a la base de datos.
- /uploads            -> Carpeta donde se guardan comprobantes (asegúrate que sea escribible).
- /assets/img/logo.png, hero.jpg -> imágenes de ejemplo.
- /sql/fernagym.sql   -> Script para crear la base de datos y tabla.

Instrucciones rápidas:
1. Copia la carpeta `fernagym_project` a tu servidor (por ejemplo /var/www/html/fernagym).
2. Asegura permisos de escritura en la carpeta `uploads`: e.g. chown www-data:www-data uploads && chmod 755 uploads
3. Crea la base de datos:
   - Importa `sql/fernagym.sql` en tu servidor MySQL o usa `mysql -u root -p < sql/fernagym.sql`
4. Ajusta las credenciales en `conexion.php` si tu usuario/clave no son los predeterminados.
5. Accede a `http://tu-servidor/fernagym/index.php` y prueba el formulario.
6. Para producción, revisa la configuración de PHP (upload_max_filesize, post_max_size) y usa HTTPS.

Notas de seguridad:
- Se usan prepared statements para evitar inyección SQL.
- Validación básica de archivos y tamaño (máx 5MB).
- En producción, guarda la configuración sensible fuera del webroot o usa variables de entorno.
