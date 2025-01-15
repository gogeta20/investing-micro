<script lang="ts" setup>
import { ref } from 'vue';
import { CreateItem } from '@/modules/home/application/useCase/CreateItem';
import RepositorySymfony from '@/modules/home/infrastructure/repositories/RepositorySymfony';
import type { ResponseData } from '@/modules/home/domain/models/ResponseData';
import Toast from '@/components/ToastView.vue';

const launchTest = (backend: string) => {
  console.log(`Lanzando prueba para ${backend}`);
};
const isDark = ref(false);

const toggleTheme = () => {
  isDark.value = !isDark.value;
  document.body.classList.toggle('dark', isDark.value);
};


const generateDetailedReport = async () => {
  try {
    const useCase = new CreateItem(new RepositorySymfony());
    const response = await useCase.execute();
    // toastResolve(response);
  } catch (error) {
    console.error('Error al crear item:', error);
  }
};

const showToast = ref(false);
const toastMessage = ref('');
const toastType = ref('info'); // Puede ser "success", "error", "warning", "info"

const toastResolve = (response: ResponseData) => {
  toastMessage.value = response.message;
  toastType.value = response.status === 200 ? 'success' : 'error';
  showToast.value = true;
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
        <p class="mt-4 text-sm md:text-base lg:text-lg">
          Esta página es una prueba diseñada para medir el rendimiento de diferentes frameworks y tecnologías, como
          Symfony, Django, Spring Boot, RabbitMQ, Rust, MongoDB, MySQL, Nginx, Jenkins, entre otros.
        </p>
        <p class="mt-4 text-sm md:text-base lg:text-lg">
          Nos basamos en CQRS y DDD, utilizando dos bases de datos, una para lectura y otra para escritura. Nuestro
          objetivo principal es probar la consistencia y eficiencia de esta arquitectura.
        </p>
        <p class="mt-4 text-sm md:text-base lg:text-lg">
          Además, Rust actualmente está diseñado para consumir eventos y sincronizar las bases de datos, mientras que
          los tres backends principales (Symfony, Django, y Spring Boot) manejan diferentes tareas del sistema.
        </p>
        <div class="mt-8 flex justify-center flex-col gap-4 sm:flex-row sm:gap-2">
          <button @click="generateDetailedReport()"
            class="px-6 py-3 bg-green-500 hover:bg-green-600 rounded-lg text-white">
            Probar Symfony
          </button>
          <button @click="launchTest('Django')" class="px-6 py-3 bg-green-500 hover:bg-green-600 rounded-lg text-white">
            Probar Django
          </button>
          <button @click="launchTest('Spring Boot')"
            class="px-6 py-3 bg-green-500 hover:bg-green-600 rounded-lg text-white">
            Probar Spring Boot
          </button>
        </div>
      </section>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-4 text-center">
      <div class="flex flex-col sm:flex-row lg:justify-between items-center container mx-auto">
        <p>&copy; 2025 Codigomau</p>
        <button @click="toggleTheme" class="mt-2 sm:mt-0">
          {{ isDark ? '🌞' : '🌚' }}
        </button>
      </div>
    </footer>
    <Toast v-if="showToast" :message="toastMessage" :type="toastType" />
  </div>
</template>

<style scoped>
/* Puedes agregar estilos adicionales si es necesario */
</style>
