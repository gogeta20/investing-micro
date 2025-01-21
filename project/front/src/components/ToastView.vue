<script lang="ts" setup>
import { computed } from 'vue';
import { useMeToast } from '@/core/hooks/ToastStore';
const storeToast = useMeToast();
const toasts = computed(() => storeToast.toasts.filter(toast => toast.isVisible));

const toastClass = (type: string) => {
  switch (type) {
    case 'success':
      return 'bg-green-500';
    case 'error':
      return 'bg-red-500';
    case 'warning':
      return 'bg-yellow-500';
    default:
      return 'bg-blue-500';
  }
};

</script>

<template>
  <div class="fixed bottom-5 right-5 space-y-2">
    <transition-group name="fade" tag="div">
      <div v-for="toast in toasts" :key="toast.id">
        <div :key="toast.id" :class="['px-4 py-2 rounded-lg shadow-md text-white', toastClass(toast.type)]"
          class="flex items-center gap-2">
          <span>{{ toast.message }}</span>
        </div>
      </div>
    </transition-group>
  </div>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.5s;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
