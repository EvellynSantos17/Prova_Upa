<template>
  <div class="flex h-screen">
    <!-- Menu lateral fixo -->
    <SidebarMenu />

    <!-- Conteúdo principal -->
    <div class="flex-1 p-10 overflow-y-auto">
      <div class="max-w-2xl mx-auto bg-white p-6 rounded shadow">
        <h2 class="text-2xl font-bold mb-6 text-center">
          Retirada de Equipamento
        </h2>

        <form @submit.prevent="submitForm" class="space-y-4">
          <!-- Código -->
          <div>
            <label for="codigo" class="block font-medium text-gray-700">
              Código do Item:
            </label>
            <input
              v-model="form.codigo"
              id="codigo"
              type="text"
              required
              class="mt-1 w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-300"
            />
          </div>

          <!-- Motivo -->
          <div>
            <label for="motivo" class="block font-medium text-gray-700">
              Motivo da Retirada:
            </label>
            <textarea
              v-model="form.descricao"
              id="motivo"
              rows="3"
              required
              class="mt-1 w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring focus:border-blue-300"
            ></textarea>
          </div>

          <!-- Botão -->
          <div class="text-center">
            <button
              type="submit"
              class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition"
            >
              Confirmar Retirada
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { reactive } from "vue";
import SidebarMenu from "../components/SidebarMenu.vue";
import api from "../axios";

const form = reactive({
  codigo: "",
  descricao: "",
});

const submitForm = async () => {
  try {
    const { data: item } = await api.get(`/itens/codigo/${form.codigo}`);

    await api.post("/movimentacao", {
      item_id: item.id,
      tipo: "devolucao",
      motivo_retirada: form.descricao,
    });

    alert("Movimentação registrada com sucesso!");

    form.codigo = "";
    form.descricao = "";
  } catch (error) {
    console.error("Erro ao registrar movimentação:", error);
    if (error.response?.data?.message) {
      alert(`Erro: ${error.response.data.message}`);
    } else {
      alert("Erro inesperado. Tente novamente.");
    }
  }
};
</script>
