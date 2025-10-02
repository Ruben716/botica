<template>
  <div class="q-pa-md">
    <q-btn label="Probar API" color="primary" @click="getProducts" />
    <pre>{{ products }}</pre>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { api } from 'boot/axios'

const products = ref([])

const getProducts = async () => {
  try {
    const { data } = await api.get('/products')
    products.value = data
  } catch (error) {
    console.error('Error al conectar con Laravel API:', error)
  }
}
</script>
