<template>
  <q-page class="dashboard-page">
    <div class="q-pa-md q-gutter-md">
      <!-- Header con bienvenida -->
      <div class="welcome-card">
        <q-card class="gradient-card">
          <q-card-section class="flex items-center justify-between">
            <div>
              <div class="text-h4 text-white font-bold">
                ¡Bienvenido! 👋
              </div>
              <div class="text-subtitle1 text-purple-1">
                Sistema de Gestión Farmacéutica
              </div>
            </div>
            <q-icon name="local_pharmacy" size="80px" color="white" class="pharmacy-icon" />
          </q-card-section>
        </q-card>
      </div>

      <!-- Tarjetas de estadísticas rápidas -->
      <div class="row q-col-gutter-md">
        <!-- Total Productos -->
        <div class="col-xs-12 col-sm-6 col-md-3">
          <q-card class="stat-card stat-card-purple">
            <q-card-section>
              <div class="flex items-center justify-between">
                <div>
                  <div class="text-h6 text-white">Productos</div>
                  <div class="text-h4 text-white font-bold q-mt-sm">245</div>
                  <div class="text-caption text-purple-1">En inventario</div>
                </div>
                <q-icon name="inventory_2" size="50px" color="white" class="stat-icon" />
              </div>
            </q-card-section>
          </q-card>
        </div>

        <!-- Total Clientes -->
        <div class="col-xs-12 col-sm-6 col-md-3">
          <q-card class="stat-card stat-card-cyan">
            <q-card-section>
              <div class="flex items-center justify-between">
                <div>
                  <div class="text-h6 text-white">Clientes</div>
                  <div class="text-h4 text-white font-bold q-mt-sm">1,234</div>
                  <div class="text-caption text-cyan-1">Registrados</div>
                </div>
                <q-icon name="groups" size="50px" color="white" class="stat-icon" />
              </div>
            </q-card-section>
          </q-card>
        </div>

        <!-- Ventas del Día -->
        <div class="col-xs-12 col-sm-6 col-md-3">
          <q-card class="stat-card stat-card-purple">
            <q-card-section>
              <div class="flex items-center justify-between">
                <div>
                  <div class="text-h6 text-white">Ventas Hoy</div>
                  <div class="text-h4 text-white font-bold q-mt-sm">S/ 3,420</div>
                  <div class="text-caption text-purple-1">+12% vs ayer</div>
                </div>
                <q-icon name="point_of_sale" size="50px" color="white" class="stat-icon" />
              </div>
            </q-card-section>
          </q-card>
        </div>

        <!-- Productos Bajos -->
        <div class="col-xs-12 col-sm-6 col-md-3">
          <q-card class="stat-card stat-card-warning">
            <q-card-section>
              <div class="flex items-center justify-between">
                <div>
                  <div class="text-h6 text-white">Stock Bajo</div>
                  <div class="text-h4 text-white font-bold q-mt-sm">12</div>
                  <div class="text-caption text-orange-1">Requieren atención</div>
                </div>
                <q-icon name="warning" size="50px" color="white" class="stat-icon" />
              </div>
            </q-card-section>
          </q-card>
        </div>
      </div>

      <!-- Tarjetas de acceso rápido -->
      <div class="text-h5 text-grey-8 q-mt-lg q-mb-md font-bold">
        Acceso Rápido
      </div>

      <div class="row q-col-gutter-md">
        <!-- Productos -->
        <div class="col-xs-12 col-sm-6 col-md-4">
          <q-card class="action-card" clickable @click="goTo('/products')">
            <q-card-section class="text-center">
              <div class="action-icon-wrapper bg-purple">
                <img 
                  src="~assets/products-icon.png" 
                  alt="Productos"
                  class="action-image"
                  @error="handleImageError"
                />
              </div>
              <div class="text-h6 q-mt-md text-purple-8 font-bold">
                Gestión de Productos
              </div>
              <div class="text-body2 text-grey-7 q-mt-sm">
                Administra tu inventario de medicamentos y productos
              </div>
            </q-card-section>
            <q-card-section class="q-pt-none text-center">
              <q-btn 
                flat 
                color="purple-7" 
                label="Ir a Productos" 
                icon-right="arrow_forward"
              />
            </q-card-section>
          </q-card>
        </div>

        <!-- Clientes -->
        <div class="col-xs-12 col-sm-6 col-md-4">
          <q-card class="action-card" clickable @click="goTo('/clients')">
            <q-card-section class="text-center">
              <div class="action-icon-wrapper bg-cyan">
                <img 
                  src="~assets/clients-icon.png" 
                  alt="Clientes"
                  class="action-image"
                  @error="handleImageError"
                />
              </div>
              <div class="text-h6 q-mt-md text-cyan-8 font-bold">
                Gestión de Clientes
              </div>
              <div class="text-body2 text-grey-7 q-mt-sm">
                Administra la información de tus clientes
              </div>
            </q-card-section>
            <q-card-section class="q-pt-none text-center">
              <q-btn 
                flat 
                color="cyan-7" 
                label="Ir a Clientes" 
                icon-right="arrow_forward"
              />
            </q-card-section>
          </q-card>
        </div>

        <!-- Ventas -->
        <div class="col-xs-12 col-sm-6 col-md-4">
          <q-card class="action-card" clickable @click="goTo('/sales')">
            <q-card-section class="text-center">
              <div class="action-icon-wrapper bg-purple-gradient">
                <img 
                  src="~assets/sales-icon.png" 
                  alt="Ventas"
                  class="action-image"
                  @error="handleImageError"
                />
              </div>
              <div class="text-h6 q-mt-md text-purple-8 font-bold">
                Registro de Ventas
              </div>
              <div class="text-body2 text-grey-7 q-mt-sm">
                Realiza y consulta las ventas de la farmacia
              </div>
            </q-card-section>
            <q-card-section class="q-pt-none text-center">
              <q-btn 
                flat 
                color="purple-7" 
                label="Ir a Ventas" 
                icon-right="arrow_forward"
              />
            </q-card-section>
          </q-card>
        </div>
      </div>

      <!-- Sección de información adicional -->
      <div class="row q-col-gutter-md q-mt-md">
        <div class="col-xs-12 col-md-6">
          <q-card class="info-card">
            <q-card-section>
              <div class="text-h6 text-purple-8 font-bold flex items-center">
                <q-icon name="trending_up" size="28px" class="q-mr-sm" />
                Productos Más Vendidos
              </div>
            </q-card-section>
            <q-separator />
            <q-card-section>
              <q-list>
                <q-item>
                  <q-item-section avatar>
                    <q-avatar color="purple-2" text-color="purple-8">1</q-avatar>
                  </q-item-section>
                  <q-item-section>
                    <q-item-label>Paracetamol 500mg</q-item-label>
                    <q-item-label caption>145 unidades vendidas</q-item-label>
                  </q-item-section>
                </q-item>
                <q-item>
                  <q-item-section avatar>
                    <q-avatar color="cyan-2" text-color="cyan-8">2</q-avatar>
                  </q-item-section>
                  <q-item-section>
                    <q-item-label>Ibuprofeno 400mg</q-item-label>
                    <q-item-label caption>132 unidades vendidas</q-item-label>
                  </q-item-section>
                </q-item>
                <q-item>
                  <q-item-section avatar>
                    <q-avatar color="purple-2" text-color="purple-8">3</q-avatar>
                  </q-item-section>
                  <q-item-section>
                    <q-item-label>Amoxicilina 500mg</q-item-label>
                    <q-item-label caption>98 unidades vendidas</q-item-label>
                  </q-item-section>
                </q-item>
              </q-list>
            </q-card-section>
          </q-card>
        </div>

        <div class="col-xs-12 col-md-6">
          <q-card class="info-card">
            <q-card-section>
              <div class="text-h6 text-cyan-8 font-bold flex items-center">
                <q-icon name="notifications_active" size="28px" class="q-mr-sm" />
                Alertas y Notificaciones
              </div>
            </q-card-section>
            <q-separator />
            <q-card-section>
              <q-list>
                <q-item>
                  <q-item-section avatar>
                    <q-icon name="inventory" color="orange" size="28px" />
                  </q-item-section>
                  <q-item-section>
                    <q-item-label>Stock bajo en 12 productos</q-item-label>
                    <q-item-label caption>Revisa tu inventario</q-item-label>
                  </q-item-section>
                </q-item>
                <q-item>
                  <q-item-section avatar>
                    <q-icon name="event" color="purple" size="28px" />
                  </q-item-section>
                  <q-item-section>
                    <q-item-label>5 productos próximos a vencer</q-item-label>
                    <q-item-label caption>En los próximos 30 días</q-item-label>
                  </q-item-section>
                </q-item>
                <q-item>
                  <q-item-section avatar>
                    <q-icon name="check_circle" color="green" size="28px" />
                  </q-item-section>
                  <q-item-section>
                    <q-item-label>Sistema actualizado</q-item-label>
                    <q-item-label caption>Última actualización: Hoy</q-item-label>
                  </q-item-section>
                </q-item>
              </q-list>
            </q-card-section>
          </q-card>
        </div>
      </div>
    </div>
  </q-page>
