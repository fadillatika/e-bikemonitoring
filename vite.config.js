import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/js/app.js', 
                'resources/js/dash.js', 
                'resources/js/mapid.js',
                'resources/js/battery.js',
            ],
            refresh: true,
        }),
    ],
});
