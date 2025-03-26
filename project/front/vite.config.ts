import vue from "@vitejs/plugin-vue";
import { fileURLToPath, URL } from 'node:url';
import { defineConfig } from "vite";

// https://vitejs.dev/config/
export default defineConfig({
  base: '/micro/',
  plugins: [vue()],
  resolve: {
    alias: {
      '@': fileURLToPath(new URL('./src', import.meta.url)),
    }
  },
});
