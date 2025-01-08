import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import path from 'path';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    resolve: {
        alias: {
            '$': 'jquery', // Pastikan alias ini mengarah ke jQuery
            // 'jquery-ui': `${path.resolve(__dirname, 'node_modules/jquery-ui')}` // Jika Anda ingin menggunakan alias untuk jQuery UI
        },
    },
});