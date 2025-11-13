<script lang="ts" setup>
import { ref, toRefs } from "vue";
import ItemTree from "@/core/layout/components/ItemTree.vue";
// import BaseRouterLink from "@/core/common/BaseRouterLink.vue";

const props = defineProps({
  dataTree: {
    type: Object,
    default: () => ({}),
  },
  isOpenSideBar: {
    type: Boolean,
  },
});
const { dataTree } = toRefs(props);
const selectedItem = ref<string[]>([]);

function handleOpenSubMenu(id: string) {
  selectedItem.value = selectedItem.value.includes(id)
    ? selectedItem.value.filter((item) => item !== id)
    : [...selectedItem.value, id];
}

function handleClickMenu(path: string) {
  return path;
}
</script>
<template>
  <ul>
    <li v-for="item in dataTree"
      :key="item.id"
      class="bh-nav__item"
      :class="[{ 'bh-open': selectedItem.includes(item.id) }]">

      <a v-if="item.children?.length > 0 && item.isVisible"
        :key="item.id" href="#"
        class="bh-nav__link bh-nav__link--dropdown"
        @click="handleOpenSubMenu(item.id)">
        <span>{{ item.title }} </span>
      </a>

      <ItemTree v-if="item.children?.length === 0 && item.isVisible"
        :nombre="item.title"
        :icon="item.icon"
        class="bh-nav__link"
        iconClass="bh-nav__icon"
        :to="item.path"
        @click="handleClickMenu(item.path)" />

      <ul class="bh-nav bh-collapse">
        <TreeSideBar v-if="item.children?.length > 0" :dataTree="item.children" />
      </ul>

    </li>
  </ul>
</template>
<style lang="scss" scoped>
.bh-sidebar--hidden {
  .bh-nav__link {
    justify-content: flex-end;
  }

  .bh-nav__link,
  .bh-nav__link.bh-nav__link--dropdown {
    :deep(span) {
      display: none;
    }

    &.router-link-exact-active {
      transform: scale(1);
    }

    :deep(.bh-nav__icon) {
      color: var(--tokyo-fg);
      text-align: center;
    }

    &:hover {
      :deep(.bh-nav__icon) {
        font-size: 1.4rem;
        color: var(--tokyo-blue);
      }
    }
  }

  .bh-nav__item>.bh-nav__link--dropdown>svg.fa-chevron-down {
    display: none;
  }
}

.bh-nav {
  &__list {
    list-style: none;
    padding: 0.2rem 0 0 0;
    margin: 0;
  }

  &__item {
    position: relative;
    display: block;
    color: var(--tokyo-fg);
  }

  &__link {
    align-items: center;
    gap: 0.5rem;
    color: inherit;
    position: relative;
    display: flex;
    text-decoration: none;
    padding: 0.5rem 1rem;
    font-size: 1rem;
    overflow: hidden;
    white-space: nowrap;
    transition: all 0.15s linear;

    &:hover {
      background-color: var(--tokyo-bg-tertiary);
      color: var(--tokyo-blue);
    }

    &.router-link-exact-active {
      background-color: var(--tokyo-bg-tertiary);
      color: var(--tokyo-blue);
      font-weight: 500;
    }

    .glyphicon {
      font-size: 0.991rem;
      margin-right: 0.5rem;
      top: 10px;
      line-height: 0;
      vertical-align: text-top;
      position: relative;
      color: var(--tokyo-fg);
    }
  }

  :deep(.bh-nav__icon) {
    font-size: 1.2rem;
    color: var(--tokyo-fg);
  }
}

.bh-nav__link svg {
  transition: all 0.4s ease;
}

.bh-nav__link svg.fa-chevron-down {
  position: absolute;
  right: 12px;
  left: auto;
}

.bh-collapse {
  transition: all 0.4s ease-out;
  opacity: 0;
  height: 0;
  overflow: hidden;
}

.bh-open>.bh-nav__link--dropdown {
  background-color: var(--tokyo-bg-tertiary);
  color: var(--tokyo-blue);
}

.bh-open .bh-collapse .bh-nav__link {
  font-size: 0.875rem;

  &:hover {
    font-size: 1rem;
  }
}

.bh-nav__item>.bh-nav__link--dropdown>svg.fa-chevron-down {
  top: 12px;
  transform: rotate(180deg);
}

.bh-open>.bh-nav__link--dropdown>svg.fa-chevron-down {
  top: 12px;
  transform: initial;
}

.bh-open>.bh-collapse {
  opacity: 1;
  height: auto;
}

.bh-collapse .bh-nav__link {
  padding-left: 2rem;
  text-align: center;
}

.bh-collapse .bh-nav__link:before {
  // content: "•";
  content: "";
  position: absolute;
  top: 3px;
  left: 0.875rem;
  width: 15px;
  height: 100%;
  font-size: 50px;
  line-height: 32px;
  z-index: 2;
  overflow: hidden;
}

.bh-collapse .bh-nav__link:after {
  content: "";
  position: absolute;
  top: 0;
  left: 1.4rem;
  width: 1px;
  height: 100%;
  // background-image: linear-gradient(to bottom, #ccc 50%, rgba(255, 255, 255, 0) 0);
  background-image: none;
  background-position: left;
  background-size: 1px 5px;
  background-repeat: repeat-y;
}
</style>
