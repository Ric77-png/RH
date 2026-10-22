import { fileURLToPath, URL } from 'node:url'

import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import vueDevTools from 'vite-plugin-vue-devtools'

// https://vite.dev/config/
export default defineConfig({
  plugins: [
    vue(),
    vueDevTools(),
  ],
  resolve: {
    alias: {
      '@': fileURLToPath(new URL('./src', import.meta.url)),
    },
  },
  
  server: {
    proxy: {
      '/api': {
        target: 'http://localhost:8080', // L'adresse de votre serveur CodeIgniter
        changeOrigin: true,
        // On réécrit l'URL : /api/register devient /api/register (on garde le /api car vos routes CI4 commencent par 'api/')
        rewrite: (path) => path.replace(/^\/api/, '/api') 
      }
    }
  }
})