import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 
                'resources/js/bootstrap.js',
                'resources/images/open.jpg',
                'resources/images/servis.jpg',
                'resources/images/Karvin.png',
                'resources/images/tim 2.jpg',
                'resources/images/salam-karvin.jpg',
                'resources/images/map.png',
                'resources/js/app.js',
            ], 
            refresh: true,
        }),
    ],
});
