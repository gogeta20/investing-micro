<script setup lang="ts">
</script>
<template>
  <main class="bg-blue">
    <div class="flex h-screen  sticky top-0">
      <div class="container m-auto p-5">
        <div class="content">
          <div class="py-3">
            <p class="kode-style text-4xl md:text-6xl text-center text-white">Whoami?</p>
            <p class="text-gray-300 text-xs sm:text-base pt-8 w-6/6">
              Hola, soy Mauricio, un desarrollador apasionado por diseñar e implementar soluciones complejas utilizando
              tecnologías de backend como Symfony php, Django python, Rust y Spring Boot java, junto con modernas
              herramientas de frontend
              como Vue.js.
            </p>
            <p class="text-gray-300 text-xs sm:text-base pt-8 w-6/6">
              He desarrollado sistemas basados en arquitecturas avanzadas como CQRS y DDD, garantizando escalabilidad,
              mantenibilidad y consistencia en los proyectos. Estas arquitecturas están respaldadas por bases de datos
              tanto relacionales (MySQL y OracleSQL) como no relacionales (MongoDB), integradas con RabbitMQ para
              gestionar
              eventos distribuidos de manera eficiente.
            </p>
            <p class="text-gray-300 text-xs sm:text-base pt-8 w-6/6">
              Algunos aspectos destacados de mi enfoque incluyen:
            </p>
            <ul style="list-style-type:circle" class="text-gray-300 text-xs sm:text-base pt-8 w-6/6">
              <li>Backend y Frontend de Alto Rendimiento: Desarrollo de microservicios para garantizar rendimiento y
                escalabilidad.</li><br>
              <li> Automatización de Infraestructura: Configuración y despliegue de entornos de desarrollo y
                producción fluidos con Docker.</li>
            </ul>
            <p class=" text-gray-300 text-xs sm:text-base pt-8 w-6/6">
              Lo que más me apasiona de este campo es su constante evolución. Siempre hay algo nuevo que aprender:
              una tecnología innovadora, una arquitectura emergente o un framework revolucionario. Nunca hay tiempo
              para aburrirse cuando te dedicas a la programación.
            </p>

          </div>
          <div class="roadmap pt-10">
            <div class="flex items-center place-content-evenly">
              <span class="line finished"></span>
              <span class="iconify" data-icon="fa6-brands:php" style="color: #fff" data-width="65"></span>
              <span class="iconify" data-icon="fa6-brands:python" style="color: #fff" data-width="65"></span>
              <span class="iconify" data-icon="fa6-brands:java" style="color: #fff" data-width="45"></span>
              <span class="iconify" data-icon="fa6-brands:rust" style="color: white" data-width="45"></span>
              <span class="iconify" data-icon="ic:baseline-javascript" style="color: #fff" data-width="65"></span>
              <span class="line finished"></span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </main>
</template>



<style scoped>
.line {
    height: 2px;
    width: 40px;
    background-color: #fff;
    float: left;
    margin: 14px 0px;
    padding: 0px;
}
.line.finished {
    height: 2px;
    width: 40px;
    background-color: #d100d1;
    float: left;
    margin: 14px 0px;
    padding: 0px;
}

.carousel div {
    width: 66%;
    margin: 40px;
}

embed {
    max-height: 500px;
}
</style>
