<!-- @format -->

<script setup lang="ts">
import TreeSidebar from "@/core/layout/components/TreeSideBar.vue";
import { reactive } from "vue";

defineProps({
  isOpenSideBar: {
    type: Boolean,
  },
});
const myRoutes = reactive([
  {
    id: "1",
    title: "home",
    path: "/home",
    icon: "home",
    isVisible: true,
    children: [],
  },
  {
    id: "2",
    title: "stocks",
    path: "/stocks",
    icon: "plus",
    isVisible: true,
    children: [
      {
        id: "2.1",
        title: "acciones",
        path: "/stocks",
        icon: "plus",
        isVisible: true,
        children: [],
      },
      {
        id: "2.2",
        title: "analisis",
        path: "/analysis/daily",
        icon: "plus",
        isVisible: true,
        children: [],
      },
      {
        id: "2.3",
        title: "notas",
        path: "/notas",
        icon: "plus",
        isVisible: true,
        children: [],
      },
    ],
  }
]);
</script>

<template>
  <aside class="bh-sidebar" :class="[{ 'bh-sidebar--hidden': !isOpenSideBar }]">
    <nav class="bh-nav">
      <TreeSidebar :dataTree="myRoutes" :isOpenSideBar="isOpenSideBar" />
    </nav>
  </aside>
</template>

<style lang="scss" scoped>
.bh-sidebar {
  position: fixed;
  left: 0;
  top: var(--header-height);
  bottom: 0;
  min-width: var(--sidbar-width);
  background-color: var(--tokyo-bg-secondary);
  box-shadow: 2px 0 8px rgba(0, 0, 0, 0.2);
  overflow: auto;
  transition: left 0.3s ease;
  z-index: 1000;
  border-right: 1px solid var(--tokyo-bg-tertiary);

  &--hidden {
    left: -250px;
    overflow-y: auto;

    .bh-nav__list {
      width: 50px;
      margin-left: 190px;
    }

    .bh-nav__link {
      :deep(span) {
        display: none;
      }

      &.router-link-exact-active {
        display: block;
        transform: scale(1);
      }

      :deep(.bh-nav__icon) {
        color: #fff;
        text-align: center;
      }

      &:hover {
        :deep(.bh-nav__icon) {
          font-size: 1.4rem;
          color: #fff;
          text-shadow: 2px 2px rgba(0, 0, 0, 0.8);
        }
      }
    }
  }
}



@media (min-width: 540px) {
  .bh-sidebar {
    opacity: 1;
  }
}
</style>
