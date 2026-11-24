# Guía completa de la aplicación Android de Dentissa

Esta guía describe cómo preparar, compilar, instalar y distribuir la aplicación móvil de Dentissa para pacientes y personal de la clínica. Está dirigida al equipo que administra la aplicación Laravel y al personal que genera los APK.

> **Estado importante:** la app abre el portal Laravel publicado dentro de Android. Se requiere internet y el sitio debe estar desplegado; las vistas no se copian al APK.

## 1. Qué hace la aplicación y qué necesita

La app carga directamente el portal HTTPS de Dentissa dentro de la pantalla principal del WebView de Android. Usa las vistas Blade, las rutas web y la sesión normal de Laravel; no hace una redirección desde la API móvil. Por eso cada rol ve su propio panel y funciones, tal como en el sitio web.

Se necesita conexión a internet. Los usuarios inician sesión una vez con sus credenciales web; la cookie de sesión de Laravel se conserva en el WebView y cerrar sesión desde el portal cierra esa sesión.

La app ofrece las mismas funciones y plantillas del portal web según cada rol:

- Administración de la clínica, agenda y pacientes para administradora y asistente, respetando los permisos definidos en el servidor.
- Perfil, citas e información clínica propia para pacientes.
- En el acceso, **¿Olvidaste tu contraseña?** abre WhatsApp con el número del consultorio cuando está configurado.

La app no guarda expedientes en el dispositivo y necesita internet para mostrar o actualizar información.

Esta versión usa la sesión web de Laravel dentro del WebView. El registro nativo de dispositivos push de la app anterior no forma parte de este flujo; las notificaciones push deben validarse por separado antes de ofrecerlas en esta modalidad.

> Las secciones de Firebase y notificaciones más abajo documentan la integración nativa anterior. No configures Firebase esperando activar notificaciones en esta versión de la app; el portal directo aún no registra el dispositivo.

## 2. Requisitos

Antes de generar un APK, prepara:

- El código fuente actualizado de Dentissa.
- Node.js y npm, para compilar la interfaz móvil.
- Android Studio, Android SDK 26 o posterior y JDK 21 para las herramientas de compilación Android.
- Un dispositivo Android para instalar y probar el APK.
- Una URL pública HTTPS del backend. Para producción de Dentissa se usa:

  ```text
  https://dentissa-production-5p0ftp.laravel.cloud
  ```


No uses URLs locales como `http://127.0.0.1:8000` en una compilación destinada a pacientes: en el teléfono apuntarían al propio teléfono, no al servidor.

## 3. Preparar y compilar el proyecto Android

### 3.1 Configurar la URL del backend

Desde la raíz del repositorio, ejecuta:

```sh
DENTISSA_APP_URL=https://dentissa-production-5p0ftp.laravel.cloud npm run android:sync
```

El comando:

1. Valida que la dirección use HTTPS.
2. Construye los recursos de la app móvil con Vite.
3. Genera `mobile/config.json` con la URL del backend.
4. Genera recursos locales de respaldo.
5. Configura la URL del portal como la pantalla inicial del WebView y sincroniza Capacitor.

`mobile/config.json` es un archivo generado y no se debe subir a Git. Si se cambia de servidor o dominio, vuelve a ejecutar el comando antes de generar otro APK. La app requiere Android 8 (API 26) o posterior.

### 3.2 Abrir Android Studio y sincronizar

Abre el proyecto `android` del repositorio en Android Studio. Espera hasta que termine **Gradle Sync**. El proyecto ya contiene la integración del plugin Google Services y de Capacitor Push Notifications; no agregues bloques Gradle duplicados siguiendo instrucciones genéricas de Firebase.

Si Android Studio pide instalar una plataforma o herramienta del SDK, acepta la instalación y espera que termine.

### 3.3 Generar un APK de prueba

En Android Studio:

1. Selecciona **Build > Build Bundle(s) / APK(s) > Build APK(s)**.
2. Cuando el build termine, selecciona **Locate** en el aviso de Android Studio.
3. El APK de depuración suele estar en:

   ```text
   android/app/build/outputs/apk/debug/app-debug.apk
   ```

