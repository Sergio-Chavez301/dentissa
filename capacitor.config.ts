import type { CapacitorConfig } from '@capacitor/cli';

const config: CapacitorConfig = {
    appId: 'com.dentissa.patient',
    appName: 'Dentissa',
    webDir: 'mobile',
    server: {
        androidScheme: 'https',
    },
};

export default config;
