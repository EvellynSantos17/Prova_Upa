// router/index.js
import { createRouter, createWebHistory } from "vue-router";
import Welcome from "../components/HelloWorld.vue";
import Login from "../Login.vue";
import Cadastro from "../Cadastro.vue";
import Home from "../Home.vue";
import InventarioView from "../pages/inventarioView.vue";
import Retirada from "../pages/retirada.vue";
import Devolucao from "../pages/devolucao.vue";

const routes = [
  { path: "/", name: "Welcome", component: Welcome },
  { path: "/login", name: "Login", component: Login },
  { path: "/cadastro", name: "Cadastro", component: Cadastro },
  { path: "/home", name: "Home", component: Home },
  { path: "/retirada", name: "Retirada", component: Retirada },
  { path: "/devolucao", name: "Devolucao", component: Devolucao },
  { path: "/inventario", name: "Inventario", component: InventarioView },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

// Proteção de rotas
router.beforeEach((to, from, next) => {
  const publicPages = ["Welcome", "Login", "Cadastro"];
  const authRequired = !publicPages.includes(to.name);
  const isAuthenticated = !!localStorage.getItem("token");

  if (authRequired && !isAuthenticated) {
    return next({ name: "Login" });
  }
  if ((to.name === "Login" || to.name === "Cadastro") && isAuthenticated) {
    return next({ name: "Home" });
  }
  next();
});

export default router;
