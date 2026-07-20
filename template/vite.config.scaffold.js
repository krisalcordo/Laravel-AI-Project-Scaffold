import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';

const ddevHost = process.env.DDEV_PRIMARY_URL
    ? new URL(process.env.DDEV_PRIMARY_URL).hostname
    : null;

export default defineConfig({
    plugins: [
        laravel({
            input: 'resources/js/app.tsx',
            refresh: true,
        }),
        react(),
    ],
    server: {
        host: '0.0.0.0',
        port: 5173,
        strictPort: true,
        allowedHosts: ['.ddev.site'],
        origin: ddevHost ? `https://${ddevHost}:5173` : 'http://localhost:5173',
        cors: {
            origin: /^https?:\/\/[a-z0-9.-]+\.ddev\.site(?::\d+)?$/,
        },
        hmr: {
            host: ddevHost ?? 'localhost',
        },
    },
});

