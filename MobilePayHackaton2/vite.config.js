import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react';
import autoprefixer from 'autoprefixer';

export default defineConfig({
    plugins: [
        laravel({                
            input: [            
                'resources/css/app.css',
                'resources/js/app.jsx'
            ],
            refresh: true,
            postcss: {
                plugins: [
                    autoprefixer(),
                ]
            },
        }),
        react(),
    ],

    server: {
        watch: {
          usePolling: true
        }
    }
});

