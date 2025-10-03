<template>
  <q-layout view="lHh Lpr lFf">
    <!-- Header -->
    <q-header elevated class="bg-gradient-header">
      <q-toolbar>
        <q-btn 
          flat 
          dense 
          round 
          icon="menu" 
          aria-label="Menu" 
          @click="toggleLeftDrawer"
          class="text-white"
        />

        <q-toolbar-title class="text-white font-semibold">
          <div class="flex items-center gap-2">
            <q-icon name="local_pharmacy" size="28px" />
            <span>Sistema Botica</span>
          </div>
        </q-toolbar-title>

        <q-chip 
          outline 
          icon="info" 
          text-color="white" 
          class="text-xs"
        >
          v{{ $q.version }}
        </q-chip>
      </q-toolbar>
    </q-header>

    <!-- Drawer lateral -->
    <q-drawer 
      v-model="leftDrawerOpen" 
      show-if-above 
      bordered
      class="bg-drawer"
    >
      <div class="q-pa-md text-center bg-gradient-drawer">
        <q-avatar size="80px" class="q-mb-sm shadow-lg">
          <q-icon name="medication" size="48px" color="white" />
        </q-avatar>
        <div class="text-h6 text-white font-bold">Farmacia</div>
        <div class="text-caption text-purple-2">Sistema de Gestión</div>
      </div>

      <q-list class="q-pt-md">
        <q-item-label header class="text-purple-8 font-semibold text-uppercase">
          Navegación
        </q-item-label>

        <!-- Link a Inicio -->
        <q-item 
          clickable 
          tag="router-link" 
          to="/"
          class="menu-item"
          active-class="active-menu-item"
        >
          <q-item-section avatar>
            <q-icon name="home" color="purple-6" />
          </q-item-section>
          <q-item-section>
            <q-item-label class="text-weight-medium">Inicio</q-item-label>
          </q-item-section>
        </q-item>

        <!-- Link a Productos -->
        <q-item 
          clickable 
          tag="router-link" 
          to="/products"
          class="menu-item"
          active-class="active-menu-item"
        >
          <q-item-section avatar>
            <q-icon name="inventory_2" color="cyan-6" />
          </q-item-section>
          <q-item-section>
            <q-item-label class="text-weight-medium">Productos</q-item-label>
          </q-item-section>
        </q-item>

        <!-- Link a Clientes -->
        <q-item 
          clickable 
          tag="router-link" 
          to="/clients"
          class="menu-item"
          active-class="active-menu-item"
        >
          <q-item-section avatar>
            <q-icon name="groups" color="purple-6" />
          </q-item-section>
          <q-item-section>
            <q-item-label class="text-weight-medium">Clientes</q-item-label>
          </q-item-section>
        </q-item>

        <!-- Link a Ventas -->
        <q-item 
          clickable 
          tag="router-link" 
          to="/sales"
          class="menu-item"
          active-class="active-menu-item"
        >
          <q-item-section avatar>
            <q-icon name="point_of_sale" color="cyan-6" />
          </q-item-section>
          <q-item-section>
            <q-item-label class="text-weight-medium">Ventas</q-item-label>
          </q-item-section>
        </q-item>

        <!-- Botón Cerrar Sesión -->
        <q-separator spaced class="bg-purple-2" />
        <q-item 
          clickable 
          @click="logout"
          class="logout-item q-mt-md"
        >
          <q-item-section avatar>
            <q-icon name="logout" color="red-6" />
          </q-item-section>
          <q-item-section>
            <q-item-label class="text-weight-medium text-red-7">
              Cerrar Sesión
            </q-item-label>
          </q-item-section>
        </q-item>
      </q-list>
    </q-drawer>

    <!-- Contenedor principal -->
    <q-page-container class="bg-main">
      <router-view />
    </q-page-container>
  </q-layout>
</template>

<script setup>
import { ref } from 'vue'
import Swal from 'sweetalert2'
import { useRouter } from 'vue-router'
import { api } from 'boot/axios' 

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
    iconColor: '#9333ea',
    showCancelButton: true,
    confirmButtonColor: '#7c3aed',
    cancelButtonColor: '#06b6d4',
    confirmButtonText: 'Sí, salir',
    cancelButtonText: 'Cancelar'
  })

  if (result.isConfirmed) {
    try {
      await api.post('/logout') 
    } catch (error) {
      console.error('Error en logout:', error.response?.data || error.message)
    } finally {
      localStorage.removeItem('token')
      router.push('/login')
      Swal.fire({
        title: 'Sesión cerrada',
        text: 'Has salido correctamente',
        icon: 'success',
        iconColor: '#06b6d4',
        confirmButtonColor: '#7c3aed'
      })
    }
  }
}
</script>

<style scoped>
/* Gradiente para el header */
.bg-gradient-header {
  background: linear-gradient(135deg, #7c3aed 0%, #06b6d4 100%);
  box-shadow: 0 4px 12px rgba(124, 58, 237, 0.3);
}

/* Gradiente para el drawer superior */
.bg-gradient-drawer {
  background: linear-gradient(180deg, #7c3aed 0%, #a855f7 100%);
  border-bottom: 3px solid #06b6d4;
}

/* Fondo del drawer */
.bg-drawer {
  background: linear-gradient(to bottom, #faf5ff 0%, #f0f9ff 100%);
}

/* Fondo del contenedor principal */
.bg-main {
  background: linear-gradient(to bottom right, #faf5ff 0%, #ecfeff 100%);
}

/* Estilo de items del menú */
.menu-item {
  margin: 4px 12px;
  border-radius: 12px;
  transition: all 0.3s ease;
}

.menu-item:hover {
  background: linear-gradient(90deg, rgba(124, 58, 237, 0.1) 0%, rgba(6, 182, 212, 0.1) 100%);
  transform: translateX(4px);
}

.active-menu-item {
  background: linear-gradient(90deg, rgba(124, 58, 237, 0.15) 0%, rgba(6, 182, 212, 0.15) 100%);
  border-left: 4px solid #7c3aed;
  font-weight: 600;
}

/* Estilo para cerrar sesión */
.logout-item {
  margin: 4px 12px;
  border-radius: 12px;
  border: 1px solid rgba(239, 68, 68, 0.2);
  transition: all 0.3s ease;
}

.logout-item:hover {
  background: rgba(239, 68, 68, 0.05);
  transform: translateX(4px);
}

/* Sombras suaves */
.shadow-lg {
  box-shadow: 0 10px 25px rgba(124, 58, 237, 0.3);
}
</style>