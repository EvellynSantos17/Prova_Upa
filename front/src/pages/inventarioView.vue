<template>
  <div class="flex h-screen">
    <!-- Menu lateral -->
    <SidebarMenu />

    <!-- Conteúdo principal -->
    <div class="flex-1 p-8 overflow-y-auto">
      <header class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">Inventário</h1>
        <div class="flex items-center space-x-2">
          <input
            v-model="search"
            type="text"
            placeholder="Pesquisar item..."
            class="p-2 border rounded w-64"
          />
          <button
            class="p-2 bg-blue-600 text-white rounded hover:bg-blue-700"
            @click="loadItens"
          >
            🔍
          </button>
        </div>
      </header>

      <!-- Tabela -->
      <table class="table-auto w-full bg-white rounded shadow text-center">
        <thead class="bg-gray-100">
          <tr>
            <th class="px-4 py-2 border">Código</th>
            <th class="px-4 py-2 border">Nome</th>
            <th class="px-4 py-2 border">Descrição</th>
            <th class="px-4 py-2 border">Ações</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="(item, index) in filteredItems"
            :key="item.id"
            class="hover:bg-gray-50"
          >
            <td class="border px-4 py-2">{{ item.codigo }}</td>
            <td class="border px-4 py-2">{{ item.nome }}</td>
            <td class="border px-4 py-2">{{ item.descricao || "-" }}</td>
            <td class="border px-4 py-2">
              <button
                class="bg-red-500 text-white px-3 py-1 rounded"
                @click="excluirItem(index)"
              >
                Excluir
              </button>
            </td>
          </tr>

          <tr v-if="filteredItems.length === 0">
            <td colspan="4" class="py-4 text-gray-500">
              Nenhum item encontrado.
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from "vue";
import api from "../axios";
import SidebarMenu from "../components/SidebarMenu.vue";

const itens = ref([]);
const search = ref("");

// Computed: filtra os itens com base na pesquisa
const filteredItems = computed(() => {
  if (!search.value.trim()) return itens.value;
  return itens.value.filter((item) =>
    item.nome.toLowerCase().includes(search.value.toLowerCase())
  );
});

// Função para carregar os itens
async function loadItens() {
  try {
    const { data } = await api.get("/itens-all");
    itens.value = data;
  } catch (error) {
    console.error("Erro ao carregar itens:", error);
    itens.value = [];
  }
}

// Função para excluir um item (via estoque)
async function excluirItem(index) {
  const item = itens.value[index];

  if (confirm(`Tem certeza que deseja excluir o item "${item.nome}"?`)) {
    try {
      // Aqui usamos DELETE na rota de estoque
      await api.delete(`/estoques/${item.id}`);
      itens.value.splice(index, 1); // remove da tabela local
    } catch (error) {
      console.error("Erro ao excluir:", error);
      alert("Erro ao excluir o item.");
    }
  }
}

onMounted(() => {
  loadItens();
});
</script>
