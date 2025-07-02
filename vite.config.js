import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';   // ① deve existir
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
  plugins: [
    laravel({                               // ② plugin precisa estar listado
      input: [
        'resources/css/app.css',
        'resources/js/app.js',
      ],
      buildDirectory: 'build/.vite',        // ③ diretório que definimos antes
    }),
    tailwindcss(),
  ],
  build: {
    emptyOutDir: true,
  },
});
