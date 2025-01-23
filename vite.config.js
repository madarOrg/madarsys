import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/plugins/chartjs.min.js',
                'resources/js/plugins/perfect-scrollbar.min.js',
                'resources/js/soft-ui-dashboard-tailwind.js',
            ],
            refresh: true,
        }),
    ],
});
