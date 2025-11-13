<!-- @format -->

<script setup lang="ts">
import { ref } from "vue";
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
  <div class="base-layout">
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
.base-layout {
  min-height: 100vh;
  background-color: var(--tokyo-bg);
}

main {
  min-height: 100vh;
  padding-top: var(--header-height);
}

section {
  min-height: calc(100vh - var(--header-height));
}

.page-fade-enter-active,
.page-fade-leave-active {
  transition: opacity 0.3s ease;
}

.page-fade-enter-from,
.page-fade-leave-to {
  opacity: 0;
}
</style>
