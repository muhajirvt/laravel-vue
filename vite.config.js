import { defineConfig } from 'vite'
import laravel from 'laravel-vite-plugin'
import vue from '@vitejs/plugin-vue'

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/js/app.js'],
            refresh: true,
        }),
        vue(),
    ],
    server: {
        host: 'laravel-vue.test',  // 👈 your local domain
        port: 5173,               // default Vite port
        cors: true,               // enable CORS
        hmr: {
            host: 'laravel-vue.test',
        },
    },
})
