import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css', 'resources/js/app.js',
                'resources/js/dashboard.js','resources/js/create_report.js',
                'resources/js/create_order.js'
            ],
            refresh: [
                'resources/js/**',
                'resources/css/**',
                'routes/**',
                'resources/views/**',
            ],
        }),
    ],
});
