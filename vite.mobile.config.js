import { defineConfig } from 'vite';

export default defineConfig({
    root: 'mobile',
    base: './',
    publicDir: false,
    build: {
        outDir: 'dist',
        emptyOutDir: true,
    },
});
