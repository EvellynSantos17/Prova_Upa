# Projeto Laravel + Vue

Este é um projeto full-stack com **Laravel 11 (PHP 8.2+)** no backend rodando via **Docker**, e **Vue 3 + Vite + TailwindCSS** no frontend.

---

## 📁 Estrutura do Projeto

- `Back/` &nbsp;&nbsp;# Backend Laravel (Laravel 11 + Sanctum + Telescope)
- `front/` &nbsp;&nbsp;# Frontend Vue 3 + Vite + TailwindCSS

---

## ✅ Pré-requisitos

Certifique-se de ter os seguintes softwares instalados:

- [PHP 8.2+](https://www.php.net/downloads.php)
- [Composer](https://getcomposer.org/)
- [Node.js](https://nodejs.org/) (v18+ recomendado)
- [MySQL](https://www.mysql.com/) ou outro banco suportado
- [NPM](https://www.npmjs.com/) ou [Yarn](https://yarnpkg.com/)

---

## ⚙️ Configuração Completa (Backend e Frontend)

```bash
# Backend Laravel

# Entrar na pasta do backend Laravel
cd Back

# Subir os containers definidos no docker-compose (PHP, MySQL, Redis, etc)
docker compose up -d
# -d: roda em background, sem travar seu terminal
# Agora o ambiente Laravel + banco + serviços já estão no ar

# Entrar no container do app PHP para executar comandos Artisan e Composer
docker exec -it app bash
# 'app' é o nome do container do Laravel PHP (confira no seu docker-compose.yml)

# Dentro do container:

# Instalar as dependências PHP do Laravel via Composer
composer install
# Isso baixa todas as libs necessárias para o Laravel funcionar


# Rodar as migrations para criar as tabelas do banco de dados
 php artisan migrate:fresh --seed

# Sair do container quando terminar os comandos
exit

# Frontend Vue 3 + Vite + TailwindCSS

# Entrar na pasta frontend Vue 3
cd ../front

# Instalar as dependências JS (Node + NPM/Yarn)
npm install
# Ou use 'yarn' se preferir

# Rodar o servidor dev do frontend com hot reload
npm run dev
# Agora o frontend está rodando
```
