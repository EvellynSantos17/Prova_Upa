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
        <!-- Modal para cadastro/edição -->
        <Modal ref="modal" @close="cancelar">
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

            <div>
              <label class="block text-sm font-medium mb-1">Descrição</label>
              <textarea
                v-model="form.descricao"
                rows="3"
                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                placeholder="Descrição opcional"
              ></textarea>
              <p v-if="errors.descricao" class="text-red-600 text-sm mt-1">
                {{ errors.descricao }}
              </p>
            </div>

            <div>
              <label class="block text-sm font-medium mb-1">
                Quantidade <span class="text-red-600">*</span>
              </label>
              <input
                v-model.number="form.quantidade"
                type="number"
                min="1"
                required
                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
              />
              <p v-if="errors.quantidade" class="text-red-600 text-sm mt-1">
                {{ errors.quantidade }}
              </p>
            </div>

            <div>
              <label class="block text-sm font-medium mb-1">
                Setor <span class="text-red-600">*</span>
              </label>
              <select
                v-model="form.setor_id"
                required
                class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
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
                  <th class="px-4 py-2 text-left">Descrição</th>
                  <th class="px-4 py-2 text-left">Setor</th>
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
                  <td class="px-4 py-2">{{ item.descricao || "-" }}</td>
                  <td class="px-4 py-2">{{ item.setor?.nome || "-" }}</td>
                  <td class="px-4 py-2 text-sm text-right">
                    <button
                      @click="editar(item)"
                      class="text-indigo-600 hover:text-indigo-800 mr-2"
                    >
                      Editar
                    </button>
                    <button
                      @click="excluir(item.id)"
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
import { ref, reactive, computed, onMounted } from "vue";
import SidebarMenu from "./components/SidebarMenu.vue";
import Modal from "./components/Modal.vue";
import api from "./axios";

const modal = ref(null);
const itens = ref([]);
const setores = ref([]);
const editingId = ref(null);

const form = reactive({
  codigo: "",
  nome: "",
  descricao: "",
  quantidade: 1,
  setor_id: "",
});

const errors = reactive({});
const isEdit = computed(() => editingId.value !== null);

// Intercepta erros 422 para exibir no formulário
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
  } catch (error) {
    console.error("Erro ao carregar itens:", error);
    itens.value = [];
  }
}

async function loadSetores() {
  try {
    const { data } = await api.get("/setores");
    setores.value = data ?? [];
  } catch (error) {
    console.error("Erro ao carregar setores:", error);
    setores.value = [];
  }
}

function startNew() {
  editingId.value = null;
  form.codigo = "";
  form.nome = "";
  form.descricao = "";
  form.quantidade = 1;
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
    const payload = {
      codigo: form.codigo,
      nome: form.nome,
      descricao: form.descricao,
      quantidade: form.quantidade,
      setor_id: form.setor_id,
    };

    if (isEdit.value) {
      await api.put(`/itens/${editingId.value}`, payload);
      alert("Item atualizado com sucesso.");
    } else {
      const response = await api.post("/create/itens", payload);
      alert(response.data.message || "Item salvo com sucesso.");
    }

    await loadItens();
    modal.value.close();
  } catch (error) {
    console.error("Erro ao salvar item:", error);
    if (error.status === 422) {
      const errs = error.data.errors || {};
      Object.keys(errs).forEach((key) => (errors[key] = errs[key][0]));
      alert(error.data.message || "Dados inválidos.");
    } else {
      alert("Falha ao salvar item.");
    }
  }
}

function editar(item) {
  editingId.value = item.id;
  form.codigo = item.codigo;
  form.nome = item.nome;
  form.descricao = item.descricao ?? "";
  form.quantidade = item.estoque?.quantidade ?? 1;
  form.setor_id = item.setor?.id ?? "";
  clearErrors();
  modal.value.show();
}

async function excluir(id) {
  if (!confirm("Tem certeza que deseja excluir este item?")) return;

  try {
    await api.delete(`/itens/${id}`);
    await loadItens();
  } catch (error) {
    console.error("Erro ao excluir item:", error);
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
