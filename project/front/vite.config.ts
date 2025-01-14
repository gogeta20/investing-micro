import { fileURLToPath, URL } from 'node:url'
import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import vueJsx from '@vitejs/plugin-vue-jsx'
import vueDevTools from 'vite-plugin-vue-devtools'

export default defineConfig({
  plugins: [
    vue(),
    vueJsx(),
    vueDevTools(),
  ],
  resolve: {
    alias: {
      '@': fileURLToPath(new URL('./src', import.meta.url))
    }
  },
  css: {
    postcss: './postcss.config.ts',
    preprocessorOptions: {
      scss: {
        additionalData: `
          @import "@/core/styles/variables.scss";
        `,
      },
    },
  },
  server: {
    proxy: {
      "/api/": {
        target: "http://nginx_proxy:80",
        secure: false,
      },
    },
  },
})
