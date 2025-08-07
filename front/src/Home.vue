<template>
  <div class="flex min-h-screen">
    <SidebarMenu />
    <div class="flex-1 bg-gray-50 text-gray-900">
      <header class="bg-white shadow-md py-4 mb-6">
        <div class="max-w-4xl mx-auto px-4 flex justify-between items-center">
          <h1 class="text-3xl font-semibold">Cadastro de Itens</h1>
          <button
            @click="startNew"
            class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md"
          >
            Novo item
          </button>
        </div>
      </header>

      <main class="max-w-4xl mx-auto px-4 divide-y divide-gray-200">
        <!-- Modal de Cadastro -->
        <Modal ref="modal" @close="cancelar">
          <h2 class="text-2xl font-medium mb-4">Novo Item</h2>
          <form @submit.prevent="submit" class="space-y-4">
            <div>
              <label class="block text-sm font-medium mb-1">Código *</label>
              <input
                v-model="form.codigo"
                type="text"
                required
                class="shadow-sm block w-full border-gray-300 rounded-md p-2"
              />
              <p v-if="errors.codigo" class="text-red-600 text-sm mt-1">
                {{ errors.codigo }}
              </p>
            </div>

            <div>
              <label class="block text-sm font-medium mb-1">Nome *</label>
              <input
                v-model="form.nome"
                type="text"
                required
                class="shadow-sm block w-full border-gray-300 rounded-md p-2"
              />
              <p v-if="errors.nome" class="text-red-600 text-sm mt-1">
                {{ errors.nome }}
              </p>
            </div>

            <div>
              <label class="block text-sm font-medium mb-1">Descrição</label>
              <textarea
                v-model="form.descricao"
                rows="3"
                class="shadow-sm block w-full border-gray-300 rounded-md p-2"
              ></textarea>
              <p v-if="errors.descricao" class="text-red-600 text-sm mt-1">
                {{ errors.descricao }}
              </p>
            </div>

            <div>
              <label class="block text-sm font-medium mb-1">Setor *</label>
              <select
                v-model="form.setor_id"
                required
                class="shadow-sm block w-full border-gray-300 rounded-md p-2"
              >
                <option value="" disabled>Selecione um setor</option>
                <option
                  v-for="setor in setores"
                  :key="setor.id"
                  :value="setor.id"
                >
                  {{ setor.nome }}
                </option>
              </select>
              <p v-if="errors.setor_id" class="text-red-600 text-sm mt-1">
                {{ errors.setor_id }}
              </p>
            </div>

            <div class="flex gap-3 justify-end">
              <button
                type="submit"
                class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md"
              >
                Salvar
              </button>
              <button
                type="button"
                @click="cancelar"
                class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md"
              >
                Cancelar
              </button>
            </div>
          </form>
        </Modal>

        <!-- Modal de Exclusão -->
        <Modal ref="deleteModal" @close="cancelarExclusao">
          <h2 class="text-xl font-semibold mb-4">Excluir Itens</h2>
          <p class="mb-4">Selecione os itens que deseja excluir:</p>
          <div class="max-h-64 overflow-y-auto mb-6">
            <div
              v-for="item in itensParaExclusao"
              :key="item.id"
              class="flex items-center mb-2"
            >
              <input
                type="checkbox"
                :id="`item-${item.id}`"
                :value="item.id"
                v-model="selecionados"
                class="mr-2"
              />
              <label :for="`item-${item.id}`">
                <span class="font-medium">{{ item.nome }}</span>
                <small class="text-gray-500 ml-2">({{ item.codigo }})</small>
              </label>
            </div>
          </div>
          <div class="flex justify-end gap-3">
            <button
              @click="confirmarExclusao"
              :disabled="selecionados.length === 0"
              class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md disabled:opacity-50"
            >
              Excluir Selecionados
            </button>
            <button
              @click="cancelarExclusao"
              class="bg-gray-300 hover:bg-gray-400 text-gray-800 px-4 py-2 rounded-md"
            >
              Cancelar
            </button>
          </div>
        </Modal>

        <!-- Lista de Itens -->
        <section class="py-6">
          <h2 class="text-2xl font-medium mb-4">Itens Cadastrados</h2>
          <div class="overflow-x-auto bg-white shadow-md rounded-md">
            <table class="min-w-full">
              <thead class="bg-gray-100">
                <tr>
                  <th class="px-4 py-2 text-left">Código</th>
                  <th class="px-4 py-2 text-left">Nome</th>
                  <th class="px-4 py-2 text-left">Qtd. Estoque</th>
                  <th class="px-4 py-2 text-left">Descrição</th>
                  <th class="px-4 py-2 text-left">Setor</th>
                  <th class="px-4 py-2 text-left">Ações</th>
                </tr>
              </thead>
              <tbody>
                <tr
                  v-for="item in itens"
                  :key="item.id"
                  class="hover:bg-gray-50 border-t"
                >
                  <td class="px-4 py-2">{{ item.codigo }}</td>
                  <td class="px-4 py-2">{{ item.nome }}</td>
                  <td class="px-4 py-2">{{ item.estoque?.quantidade ?? 0 }}</td>
                  <td class="px-4 py-2">{{ item.descricao || "-" }}</td>
                  <td class="px-4 py-2">{{ item.setor?.nome || "-" }}</td>
                  <td class="px-4 py-2 text-sm text-right">
                    <button
                      @click="abrirModalExcluir"
                      class="text-red-600 hover:text-red-800"
                    >
                      Excluir
                    </button>
                  </td>
                </tr>
                <tr v-if="itens.length === 0">
                  <td colspan="6" class="text-center py-4 text-gray-500">
                    Nenhum item encontrado.
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </section>
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from "vue";
import SidebarMenu from "./components/SidebarMenu.vue";
import Modal from "./components/Modal.vue";
import api from "./axios";