El APK de depuración es para pruebas. No es el artefacto recomendado para distribución general.

### 3.4 Instalar en el teléfono

Puedes conectar el teléfono por USB y ejecutar la app desde Android Studio, o transferir el APK de depuración al dispositivo e instalarlo. Android puede solicitar permiso para instalar aplicaciones desde esa fuente; habilítalo solo para la aplicación desde la que abriste el APK y vuelve a desactivarlo al terminar si no lo necesitas.

Después de instalar:

1. Abre Dentissa e inicia sesión con una cuenta de paciente activa.
2. Comprueba que se vean sus datos y citas.
3. Acepta el permiso de notificaciones cuando Android lo solicite.
4. Para probar avisos reales, completa también la configuración del backend de las secciones 4 y 5.

## 4. Configurar Firebase para Android

### 4.1 Registrar la aplicación Android

1. En Firebase Console, crea o abre el proyecto de Firebase de Dentissa.
2. Agrega una aplicación **Android**.
3. Usa exactamente este ID de aplicación (package name):

   ```text
   com.dentissa.patient
   ```

4. Completa el registro de la app en Firebase y habilita Firebase Cloud Messaging (FCM) para el proyecto.
5. Descarga `google-services.json`.
6. Coloca el archivo en:

   ```text
   android/app/google-services.json
   ```

El archivo de configuración Android no es la clave privada de servidor. Aun así, este proyecto lo excluye de Git; no cambies esa exclusión ni publiques archivos privados por error. Después de agregarlo, ejecuta de nuevo `android:sync` y vuelve a compilar el APK.

### 4.2 Crear credenciales de servidor (no van en Android)

Laravel necesita una cuenta de servicio de Google para solicitar tokens OAuth y enviar mensajes por FCM HTTP v1:

1. En Firebase Console o Google Cloud Console, abre el proyecto Firebase correcto.
2. En **Configuración del proyecto > Cuentas de servicio**, abre la administración de cuentas de servicio de Google Cloud.
3. Usa una cuenta de servicio con permiso para enviar mensajes de Firebase Cloud Messaging (por ejemplo, el rol **Firebase Cloud Messaging API Admin**).
4. Genera y descarga una clave JSON de esa cuenta.
5. Guarda el archivo en una ubicación privada y temporal para copiar su contenido directamente al gestor de secretos de Laravel Cloud.

Configura en Laravel Cloud estas variables para el entorno de producción:

| Variable | Valor |
|---|---|
| `FIREBASE_PROJECT_ID` | ID del proyecto Firebase, por ejemplo `dentissa-a40e6`. Debe coincidir con `project_id` de la cuenta de servicio. |
| `FIREBASE_SERVICE_ACCOUNT_JSON` | Contenido JSON completo de la clave de la cuenta de servicio. Guárdalo como secreto y no lo pongas en el APK. |

No pegues la cuenta de servicio en el chat, no la agregues a `google-services.json`, no la guardes en el repositorio y no la envíes a pacientes. Si la clave se expone, revócala y genera una nueva.

Cuando se actualicen variables de entorno, aplica la configuración según el flujo de Laravel Cloud para que los procesos desplegados la reciban. El servicio valida que `FIREBASE_PROJECT_ID` coincida con el proyecto de la cuenta de servicio; si no coincide o falta una variable, FCM no podrá enviar avisos.

## 5. Desplegar backend, base de datos y Scheduler

Las notificaciones requieren cambios en Laravel además del APK:

1. Despliega la versión del backend que contiene la API móvil, el registro de dispositivos push y el envío de notificaciones.
2. En Laravel Cloud ejecuta las migraciones de producción:

   ```sh
   php artisan migrate --force
   ```

