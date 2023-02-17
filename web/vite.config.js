import { fileURLToPath, URL } from 'node:url'

import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'

// https://vitejs.dev/config/
export default defineConfig({
    plugins: [vue()],
    resolve: {
        alias: {
            '@': fileURLToPath(new URL('./src', import.meta.url))
        }
    },
    build: {
        outDir: '../public'
    },
    server: {
        '/api': {
            target: 'http://localhost:8000/api',
            changeOrigin: true,
            rewrite: (path) => path.replace(/^\/api/, ''),
        }
    }
})
