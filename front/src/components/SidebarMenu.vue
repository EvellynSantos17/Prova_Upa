<template>
  <aside
    class="w-64 bg-gray-900 text-white h-screen flex flex-col justify-between"
  >
    <nav class="p-4">
      <ul>
        <li
          v-for="(item, index) in menuItems"
          :key="index"
          class="mb-2 group"
          @mouseenter="hoveredIndex = index"
          @mouseleave="hoveredIndex = null"
        >
          <div
            class="flex items-center justify-between cursor-pointer p-2 rounded hover:bg-gray-700"
            @click="navigateTo(item)"
          >
            <div class="flex items-center gap-2">
              <span class="text-lg">{{ item.icon }}</span>
              <span>{{ item.label }}</span>
            </div>
            <svg
              v-if="item.submenu"
              class="w-4 h-4 transform transition-transform"
              :class="{ 'rotate-180': hoveredIndex === index }"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path
                stroke-linecap="round"
                stroke-linejoin="round"
                stroke-width="2"
                d="M19 9l-7 7-7-7"
              />
            </svg>
          </div>

          <!-- SUBMENU -->
          <ul
            v-if="item.submenu && hoveredIndex === index"
            class="ml-6 mt-1 space-y-1"
          >
            <li
              v-for="(sub, subIndex) in item.submenu"
              :key="subIndex"
              class="p-1 pl-3 hover:bg-gray-700 rounded cursor-pointer"
              @click="navigateTo(sub)"
            >
              {{ sub.label }}
            </li>
          </ul>
        </li>
      </ul>
    </nav>

    <div
      class="p-4 border-t border-gray-800 cursor-pointer hover:bg-gray-700 rounded flex items-center gap-2"
      @click="logout"
    >
      <span class="text-lg">🚪</span>
      <span>Desconectar</span>
    </div>
  </aside>
</template>

<script setup>
import { ref } from "vue";
import { useRouter } from "vue-router";

const hoveredIndex = ref(null);
const router = useRouter();

const menuItems = [
  {
    label: "Início",
    icon: "🏠",
    route: "/home",
  },
  {
    label: "Retirada",
    icon: "📦",
    submenu: [
      { label: "Retirou", route: "/retirada" },
      { label: "Devolver", route: "/retirada" },
    ],
  },
  {
    label: "Inventário",
    icon: "📋",
    route: "/inventario",
  },
];

function navigateTo(item) {
  if (item.route) {
    router.push(item.route);
  }
}

function logout() {
  localStorage.clear();
  router.replace({ name: "Login" });
}
</script>
