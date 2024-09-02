import { createRouter, createWebHistory } from "vue-router";
import Login from "@/components/auth/login.vue";
import register from "@/components/auth/register.vue";
import Dashboard from "@/components/dashboard.vue";
import DetailsView from "@/components/invoices/invoiceDetail.vue";

const routes = [
  { path: "/", redirect: "/login" },
  { path: "/login", component: Login },
  { path: "/register", component: register },
  { path: "/dashboard", component: Dashboard, meta: { requiresAuth: true } },
  {
    path: "/details/:id",
    component: DetailsView,
    meta: { requiresAuth: true },
  },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

// Guardar rutas protegidas
router.beforeEach((to, from, next) => {
  const isAuthenticated = !!localStorage.getItem("authToken");

  if (to.meta.requiresAuth && !isAuthenticated) {
    next("/login");
  } else {
    next();
  }
});

export default router;
