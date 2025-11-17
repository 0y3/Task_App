import { defineConfig, loadEnv } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';
import vue from '@vitejs/plugin-vue';

export default defineConfig(({ mode }) => {
    const env = loadEnv(mode, process.cwd(), ''); // loads .env, .env.development etc.
    return {
        base: env.VITE_APP_URL || '/',
        plugins: [
            laravel({
                input: ['resources/css/app.css', 'resources/js/app.ts'],
                refresh: true,
            }),
            tailwindcss(),
            vue(),
        ],
        server: {
            // host: env.VITE_APP_URL || 'localhost',
            // port: env.VITE_DEV_PORT || 5173,
        },
    };
});
