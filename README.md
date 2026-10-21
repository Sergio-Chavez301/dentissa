<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework. You can also check out [Laravel Learn](https://laravel.com/learn), where you will be guided through building a modern Laravel application.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Redberry](https://redberry.international/laravel-development)**
- **[Active Logic](https://activelogic.com)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Recuperación de contraseña por correo

El restablecimiento usa el Password Broker de Laravel: los enlaces son de un solo uso, caducan según `config/auth.php` y las solicitudes están limitadas a tres por minuto. La respuesta no confirma si una dirección tiene una cuenta.

En desarrollo el transporte `log` es útil para comprobar el flujo, pero no entrega correos. En producción configura un proveedor de correo transaccional (por ejemplo, Resend, Postmark o Brevo) mediante SMTP en el entorno del servidor; no guardes sus credenciales en el repositorio:

```env
APP_ENV=production
APP_URL=https://tu-dominio.example
MAIL_MAILER=smtp
MAIL_SCHEME=smtp
MAIL_HOST=servidor-smtp-del-proveedor
MAIL_PORT=587
MAIL_USERNAME=usuario-smtp-del-proveedor
MAIL_PASSWORD=secreto-smtp-del-proveedor
MAIL_FROM_ADDRESS=cuentas@tu-dominio.example
MAIL_FROM_NAME="Dentissa"
```

Usa los valores SMTP y el modo de cifrado que indique el proveedor. Verifica el dominio remitente y configura SPF, DKIM y DMARC antes de producción. Después de cambiar variables de entorno, ejecuta `php artisan config:clear` (o reconstruye la caché de configuración del despliegue) y solicita un restablecimiento de prueba. En producción, el envío se desactiva si el mailer continúa en `log`, para evitar que los enlaces y tokens terminen escritos en los logs.

## Despliegue en hosting

GitHub almacena y distribuye el código, pero no ejecuta por sí solo esta aplicación Laravel. Conecta el repositorio a un hosting que soporte PHP 8.2 o superior, Composer, MySQL/MariaDB, Node.js para compilar Vite (o compila los assets antes de desplegar) y HTTPS. GitHub Pages no puede ejecutar Laravel/PHP.

Antes de publicar:

1. Configura el document root del dominio en la carpeta `public`, nunca en la raíz del repositorio.
2. Ejecuta `composer install --no-dev --optimize-autoloader` y `npm ci && npm run build` durante el despliegue. Si el hosting no tiene Node.js, compila `public/build` antes de subir el release.
3. Crea una base MySQL/MariaDB y define las variables del servidor tomando `.env.example` como referencia. Usa `APP_ENV=production`, `APP_DEBUG=false`, la URL HTTPS real, credenciales de base de datos propias del hosting y un `APP_KEY` nuevo generado en el servidor. No subas `.env` a GitHub.
4. Ejecuta `php artisan migrate --force` y `php artisan storage:link`. Asegura permisos de escritura para `storage` y `bootstrap/cache`.
5. Configura un SMTP transaccional real antes de activar recuperación de contraseñas. Cambia `MAIL_MAILER=log` por los datos SMTP del proveedor y verifica el dominio remitente.
6. Al terminar, ejecuta `php artisan config:cache` y `php artisan view:cache`. No uses `php artisan migrate:fresh` en producción: elimina las tablas y sus datos.

En este proyecto no existen migraciones para tablas de sesión, caché o cola; por eso `.env.example` usa sesiones y caché en archivos y cola síncrona. La configuración real del servidor debe conservar esos drivers, o instalar las tablas/servicios correspondientes antes de cambiar a drivers de base de datos o Redis. La aplicación necesita extensiones PHP como `pdo_mysql`, `mbstring`, `openssl`, `fileinfo`, `xml`, `ctype` y `json`.

## Documentación interactiva

Abre [documentacion/informacion/index.html](documentacion/informacion/index.html) para navegar el [diagrama de base de datos](documentacion/informacion/diagrama-base-datos.html), los [flujos del sistema](documentacion/informacion/flujo-sistema.html) y la [referencia técnica](documentacion/informacion/documentacion-sistema.html). Son documentos HTML autónomos que se pueden abrir localmente; no dependen de librerías externas.
