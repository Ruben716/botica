import { boot } from 'quasar/wrappers'
import axios from 'axios'

// 🔹 Aquí pones la URL de tu backend Laravel
const api = axios.create({
  baseURL: 'http://127.0.0.1:8000/api'
})

// 🔹 Interceptor para adjuntar token automáticamente
api.interceptors.request.use(config => {
  const token = localStorage.getItem('token')
  if (token) {
    config.headers.Authorization = `Bearer ${token}`
  }
  return config
}, error => {
  return Promise.reject(error)
})

export default boot(({ app }) => {
  // Hacemos accesible Axios desde cualquier componente con this.$axios o this.$api
  app.config.globalProperties.$axios = axios
  app.config.globalProperties.$api = api
})

export { axios, api }
