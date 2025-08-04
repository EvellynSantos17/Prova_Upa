<template>
  <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-blue-100 to-blue-300">
    <div class="bg-white shadow-lg rounded-xl p-8 w-full max-w-md">
      <h1 class="text-2xl font-bold text-center text-blue-700 mb-6">Login</h1>

      <form @submit.prevent="fazerLogin" class="space-y-4">
        <div>
          <label class="block text-gray-700 mb-1">E-mail</label>
          <input v-model="email" type="email" class="w-full border rounded p-2" required />
        </div>
        <div>
          <label class="block text-gray-700 mb-1">Senha</label>
          <input v-model="senha" type="password" class="w-full border rounded p-2" required />
        </div>

        <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 rounded">
          Entrar
        </button>

        <p class="text-center text-sm mt-4 text-gray-600">
          Não tem uma conta?
          <router-link to="/cadastro" class="text-blue-600 hover:underline">Cadastre-se</router-link>
        </p>
      </form>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import api from './axios'
import { useRouter } from 'vue-router'

const email = ref('')
const senha = ref('')
const router = useRouter()

async function fazerLogin() {
  try {
    const response = await api.post('/login', {
      email: email.value,
      password: senha.value
    })

    // opcional: salvar token, se estiver retornando um
    // localStorage.setItem('token', response.data.token)

    alert('Login realizado com sucesso!')
    router.push('/') // redirecionar para página principal
  } catch (error) {
    alert('Erro ao logar: ' + (error.response?.data?.message || error.message))
  }
}
</script>

