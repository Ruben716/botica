const routes = [
  // 🔹 Rutas públicas (no requieren token)
  {
    path: '/login',
    component: () => import('layouts/AuthLayout.vue'),
    children: [
      { path: '', component: () => import('pages/LoginPage.vue') }
    ]
  },
  {
    path: '/register',
    component: () => import('layouts/AuthLayout.vue'),
    children: [
      { path: '', component: () => import('pages/RegisterPage.vue') }
    ]
  },

  // 🔹 Rutas protegidas (requieren token con auth:sanctum)
  {
    path: '/',
    component: () => import('layouts/MainLayout.vue'),
    children: [
      { path: '', component: () => import('pages/IndexPage.vue') },
      { path: 'products', component: () => import('pages/ProductsPage.vue') },
      { path: 'clients', component: () => import('pages/ClientsPage.vue') },
      { path: 'sales', component: () => import('pages/SalesPage.vue') }
    ],
    meta: { requiresAuth: true }
  },

  // 🔹 Ruta de error (catch-all)
  {
    path: '/:catchAll(.*)*',
    component: () => import('pages/ErrorNotFound.vue')
  }
]

export default routes
