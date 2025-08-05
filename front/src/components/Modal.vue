<template>
  <div
    v-show="visible"
    @click.self="close"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50"
  >
    <div
      class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md mx-auto relative"
    >
      <button
        @click.prevent="close"
        class="absolute top-2 right-2 text-2xl text-gray-600"
      >
        &times;
      </button>
      <slot />
    </div>
  </div>
</template>

<script setup>
import { ref, defineEmits, defineExpose } from "vue";

const visible = ref(false);
const emit = defineEmits(["close"]);

function show() {
  visible.value = true;
}

function close() {
  visible.value = false;
  emit("close");
}

defineExpose({ show });
</script>

<style scoped>
/* O estilo já está embutido no template */
</style>
