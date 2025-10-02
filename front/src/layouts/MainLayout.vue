<template>
  <q-layout view="lHh Lpr lFf">
    <!-- Header -->
    <q-header elevated>
      <q-toolbar>
        <q-btn 
          flat dense round 
          icon="menu" 
          aria-label="Menu" 
          @click="toggleLeftDrawer" 
        />

        <q-toolbar-title> Sistema Botica </q-toolbar-title>

        <div>Quasar v{{ $q.version }}</div>
      </q-toolbar>
    </q-header>

    <!-- Drawer lateral -->
    <q-drawer v-model="leftDrawerOpen" show-if-above bordered>
      <q-list>
        <q-item-label header> Navegación </q-item-label>

        <!-- Link a Inicio -->
        <q-item clickable tag="router-link" to="/">
          <q-item-section avatar>
            <q-icon name="home" />
          </q-item-section>
          <q-item-section>
            <q-item-label>Inicio</q-item-label>
          </q-item-section>
        </q-item>

        <!-- Link a Productos -->
        <q-item clickable tag="router-link" to="/products">
          <q-item-section avatar>
            <q-icon name="inventory_2" />
          </q-item-section>
          <q-item-section>
            <q-item-label>Productos</q-item-label>
          </q-item-section>
        </q-item>

        <!-- Link a Clientes -->
        <q-item clickable tag="router-link" to="/clients">
          <q-item-section avatar>
            <q-icon name="groups" />
          </q-item-section>
          <q-item-section>
            <q-item-label>Clientes</q-item-label>
          </q-item-section>
        </q-item>

        <!-- Link a Ventas -->
        <q-item clickable tag="router-link" to="/sales">
          <q-item-section avatar>
            <q-icon name="point_of_sale" />
          </q-item-section>
          <q-item-section>
            <q-item-label>Ventas</q-item-label>
          </q-item-section>
        </q-item>

        <!-- 🔹 Botón Cerrar Sesión -->
        <q-separator spaced />
        <q-item clickable @click="logout">
          <q-item-section avatar>
            <q-icon name="logout" />
          </q-item-section>
          <q-item-section>
            <q-item-label>Cerrar Sesión</q-item-label>
          </q-item-section>
        </q-item>
      </q-list>
    </q-drawer>

    <!-- Contenedor principal -->
    <q-page-container>
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<script setup>
import { ref } from 'vue'
import Swal from 'sweetalert2'
import { useRouter } from 'vue-router'
import { api } from 'boot/axios' // 👈 usamos el boot con token automático

const leftDrawerOpen = ref(false)
const router = useRouter()

function toggleLeftDrawer() {
  leftDrawerOpen.value = !leftDrawerOpen.value
}

async function logout() {
  const result = await Swal.fire({
    title: '¿Cerrar sesión?',
    text: 'Tu sesión actual se cerrará',
    icon: 'warning',
    showCancelButton: true,
    confirmButtonText: 'Sí, salir',
    cancelButtonText: 'Cancelar'
  })

  if (result.isConfirmed) {
    try {
      await api.post('/logout') // 👈 Laravel API con Sanctum
    } catch (error) {
      console.error('Error en logout:', error.response?.data || error.message)
    } finally {
      // Siempre limpiar el token
      localStorage.removeItem('token')
      router.push('/login')
      Swal.fire('Sesión cerrada', 'Has salido correctamente', 'success')
    }
  }
}
</script>
