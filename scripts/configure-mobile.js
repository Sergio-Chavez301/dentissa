import { writeFile } from 'node:fs/promises';
import { resolve } from 'node:path';

const configuredUrl = process.env.DENTISSA_APP_URL || process.env.DENTISSA_API_URL;

if (!configuredUrl) {
    throw new Error('Define DENTISSA_APP_URL con la URL HTTPS pública del portal Dentissa.');
}

let apiUrl;

try {
    apiUrl = new URL(configuredUrl);
} catch {
    throw new Error('DENTISSA_API_URL no es una URL válida.');
}

if (apiUrl.protocol !== 'https:' || apiUrl.username || apiUrl.password || apiUrl.search || apiUrl.hash) {
    throw new Error('DENTISSA_APP_URL debe ser HTTPS y no incluir credenciales, parámetros ni fragmentos.');
}

const config = {
    portalUrl: apiUrl.toString().replace(/\/+$/, ''),
};

await writeFile(
    resolve('mobile/config.json'),
    `${JSON.stringify(config, null, 2)}\n`,
    'utf8',
);

console.log(`Portal Dentissa configurado para ${config.portalUrl}`);
