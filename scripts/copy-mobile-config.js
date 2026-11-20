import { copyFile } from 'node:fs/promises';

await copyFile('mobile/config.json', 'mobile/dist/config.json');
