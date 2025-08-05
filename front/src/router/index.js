import { createRouter, createWebHistory } from "vue-router";
import Welcome from "../components/HelloWorld.vue";
import Login from "../Login.vue";
import Cadastro from "../Cadastro.vue";
import Home from "../Home.vue";

const routes = [
  { path: "/", name: "Welcome", component: Welcome },
  { path: "/login", name: "Login", component: Login },
  { path: "/cadastro", name: "Cadastro", component: Cadastro },
  { path: "/home", name: "Home", component: Home },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

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
