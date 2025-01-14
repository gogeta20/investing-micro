<script lang="ts" setup>
import { ref } from 'vue';
const launchTest = (backend: string) => {
  console.log(`Lanzando prueba para ${backend}`);
};
const isDark = ref(false);

const toggleTheme = () => {
  isDark.value = !isDark.value;
  document.body.classList.toggle('dark', isDark.value);
};
</script>
<template>
  <div class="min-h-screen flex flex-col">
    <!-- Header -->
    <header class="bg-blue-500 text-white py-4 shadow-md">
      <div class="container mx-auto px-4 flex justify-between items-center">
        <h1 class="text-xl font-bold">codigomau</h1>
        <nav class="flex gap-4">
          <router-link to="/about" class="px-4 py-2 bg-blue-700 hover:bg-blue-800 rounded-lg text-white">
            About
          </router-link>
        </nav>
      </div>
    </header>

    <!-- Body -->
    <main class="container mx-auto px-4 flex-grow">
      <section class="text-center mt-8">
        <h2 class="text-2xl font-semibold">Bienvenido a codigomau</h2>
        <p class="mt-4">
          Esta página es una prueba diseñada para medir el rendimiento de diferentes frameworks y tecnologías, como
          Symfony, Django, Spring Boot, RabbitMQ, Rust, MongoDB, MySQL, Nginx, Jenkins, entre otros.
        </p>
        <p class="mt-4 ">
          Nos basamos en CQRS y DDD, utilizando dos bases de datos, una para lectura y otra para escritura. Nuestro
          objetivo principal es probar la consistencia y eficiencia de esta arquitectura.
        </p>
        <p class="mt-4 ">
          Además, Rust actualmente está diseñado para consumir eventos y sincronizar las bases de datos, mientras que
          los tres backends principales (Symfony, Django, y Spring Boot) manejan diferentes tareas del sistema.
        </p>
        <div class="mt-8">
          <button @click="launchTest('Symfony')"
            class="px-4 py-2 bg-green-500 hover:bg-green-600 rounded-lg text-white mx-2">
            Probar Symfony
          </button>
          <button @click="launchTest('Django')"
            class="px-4 py-2 bg-green-500 hover:bg-green-600 rounded-lg text-white mx-2">
            Probar Django
          </button>
          <button @click="launchTest('Spring Boot')"
            class="px-4 py-2 bg-green-500 hover:bg-green-600 rounded-lg text-white mx-2">
            Probar Spring Boot
          </button>
        </div>
      </section>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-4 text-center">
      <div class="flex justify-between items-center container mx-auto">
        <p>&copy; 2025 Codigomau - Todos los derechos reservados.</p>
        <button @click="toggleTheme">
          {{ isDark ? '🌞' : '🌚' }}
        </button>
      </div>
    </footer>
  </div>
</template>

<style scoped>
/* Puedes agregar estilos adicionales si es necesario */
</style>
