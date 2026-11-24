import type { CapacitorConfig } from '@capacitor/cli';

const appUrl = process.env.DENTISSA_APP_URL
    || process.env.DENTISSA_API_URL
    || 'https://dentissa-production-5p0ftp.laravel.cloud';

const portalUrl = new URL(appUrl);
if (portalUrl.protocol !== 'https:' || portalUrl.username || portalUrl.password || portalUrl.search || portalUrl.hash) {
    throw new Error('DENTISSA_APP_URL debe ser HTTPS y no incluir credenciales, parámetros ni fragmentos.');
}
portalUrl.pathname = '/login';
portalUrl.search = '?mobile=1';

const config: CapacitorConfig = {
    appId: 'com.dentissa.patient',
    appName: 'Dentissa',
    webDir: 'mobile/dist',
    server: {
        androidScheme: 'https',
        url: portalUrl.toString(),
        allowNavigation: [portalUrl.hostname],
        errorPath: 'offline.html',
    },
    plugins: {
        PushNotifications: {
            presentationOptions: ['alert', 'sound'],
        },
    },
};

export default config;
