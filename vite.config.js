import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/css/admin/admin-style.css',
                'resources/css/admin/tabulator-custom.css',
                'resources/js/admin/tabulator-init.js',
                'resources/css/employee/employee-style.css',
                'resources/css/employee/tabulator-custom.css',
                'resources/js/employee/tabulator-init.js',
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
