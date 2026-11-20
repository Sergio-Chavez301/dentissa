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
   DENTISSA_API_URL=https://dentissa.example npm run android:sync
   ```

   Sustituye `https://dentissa.example` por el dominio real. El comando solo acepta HTTPS y genera `mobile/config.json`, que no se versiona.
4. Abre el proyecto nativo:

   ```sh
   npm run android:open
   ```

5. En Android Studio instala el SDK requerido y genera el APK desde **Build > Build Bundle(s) / APK(s) > Build APK(s)**.

## Funciones incluidas

- Inicio de sesión para cuentas de pacientes activas.
- Consulta del perfil básico y de las citas del paciente autenticado.
- Solicitud de una cita con servicio, fecha y horario disponible; queda pendiente de confirmación por el consultorio.
- Tokens API revocables con vencimiento de 30 días y respuestas marcadas para no almacenarse en caché.

La app no guarda datos clínicos ni funciona sin conexión. La confirmación de solicitudes continúa realizándose desde el sistema web.
