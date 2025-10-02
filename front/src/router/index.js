import { defineRouter } from '#q-app/wrappers'
import {
  createRouter,
  createMemoryHistory,
  createWebHistory,
  createWebHashHistory,
} from 'vue-router'
import routes from './routes'

export default defineRouter(function (/* { store, ssrContext } */) {
  const createHistory = process.env.SERVER
    ? createMemoryHistory
    : process.env.VUE_ROUTER_MODE === 'history'
      ? createWebHistory
      : createWebHashHistory

  const Router = createRouter({
    scrollBehavior: () => ({ left: 0, top: 0 }),
    routes,
    history: createHistory(process.env.VUE_ROUTER_BASE),
  })

  // 🔹 Middleware de autenticación
  Router.beforeEach((to, from, next) => {
    const token = localStorage.getItem('token')

    if (to.meta.requiresAuth && !token) {
      // No tiene token y la ruta requiere auth → redirigir al login
      next('/login')
    } else if ((to.path === '/login' || to.path === '/register') && token) {
      // Si ya tiene token e intenta entrar a login o registro → mandarlo al home
      next('/')
    } else {
      next()
    }
  })

  return Router
})
