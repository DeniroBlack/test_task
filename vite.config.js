import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    server: {
      host: '0.0.0.0',
      hmr: {
        host: 'localhost',
        port: 5173, // Явное указание порта
        protocol: 'ws'
      },
      watch: {
        usePolling: true // Для отслеживания изменений в Docker
      }
    },
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: [
                {
                    paths: ['resources/views/**'],
                }
            ]
        }),
        tailwindcss(),
    ],
});