const modal = ref(null);
const deleteModal = ref(null);

const itens = ref([]); // Usado na tabela principal
const itensParaExclusao = ref([]); // Usado no modal de exclusão
const setores = ref([]);
const selecionados = ref([]);

const form = reactive({
  codigo: "",
  nome: "",
  descricao: "",
  setor_id: "",
});

const errors = reactive({});

// Intercepta erros 422
api.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response?.status === 422) throw error.response;
    return Promise.reject(error);
  }
);

async function loadItens() {
  try {
    const { data } = await api.get("/itens");
    itens.value = data ?? [];
  } catch {
    itens.value = [];
  }
}

async function loadSetores() {
  try {
    const { data } = await api.get("/setores");
    setores.value = data ?? [];
  } catch {
    setores.value = [];
  }
}

function startNew() {
  form.codigo = "";
  form.nome = "";
  form.descricao = "";
  form.setor_id = "";
  clearErrors();
  modal.value.show();
}

function cancelar() {
  modal.value.close();
  clearErrors();
}

async function submit() {
  clearErrors();
  try {
    await api.post("/create/itens", {
      codigo: form.codigo,
      nome: form.nome,
      descricao: form.descricao,
      quantidade: 1,
      setor_id: form.setor_id,
    });
    await loadItens();
    modal.value.close();
  } catch (error) {
    if (error.status === 422) {
      const errs = error.data.errors || {};
      Object.entries(errs).forEach(([k, v]) => (errors[k] = v[0]));
    }
  }
}

async function abrirModalExcluir() {
  selecionados.value = [];
  try {
    const { data } = await api.get("/itens-all");
    itensParaExclusao.value = data ?? [];
    deleteModal.value.show();
  } catch {
    alert("Erro ao carregar itens para exclusão.");
  }
}

function cancelarExclusao() {
  deleteModal.value.close();
}

async function confirmarExclusao() {
  if (selecionados.value.length === 0) return;
  try {
    await Promise.all(
      selecionados.value.map((id) => api.delete(`/itens/${id}`))
    );
    await loadItens();
    deleteModal.value.close();
  } catch {
    alert("Erro ao excluir itens.");
  }
}

function clearErrors() {
  Object.keys(errors).forEach((key) => (errors[key] = ""));
}

onMounted(() => {
  loadItens();
  loadSetores();
});
</script>
