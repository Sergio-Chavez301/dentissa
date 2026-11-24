# App Android de Dentissa

## Qué incluye la app

La app Android abre Dentissa dentro de una ventana segura del propio proyecto. No muestra la página pública. Presenta el acceso y, después de iniciar sesión, el panel correspondiente al rol:

- **Doctora / administradora:** configuración y herramientas administrativas, además de la operación clínica.
- **Asistente:** funciones operativas de agenda, pacientes y solicitudes, según los permisos del sistema.
- **Paciente:** sus propias citas, perfil y tratamientos; no puede acceder a la información de otros pacientes.

La interfaz reutiliza las vistas web de Laravel. La aplicación requiere internet y la sesión se valida en el servidor. No almacena expedientes localmente ni tiene modo sin conexión. La página pública sigue disponible en el navegador; el acceso móvil no la presenta.

El acceso se abre en `/login?mobile=1`. Esa sesión activa el modo móvil, que mantiene al usuario en su panel según su rol. Cerrar sesión vuelve al acceso de la app. Laravel controla los permisos; ocultar un botón en la interfaz no sustituye las validaciones del servidor.

En la pantalla de acceso, **¿Olvidaste tu contraseña?** ofrece contactar a la doctora por WhatsApp cuando el número del consultorio está configurado. La misma opción aparece en la página de recuperación.

## Cambios que requieren una nueva APK

Las páginas y funciones servidas por Laravel se actualizan al desplegar el backend. Se debe compilar, firmar e instalar una APK nueva para modificar recursos nativos, como el icono, el nombre, la configuración de Capacitor o el código Android.

El icono de Dentissa usa el emblema oficial rosa del logo (`public/img/logo.png`), con versiones adaptables y tamaños para Android; reemplaza el icono genérico de Capacitor.

## Notificaciones

El backend conserva componentes y endpoints de notificaciones push, pero esta versión de la app carga el portal web y **no registra el dispositivo Android para recibir push**. Por lo tanto, no se debe prometer que las notificaciones push estén disponibles en la app. Para habilitarlas de nuevo hay que integrar y probar el registro nativo del dispositivo y el flujo de permisos.

## Requisitos para compilar

- Node.js y npm.
- Android Studio, Android SDK y JDK 21.
- Una URL pública HTTPS del portal.
- Un dispositivo Android para probar inicio de sesión, teclado, paneles y cierre de sesión.

La URL de producción actual es:

```text
https://dentissa-production-5p0ftp.laravel.cloud
```

## Preparar y sincronizar

Desde la raíz del proyecto:

```sh
DENTISSA_APP_URL=https://dentissa-production-5p0ftp.laravel.cloud npm run android:sync
```

El comando valida la URL HTTPS, prepara los recursos locales y sincroniza los plugins de Capacitor con Android. `DENTISSA_APP_URL` permite compilar para otro entorno; no uses `127.0.0.1` ni una dirección HTTP en una APK destinada a teléfonos.

Después abre la carpeta `android` en Android Studio y espera a que termine **Gradle Sync**. Para generar una APK de prueba, selecciona **Build > Build Bundle(s) / APK(s) > Build APK(s)**. El resultado de depuración suele estar en:

```text
android/app/build/outputs/apk/debug/app-debug.apk
```

## Prueba antes de distribuir

1. Instala la APK en un teléfono Android con conexión a internet.
2. Inicia sesión con cuentas de prueba de administradora, asistente y paciente.
3. Comprueba que cada rol llegue a su panel y no pueda abrir las rutas de los otros roles.
4. Verifica que los campos de acceso acepten texto y que el teclado no cubra el formulario.
5. Comprueba las funciones del panel, el cierre de sesión y el enlace de recuperación por WhatsApp.
6. Confirma que la app no muestre la página pública ni datos de otras cuentas.

Usa cuentas y datos ficticios durante las pruebas. No incluyas información clínica real en capturas o videos de demostración.

## Actualizar y distribuir

- Los cambios de vistas, permisos y lógica del servidor requieren desplegar el backend.
- Los cambios de icono o configuración Android requieren `android:sync`, un nuevo build y una nueva APK.
- Una APK ya instalada no cambia automáticamente. Hay que instalar y probar la nueva versión.
- Para distribuir una actualización firmada directamente, conserva la misma clave de firma y aumenta `versionCode` y `versionName` en `android/app/build.gradle`.
- Guarda las claves de firma fuera del repositorio y no las compartas por chat.

La descarga de la app se ofrece desde la web pública cuando `ANDROID_APP_DOWNLOAD_URL` está configurada. No debe aparecer como descarga dentro del panel de paciente en la app.
