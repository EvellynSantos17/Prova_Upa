<template>
  <div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="bg-white shadow-lg rounded-xl p-8 w-full max-w-md">
      <h1 class="text-2xl font-bold text-center text-green-700 mb-6">Cadastro</h1>

      <form @submit.prevent="cadastrar" class="space-y-4">
        <div>
          <label class="block text-gray-700 mb-1">Nome</label>
          <input v-model="nome" type="text" class="w-full border rounded p-2" required />
        </div>
        <div>
          <label class="block text-gray-700 mb-1">E-mail</label>
          <input v-model="email" type="email" class="w-full border rounded p-2" required />
        </div>
        <div>
          <label class="block text-gray-700 mb-1">Senha</label>
          <input v-model="senha" type="password" class="w-full border rounded p-2" required />
        </div>

        <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white py-2 rounded">
          Cadastrar
        </button>

        <p class="text-center text-sm mt-4 text-gray-600">
          Já tem conta?
          <router-link to="/login" class="text-green-600 hover:underline">Entrar</router-link>
        </p>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const nome = ref('')
const email = ref('')
const senha = ref('')

function cadastrar() {
  alert(`Cadastro feito para: ${nome.value}`)
}
</script>