3. Comprueba que estas migraciones estén aplicadas:

   - `2026_11_19_214759_create_personal_access_tokens_table`
   - `2026_11_19_230000_add_reminder_sent_at_to_citas_table`
   - `2026_11_19_230001_create_push_devices_table`
   - `2026_11_20_000001_add_origen_to_citas_table`
   - `2026_11_23_000001_add_custom_treatment_to_citas_table`

   Si la migración de tokens ya se aplicó anteriormente, no es necesario volver a aplicarla; Laravel solo ejecutará las pendientes. Revisa el resultado con:

   ```sh
   php artisan migrate:status
   ```

4. En Laravel Cloud habilita **Scheduler** para el App cluster y despliega/aplica ese cambio. El Scheduler ejecuta cada hora la actualización de citas vencidas y cada minuto los recordatorios push, ambos con prevención de ejecuciones simultáneas.
5. Confirma la programación dentro del entorno desplegado:

   ```sh
   php artisan schedule:list
   ```

6. Revisa los logs de Laravel Cloud durante la prueba. El backend notifica al dispositivo registrado cuando el personal confirma o cancela una cita; el recordatorio se intenta en la ventana aproximada de 24 horas antes de una cita confirmada.

El Scheduler solo ejecuta el comando de recordatorios mientras esté activo. El comando usa el servicio Firebase de Laravel; Scheduler no envía mensajes directamente. El recordatorio se marca cuando FCM acepta al menos un envío; esa respuesta no garantiza que Android haya mostrado la notificación. Al reprogramar una cita o volver a confirmarla se reinicia la marca. Si Scheduler no está activo, las confirmaciones y cancelaciones todavía pueden producir push, pero los recordatorios programados no se ejecutarán.

## 6. Probar el recorrido de notificaciones

Haz la prueba con un dispositivo y una cuenta de paciente autorizada:

1. Instala el APK compilado después de agregar `google-services.json`.
2. Inicia sesión en la app y concede permiso para notificaciones.
3. Confirma que el teléfono tenga conexión a internet y que Android no tenga bloqueadas las notificaciones de Dentissa.
4. Comprueba en el backend que el dispositivo quedó registrado para la cuenta del paciente.
5. Desde el sistema web cambia el estado de una cita de prueba a confirmada o cancelada. Revisa que el teléfono reciba el aviso.
6. Para validar el recordatorio, usa una cita confirmada que entre en la ventana de 24 horas y verifica que el Scheduler ejecute el comando.

Los mensajes usan texto general y no incluyen diagnósticos ni detalles clínicos en la pantalla bloqueada. Los permisos del teléfono permiten mostrar avisos, pero no sustituyen la configuración de Firebase ni el Scheduler.

## 7. Firmar y distribuir

### APK firmado para compartir directamente

1. En Android Studio selecciona **Build > Generate Signed Bundle / APK**.
2. Elige **APK** y el módulo `app`.
3. Si no tienes una clave de firma, elige **Create new…** y crea un keystore.
4. Conserva el keystore, la contraseña, el alias y la contraseña de la clave en un gestor seguro y con respaldo. Son necesarios para futuras actualizaciones compatibles.
5. Completa el asistente de firma y localiza el APK de lanzamiento que genera Android Studio.
6. Prueba ese APK en un dispositivo antes de compartirlo.
7. Aloja el archivo en una URL HTTPS pública controlada por el consultorio.
8. Configura `ANDROID_APP_DOWNLOAD_URL` en Laravel Cloud con esa URL y despliega la configuración. La página pública y los mensajes de credenciales pueden mostrar el enlace de descarga.
9. Cada nueva compilación debe incrementar `versionCode` y `versionName` antes de firmarla. Para actualizaciones directas conserva el mismo keystore y reutiliza el enlace estable del último release, por ejemplo el endpoint `/releases/latest/download/Dentissa.apk` de GitHub cuando el nombre del archivo se mantiene constante.

No compartas el archivo keystore ni sus contraseñas. Perder la clave puede impedir publicar actualizaciones de la misma app.

### Google Play

Para publicar en Google Play, genera un **Android App Bundle (AAB)** firmado desde **Build > Generate Signed Bundle / APK > Android App Bundle** y completa el proceso de publicación de Google Play. Revisa además las políticas de privacidad, contenido y permisos exigidas por la tienda.

