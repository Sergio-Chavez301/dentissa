import type { CapacitorConfig } from '@capacitor/cli';

const config: CapacitorConfig = {
    appId: 'com.dentissa.patient',
    appName: 'Dentissa',
    webDir: 'mobile/dist',
    server: {
        androidScheme: 'https',
    },
    plugins: {
        PushNotifications: {
            presentationOptions: ['alert', 'sound'],
        },
    },
};

export default config;
