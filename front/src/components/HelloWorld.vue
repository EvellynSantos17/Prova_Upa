<template>
  <div class="min-h-screen flex flex-col items-center justify-center bg-gradient-to-br from-blue-100 to-blue-300 p-6">
    <div class="bg-white shadow-xl rounded-2xl p-10 max-w-md w-full text-center">
      <h1 class="text-3xl font-bold text-blue-700 mb-4">Bem-vindo(a)!</h1>
      <p class="text-gray-700 text-lg mb-6">
        Este é um sistema simples para cadastro de itens por departamento.
      </p>
      <router-link to="/cadastro" class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition">
        Começar
      </router-link>
    </div>
  </div>
</template>

<script setup>
</script>
