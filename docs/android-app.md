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

## Notificaciones push y recordatorios

Las confirmaciones y cancelaciones enviadas desde la gestión de citas notifican al dispositivo Android registrado del paciente. Una tarea programada envía un recordatorio 24 horas antes de cada cita confirmada y registra el envío para no repetirlo.

Para activar la entrega real:

1. Crea un proyecto Firebase, agrega una app Android con el ID `com.dentissa.patient` y activa Firebase Cloud Messaging.
2. Descarga `google-services.json` y colócalo en `android/app/google-services.json` antes de sincronizar y compilar el APK. El archivo está excluido de Git.
3. En Laravel Cloud configura `FIREBASE_PROJECT_ID` y `FIREBASE_SERVICE_ACCOUNT_JSON` como variables/secretos del backend. La cuenta de servicio debe tener permiso para enviar mensajes de Firebase Cloud Messaging; nunca incluyas su clave privada en el APK ni en Git.
4. Despliega las migraciones nuevas y habilita **Scheduler** en el App cluster de Laravel Cloud (Infrastructure canvas). Guarda y vuelve a desplegar; Laravel Cloud ejecutará `schedule:run` cada minuto.
5. Genera un APK nuevo, instálalo en los teléfonos y acepta el permiso de notificaciones. Los APK existentes no incluyen el plugin push.

Las notificaciones usan texto general para no exponer detalles de salud en la pantalla bloqueada. El paciente debe iniciar sesión en la app para asociar el dispositivo con su cuenta y aceptar el permiso de notificaciones. Si no configura Firebase o el scheduler, los avisos no se podrán entregar.

## Compartir la aplicación

Genera un APK de lanzamiento firmado desde Android Studio y súbelo a un alojamiento con URL HTTPS pública de descarga. En Laravel Cloud configura la variable de entorno `ANDROID_APP_DOWNLOAD_URL` con esa URL y despliega la configuración. Entonces el enlace aparecerá en la página pública y se incluirá en los mensajes de WhatsApp que contienen credenciales del paciente.

No uses el APK de depuración para compartirlo con pacientes. Para distribución general, genera un Android App Bundle (AAB) firmado y publica la aplicación en Google Play. Cada nueva versión requiere compilar y distribuir una actualización.
