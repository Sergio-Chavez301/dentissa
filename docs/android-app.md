# Aplicación Android de Dentissa

La app usa una interfaz local empaquetada con Capacitor y se conecta por HTTPS a la API de Laravel. Los datos y las citas permanecen en la base de datos del servidor; la app necesita internet. El token de acceso solo se mantiene en memoria, por lo que se solicita iniciar sesión otra vez al volver a abrir la app.

## Preparar el APK

1. Publica Laravel en un dominio HTTPS y configura la base de datos del servidor.
2. Despliega los cambios del backend e instala la nueva tabla de tokens en el servidor:

   ```sh
   composer install --no-dev --optimize-autoloader
   php artisan migrate --force
   ```

3. Desde el proyecto, configura la URL de la API y sincroniza Android:

   ```sh
   DENTISSA_API_URL=https://dentissa-production-5p0ftp.laravel.cloud npm run android:sync
   ```

   El comando solo acepta HTTPS y genera `mobile/config.json`, que no se versiona.
4. Abre el proyecto nativo:

   ```sh
   npm run android:open
   ```

5. En Android Studio instala el SDK requerido y genera el APK desde **Build > Build Bundle(s) / APK(s) > Build APK(s)**.

## Funciones incluidas

- Inicio de sesión para cuentas de pacientes activas.
- Consulta de todos los datos de perfil del paciente, un resumen y listado de sus citas, tratamientos con progreso e historial, además de las tres ofertas activas.
- Solicitud de una cita con servicio, fecha y horario disponible; queda pendiente de confirmación por el consultorio.
- Tokens API revocables con vencimiento de 30 días y respuestas marcadas para no almacenarse en caché.

La app no guarda datos clínicos ni funciona sin conexión. La confirmación de solicitudes continúa realizándose desde el sistema web.

## Compartir la aplicación

Para una distribución de prueba, genera un APK de depuración desde Android Studio y comparte el archivo con los pacientes mediante un enlace privado de descarga. En Android tendrán que autorizar la instalación desde esa fuente. Para distribución general, genera un APK de lanzamiento firmado o un Android App Bundle (AAB) y publica la aplicación en Google Play. Cada nueva versión requiere compilar y distribuir una actualización.