Cada nueva versión de la app debe compilarse de nuevo, firmarse con la misma identidad y distribuirse a los pacientes. Un APK instalado anteriormente no recibe el código nuevo automáticamente.

## 8. Actualizar la app sin instalar un APK viejo

Al cambiar código móvil:

1. Obtén la versión actualizada del repositorio.
2. Ejecuta el comando `android:sync` con la URL HTTPS que debe usar esa compilación.
3. Espera a que termine el build y la sincronización de Capacitor.
4. En Android Studio vuelve a sincronizar Gradle y genera un APK nuevo.
5. Instala el APK nuevo para validar la versión.

En este proyecto se corrigió un error donde la función de activación push no estaba disponible al iniciar sesión. Si el teléfono aún muestra `enablePushNotifications is not defined`, tiene instalada una compilación anterior: sincroniza, compila e instala el APK actualizado. La compilación que incluye el arreglo ya no debe mostrar ese error.

## 9. Solución de problemas

### Android Studio no abre desde `npm run android:open`

El comando solo abre Android Studio; no lo instala. Abre Android Studio manualmente y selecciona la carpeta `android` del proyecto.

### `DENTISSA_API_URL` no está definida o la URL no usa HTTPS

Ejecuta `android:sync` con la variable y una dirección HTTPS pública completa:

```sh
DENTISSA_API_URL=https://dentissa-production-5p0ftp.laravel.cloud npm run android:sync
```

### El APK carga, pero no aparecen los datos

- Comprueba la conexión a internet del teléfono.
- Verifica que el APK se haya compilado con la URL HTTPS correcta.
- Confirma que la API del backend está desplegada y que la cuenta corresponde a un paciente activo.
- No uses una URL `127.0.0.1`, una IP local o un dominio HTTP en la compilación del teléfono.

### Aparece `enablePushNotifications is not defined`

El teléfono tiene un APK anterior. Ejecuta `android:sync`, vuelve a compilar e instala el APK recién generado. No basta con cerrar y volver a abrir el APK anterior.

### La app permite iniciar sesión, pero avisa que no pudo activar push

- Asegúrate de que `android/app/google-services.json` corresponda a `com.dentissa.patient`.
- Confirma que el JSON estaba en su ubicación antes de sincronizar y compilar el APK.
- Acepta el permiso de notificaciones de Android y revisa que no esté bloqueado en Ajustes del dispositivo.
- Verifica `FIREBASE_PROJECT_ID` y `FIREBASE_SERVICE_ACCOUNT_JSON` en Laravel Cloud y que ambos sean del mismo proyecto.
- Confirma que el backend actualizado y las migraciones estén desplegados.
- Revisa los logs del backend y el estado de Firebase Cloud Messaging.

### Las confirmaciones funcionan, pero no llega el recordatorio

- Confirma que Scheduler está habilitado para el App cluster en Laravel Cloud.
- Ejecuta `php artisan schedule:list` en el entorno de producción y verifica `citas:enviar-recordatorios`.
- El recordatorio es para citas confirmadas que se encuentran aproximadamente a 24 horas de iniciar; una cita ya pasada o fuera de esa ventana no genera el mismo aviso.
- Comprueba que la cita no tenga ya registrado su recordatorio y revisa los logs.

### Gradle pide configurar Firebase manualmente

El proyecto ya aplica los plugins Gradle necesarios cuando `google-services.json` está presente. No copies otra vez las mismas declaraciones. Verifica primero que el JSON esté en `android/app/`, luego vuelve a sincronizar Capacitor y Gradle.

## 10. Seguridad y privacidad

- No introduzcas secretos en el código de la app, en el APK ni en el repositorio.
- Mantén la cuenta de servicio de Firebase como secreto exclusivo del backend.
- No uses datos de pacientes reales en capturas o pruebas que se vayan a compartir públicamente.
- Entrega las credenciales temporales de los pacientes por un canal privado y recomienda cambiar la contraseña.
- La app requiere internet y transmite solicitudes autenticadas al backend por HTTPS.
