import { defineConfig } from 'vite';
import { resolve } from 'node:path';

export default defineConfig({
    root: 'mobile',
    base: './',
    publicDir: false,
    build: {
        outDir: 'dist',
        emptyOutDir: true,
        rollupOptions: {
            input: {
                app: resolve('mobile/index.html'),
                offline: resolve('mobile/offline.html'),
            },
        },
    },
});
