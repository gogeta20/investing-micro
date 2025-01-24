<script setup lang="ts">
import lendrockImg from "@/assets/projects/lendrock.png";
import novaruaImg from "@/assets/projects/novarua.png";
import upraImg from "@/assets/projects/upra.png";
import widiImg from "@/assets/projects/widi.png";
import xorforImg from "@/assets/projects/xorfor.png";
import ProjectItem from "@/components/ProjectItem.vue";
import Flickity from "flickity";
import { onMounted } from "vue";

onMounted(() => {
  const carousel = document.querySelector(".carousel");
  if (carousel) {
    new Flickity(carousel, {
      imagesLoaded: true,
      wrapAround: false,
      groupCells: 1,
    });
  }
});
</script>

<template>
  <main class="bg-main">
    <div class="flex h-screen sticky top-0">
      <div class="container m-auto p-5">
        <p class="kode-style pt-4 text-4xl md:text-6xl text-white text-center">
          Web Projects
        </p>

        <br />
        <div
          class="carousel"
          data-flickity='{ "imagesLoaded": true, "wrapAround": false,  "groupCells": 1 }'
        >
          <ProjectItem :img="upraImg" href="https://www.upra.org/es/" title="Upra" />

          <ProjectItem :img="widiImg" href="https://widitrade.com/" title="Widitrade" />

          <ProjectItem :img="novaruaImg" href="https://novarua.es/" title="Novarua" />

          <ProjectItem
            :img="lendrockImg"
            href="https://www.lendrock.com/"
            title="Lendrock"
          />

          <ProjectItem
            :img="xorforImg"
            href="https://ovmediorural.xunta.gal/es/tramites/xorfor"
            title="Xorfor"
          />
        </div>
        <p class="text-white pt-5">La arquitectura principalmente es DDD y CQRS.</p>
        <p class="text-white pt-5">Implementacion de microservicios.</p>
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