</template>

<script setup>
import { useRouter } from 'vue-router'

const router = useRouter()

function goTo(path) {
  router.push(path)
}

function handleImageError(event) {
  // Si la imagen no carga, muestra un ícono por defecto
  event.target.style.display = 'none'
}
</script>

<style scoped>
.dashboard-page {
  background: linear-gradient(to bottom right, #faf5ff 0%, #ecfeff 100%);
  min-height: 100vh;
}

/* Tarjeta de bienvenida */
.welcome-card {
  margin-bottom: 20px;
}

.gradient-card {
  background: linear-gradient(135deg, #7c3aed 0%, #06b6d4 100%);
  border-radius: 16px;
  box-shadow: 0 8px 24px rgba(124, 58, 237, 0.3);
}

.pharmacy-icon {
  animation: pulse 2s ease-in-out infinite;
}

@keyframes pulse {
  0%, 100% {
    transform: scale(1);
    opacity: 1;
  }
  50% {
    transform: scale(1.05);
    opacity: 0.8;
  }
}

/* Tarjetas de estadísticas */
.stat-card {
  border-radius: 16px;
  transition: transform 0.3s ease, box-shadow 0.3s ease;
  overflow: hidden;
}

.stat-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 12px 28px rgba(0, 0, 0, 0.15);
}

.stat-card-purple {
  background: linear-gradient(135deg, #7c3aed 0%, #a855f7 100%);
}

.stat-card-cyan {
  background: linear-gradient(135deg, #06b6d4 0%, #22d3ee 100%);
}

.stat-card-warning {
  background: linear-gradient(135deg, #f97316 0%, #fb923c 100%);
}

.stat-icon {
  opacity: 0.9;
}

/* Tarjetas de acción */
.action-card {
  border-radius: 16px;
  transition: all 0.3s ease;
  border: 2px solid transparent;
  background: white;
}

.action-card:hover {
  transform: translateY(-8px);
  box-shadow: 0 12px 32px rgba(124, 58, 237, 0.2);
  border-color: #7c3aed;
}

.action-icon-wrapper {
  width: 120px;
  height: 120px;
  margin: 0 auto;
  border-radius: 20px;
  display: flex;
  align-items: center;
  justify-content: center;
  position: relative;
  overflow: hidden;
}

.action-icon-wrapper.bg-purple {
  background: linear-gradient(135deg, #7c3aed 0%, #a855f7 100%);
}

.action-icon-wrapper.bg-cyan {
  background: linear-gradient(135deg, #06b6d4 0%, #22d3ee 100%);
}

.action-icon-wrapper.bg-purple-gradient {
  background: linear-gradient(135deg, #9333ea 0%, #7c3aed 100%);
}

.action-image {
  width: 80px;
  height: 80px;
  object-fit: contain;
  filter: brightness(0) invert(1);
}

/* Tarjetas de información */
.info-card {
  border-radius: 16px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
  background: white;
  height: 100%;
}

/* Animaciones adicionales */
@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.q-card {
  animation: fadeIn 0.5s ease-out;
}
</style>