import { defineConfig, loadEnv } from 'vite'
import laravel from 'laravel-vite-plugin'
import vue from '@vitejs/plugin-vue'

export default defineConfig(({ mode }) => {
    const appUrl = loadEnv(mode, process.cwd(), '').APP_URL
    const host = appUrl ? new URL(appUrl).hostname : 'localhost'

    return {
        plugins: [
            laravel({
                input: ['resources/js/app.js'],
                refresh: true,
            }),
            vue(),
        ],
        server: {
            host,
            port: 5173,
            cors: true,
            hmr: {
                host,
            },
        },
    }
})
