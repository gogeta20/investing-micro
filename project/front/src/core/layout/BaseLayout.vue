<!-- @format -->

<script setup lang="ts">
import { ref } from "vue";
import ParticlesView from "@/components/ParticlesView.vue";
import Header from "@/core/layout/components/Header.vue";
import SideBar from "@/core/layout/components/SideBar.vue";

const isOpenMenu = ref(false);

const openSidBar = async () => {
  isOpenMenu.value = !isOpenMenu.value;
};

const closeSideBar = () => {
  isOpenMenu.value = false;
};
</script>

<template>
  <div>
    <ParticlesView />
    <SideBar :isOpenSideBar="isOpenMenu" />

    <main @click="closeSideBar">
      <Header @menu-toggle="openSidBar" />
      <section>
        <Transition name="page-fade" mode="out-in">
          <RouterView />
        </Transition>
      </section>
    </main>
  </div>
</template>

<style lang="scss" scoped>
.page-fade-enter-active,
.page-fade-leave-active {
  transition: opacity 0.8s ease, filter 0.8s ease;
}

.page-fade-enter-from,
.page-fade-leave-to {
  opacity: 0;
  filter: blur(10px);
}

.page-fade-enter-to,
.page-fade-leave-from {
  opacity: 1;
  filter: blur(0);
}
</style>
