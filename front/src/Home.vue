<template>
  <div class="flex min-h-screen">
    <SidebarMenu />

    <div class="flex-1 bg-gray-50 text-gray-900 ml-64">
      <header class="bg-white shadow-md py-4 mb-6">
        <div class="max-w-4xl mx-auto px-4 flex items-center justify-between">
          <h1 class="text-3xl font-semibold">Cadastro de Itens</h1>
          <div class="p-6">
            <button
              @click="startNew"
              class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md"
            >
              Novo item
            </button>
          </div>
        </div>
      </header>

      <main class="max-w-4xl mx-auto px-4 divide-y divide-gray-200">
        <!-- Modal -->
        <Modal :visible="showModal" @close="cancelar">
          <h2 class="text-2xl font-medium mb-4">
            {{ isEdit ? "Editar Item" : "Novo Item" }}
          </h2>
          <form @submit.prevent="submit" class="space-y-4">
            <div>
              <label class="block text-sm font-medium mb-1">
                Código <span class="text-red-600">*</span>
              </label>
              <input
                v-model="form.codigo"
                :disabled="isEdit"
                type="text"
                required
                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
              />
              <p v-if="errors.codigo" class="text-red-600 text-sm mt-1">
                {{ errors.codigo }}
              </p>
            </div>

            <div>
              <label class="block text-sm font-medium mb-1">
                Nome <span class="text-red-600">*</span>
              </label>
              <input
                v-model="form.nome"
                type="text"
                required
                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
              />
              <p v-if="errors.nome" class="text-red-600 text-sm mt-1">
                {{ errors.nome }}
              </p>
            </div>

            <div class="flex gap-3 justify-end">
              <button
                type="submit"
                class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-md"
              >
                {{ isEdit ? "Atualizar" : "Salvar" }}
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
                  <th class="px-4 py-2">Ações</th>
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
                  <td class="px-4 py-2 text-sm text-right">
                    <button
                      @click="editar(item)"
                      class="text-indigo-600 hover:text-indigo-800 font-medium mr-2"
                    >
                      Editar
                    </button>
                    <button
                      @click="excluir(item.id)"
                      class="text-red-600 hover:text-red-800 font-medium"
                    >
                      Excluir
                    </button>
                  </td>
                </tr>
                <tr v-if="itens.length === 0">
                  <td colspan="4" class="text-center py-4 text-gray-500">
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
import { ref, reactive, computed, onMounted } from "vue";
import axios from "axios";
import SidebarMenu from "./components/SidebarMenu.vue";
import Modal from "./components/Modal.vue";

const itens = ref([]);
const showModal = ref(false);
const editingId = ref(null);

const form = reactive({
  codigo: "",
  nome: "",
});

const errors = reactive({});

const isEdit = computed(() => editingId.value !== null);

const API = axios.create({
  baseURL: import.meta.env.VITE_APP_API_URL || "/api/v1",
  withCredentials: true,
});

API.interceptors.response.use(
  (response) => response,
  (error) => {
    if (error.response?.status === 422) {
      throw error.response;
    }
    return Promise.reject(error);
  }
);

async function loadItens() {
  try {
    const { data } = await API.get("/itens");
    itens.value = data.data;
  } catch (e) {
    console.error("Erro ao carregar itens:", e);
  }
}

function startNew() {
  console.log("showModal:", showModal.value); // 👈 TESTE
  editingId.value = null;
  form.codigo = "";
  form.nome = "";
  showModal.value = true;
}

function cancelar() {
  showModal.value = false;
  clearErrors();
}

async function submit() {
  clearErrors();
  try {
    const payload = {
      codigo: form.codigo,
      nome: form.nome,
    };

    if (isEdit.value) {
      await API.put(`/itens/${editingId.value}`, payload);
      alert("Item atualizado com sucesso.");
    } else {
      const resp = await API.post("/create/itens", payload);
      alert(resp.data.message || "Item salvo com sucesso.");
    }

    await loadItens();
    showModal.value = false;
  } catch (errResp) {
    if (errResp.status === 422) {
      const payload = errResp.data.error
        ? { nome: errResp.data.error }
        : errResp.data.errors || {};
      for (const key in payload) errors[key] = payload[key][0] || payload[key];
      alert(errResp.data.error || errResp.data.message || "Dados inválidos.");
    } else {
      console.error(errResp);
      alert("Falha ao salvar item.");
    }
  }
}

function editar(item) {
  editingId.value = item.id;
  form.codigo = item.codigo;
  form.nome = item.nome;
  showModal.value = true;
}

async function excluir(id) {
  if (!confirm("Tem certeza que deseja excluir este item?")) return;
  try {
    await API.delete(`/itens/${id}`);
    await loadItens();
  } catch (e) {
    console.error("Erro ao excluir:", e);
  }
}

function clearErrors() {
  for (const key in errors) {
    errors[key] = "";
  }
}

onMounted(loadItens);
</script>
