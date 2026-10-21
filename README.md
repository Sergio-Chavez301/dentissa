<div align="center">
  <img src="public/img/logo.png" alt="Dentissa · Odontología integral" width="150">
  <h1>Dentissa</h1>
  <p><strong>Sistema de gestión para una atención odontológica organizada y cercana.</strong></p>
  <p>
    <img src="https://img.shields.io/badge/Laravel-12-FF2D20?logo=laravel&logoColor=white" alt="Laravel 12">
    <img src="https://img.shields.io/badge/PHP-8.2%2B-777BB4?logo=php&logoColor=white" alt="PHP 8.2 o superior">
    <img src="https://img.shields.io/badge/Interfaz-Blade%20%2B%20Bootstrap-7952B3?logo=bootstrap&logoColor=white" alt="Blade y Bootstrap">
  </p>
</div>

Dentissa centraliza la operación de una clínica dental en una aplicación web: organiza pacientes y expedientes, administra la agenda y acompaña el seguimiento de tratamientos desde una solicitud de cita hasta la atención.

> Proyecto Laravel con acceso por roles. El uso en producción requiere configurar el hosting, la base de datos, el correo transaccional y las medidas de seguridad descritas abajo. No uses información clínica real en una instalación de demostración.

## Qué incluye

- **Portal público:** información de la clínica, tratamientos, ofertas, ubicación y solicitud de cita.
- **Agenda:** consulta de disponibilidad, bloqueos de horario y estados de cita: en espera, confirmada, realizada, cancelada y no se presentó.
- **Pacientes:** datos personales y clínicos, usuario de acceso y expediente vinculado.
- **Tratamientos:** casos clínicos con progreso y citas relacionadas.
- **Paneles por rol:** administrador, asistente y paciente, con vistas acordes a sus tareas.
- **Administración:** servicios, usuarios, solicitudes, ofertas, reportes y datos de contacto/ubicación del consultorio.
- **Mensajes de WhatsApp:** enlaces preparados para confirmar citas, compartir credenciales temporales y contactar al consultorio.

## Roles

| Rol | Uso principal |
| --- | --- |
| Administrador | Configura el sistema, gestiona cuentas y contenido, procesa solicitudes y administra la clínica. |
| Asistente | Apoya la agenda y la gestión operativa de pacientes y tratamientos. |
| Paciente | Consulta su perfil, citas, tratamientos, progreso y ofertas. |

## Tecnologías

- Laravel 12 y PHP 8.2 o superior.
- MySQL/MariaDB para los datos de la clínica.
- Blade, Bootstrap 5 y Bootstrap Icons para las vistas.
- Vite, npm y Tailwind CSS para compilar recursos frontend.
- PHPUnit para pruebas automatizadas.

## Documentación

La documentación interactiva está en [`documentacion/informacion/`](documentacion/informacion/index.html):

- [Índice de documentación](documentacion/informacion/index.html)
- [Diagrama interactivo de la base de datos](documentacion/informacion/diagrama-base-datos.html)
- [Flujos del sistema](documentacion/informacion/flujo-sistema.html)
- [Referencia técnica, permisos y despliegue](documentacion/informacion/documentacion-sistema.html)

## Instalación local

Requisitos: PHP 8.2+, Composer, Node.js/npm y MySQL o MariaDB.

```bash
composer install
cp .env.example .env
php artisan key:generate
```

Primero crea una base de datos vacía en MySQL/MariaDB y configura sus datos de conexión en `.env`. Después ejecuta:

```bash
php artisan migrate
npm ci
npm run build
php artisan serve
```

Abre `http://127.0.0.1:8000`. `php artisan migrate --seed` crea datos y cuentas de demostración: úsalo solo en una base local desechable después de revisar los seeders. Nunca ejecutes seeders de demostración en producción. No publiques `.env`, claves de aplicación, contraseñas ni credenciales de prueba.

## Seguridad y datos

Esta aplicación puede contener información personal y clínica. Para producción, utiliza un hosting privado y mantenido, HTTPS, contraseñas robustas, base de datos con acceso restringido, permisos mínimos y copias de seguridad protegidas. No despliegues una demo gratuita con datos reales de pacientes.

La recuperación de contraseña usa el Password Broker de Laravel, enlaces temporales de un solo uso y límite de solicitudes. `MAIL_MAILER=log` no entrega correos; en producción configura un proveedor SMTP transaccional y verifica el dominio remitente. Las credenciales del proveedor deben configurarse como secretos del hosting, nunca en GitHub.

## Despliegue en hosting

GitHub guarda el código, pero no ejecuta Laravel. Usa un hosting que soporte PHP 8.2+, Composer, MySQL/MariaDB, HTTPS y extensiones como `pdo_mysql`, `mbstring`, `openssl`, `fileinfo`, `xml`, `ctype` y `json`. GitHub Pages no puede ejecutar PHP.

1. Configura el document root del dominio en `public/`, no en la raíz del repositorio.
2. Instala dependencias de producción con `composer install --no-dev --optimize-autoloader`.
3. Compila los recursos con `npm ci && npm run build` durante el despliegue, o compila `public/build` antes de subir el release.
4. Define las variables privadas del servidor tomando `.env.example` como referencia: `APP_ENV=production`, `APP_DEBUG=false`, URL HTTPS, una `APP_KEY` nueva y credenciales MySQL propias del hosting. Mantén `.env` fuera de Git.
5. Ejecuta `php artisan migrate --force` y concede escritura a `storage/` y `bootstrap/cache/`.
6. Configura SMTP transaccional antes de habilitar recuperación por correo y verifica SPF, DKIM y DMARC con el proveedor.
7. Al completar el despliegue, ejecuta `php artisan config:cache` y `php artisan view:cache`. No uses `php artisan migrate:fresh` en producción: elimina tablas y datos.

La configuración incluida usa sesiones y caché en archivos y cola síncrona porque el repositorio no crea tablas de sesiones, caché ni trabajos. Conserva estos drivers en el hosting salvo que añadas y migres esas tablas o configures el servicio correspondiente.

## Recuperación de contraseña por correo

El formulario responde de forma genérica para no revelar si un correo tiene una cuenta. El endpoint está limitado a tres solicitudes por minuto. En producción la recuperación se desactiva si el mailer continúa configurado como `log`, para impedir que tokens de restablecimiento acaben en los logs.

Configura SMTP usando el host, puerto, usuario, contraseña de aplicación y dirección remitente proporcionados por tu proveedor. Los secretos se cargan desde el panel del hosting; no los escribas en este README ni en archivos versionados. Después de actualizar variables, limpia o reconstruye la caché de configuración y prueba el envío con una cuenta controlada.
