<template>
  <div class="q-pa-md sales-container">
    <!-- Header Moderno -->
    <div class="header-section q-mb-lg">
      <div class="row items-center justify-between">
        <div class="col-auto">
          <div class="text-h4 text-weight-bold text-primary q-mb-xs">
            <q-icon name="shopping_cart" size="40px" class="q-mr-sm" />
            Gestión de Ventas
          </div>
          <div class="text-subtitle1 text-grey-7">
            Sistema de registro y control de ventas farmacéuticas
          </div>
        </div>
        <div class="col-auto">
          <q-btn 
            label="Nueva Venta" 
            color="primary" 
            icon="add_shopping_cart" 
            @click="openCreateDialog" 
            unelevated
            size="lg"
            class="shadow-3 btn-nueva-venta"
            no-caps
          />
        </div>
      </div>
    </div>

    <!-- Estadísticas Rápidas -->
    <div class="row q-col-gutter-md q-mb-lg">
      <div class="col-12 col-md-4">
        <q-card class="stats-card shadow-4" flat bordered>
          <q-card-section class="row items-center">
            <q-icon name="receipt_long" size="48px" color="primary" class="q-mr-md" />
            <div>
              <div class="text-h5 text-weight-bold">{{ sales.length }}</div>
              <div class="text-grey-7">Total Ventas</div>
            </div>
          </q-card-section>
        </q-card>
      </div>
      <div class="col-12 col-md-4">
        <q-card class="stats-card shadow-4" flat bordered>
          <q-card-section class="row items-center">
            <q-icon name="payments" size="48px" color="positive" class="q-mr-md" />
            <div>
              <div class="text-h5 text-weight-bold">S/ {{ totalRevenue.toFixed(2) }}</div>
              <div class="text-grey-7">Ingresos Totales</div>
            </div>
          </q-card-section>
        </q-card>
      </div>
      <div class="col-12 col-md-4">
        <q-card class="stats-card shadow-4" flat bordered>
          <q-card-section class="row items-center">
            <q-icon name="trending_up" size="48px" color="secondary" class="q-mr-md" />
            <div>
              <div class="text-h5 text-weight-bold">S/ {{ averageSale.toFixed(2) }}</div>
              <div class="text-grey-7">Venta Promedio</div>
            </div>
          </q-card-section>
        </q-card>
      </div>
    </div>

    <!-- Tabla de ventas Mejorada -->
    <q-card flat bordered class="shadow-4 table-card">
      <q-card-section class="bg-grey-2 q-py-md">
        <div class="text-h6 text-weight-bold">
          <q-icon name="list_alt" class="q-mr-sm" />
          Registro de Ventas
        </div>
      </q-card-section>
      
      <q-table
        :rows="sales"
        :columns="columns"
        row-key="id"
        flat
        :rows-per-page-options="[10, 25, 50]"
        separator="cell"
        no-data-label="No hay ventas registradas"
        class="modern-table"
      >
        <template v-slot:body-cell-id="props">
          <q-td :props="props">
            <q-chip dense color="primary" text-color="white" size="sm">
              #{{ props.value }}
            </q-chip>
          </q-td>
        </template>

        <template v-slot:body-cell-client="props">
          <q-td :props="props">
            <div class="row items-center">
              <q-avatar size="32px" color="blue-2" text-color="primary" class="q-mr-sm">
                <q-icon name="person" />
              </q-avatar>
              <span class="text-weight-medium">{{ props.value }}</span>
            </div>
          </q-td>
        </template>

        <template v-slot:body-cell-items_count="props">
          <q-td :props="props">
            <q-badge color="secondary" :label="props.value + ' producto(s)'" />
          </q-td>
        </template>

        <template v-slot:body-cell-total="props">
          <q-td :props="props">
            <div class="text-h6 text-weight-bold text-positive">
              {{ props.value }}
            </div>
          </q-td>
        </template>

        <template v-slot:body-cell-date="props">
          <q-td :props="props">
            <div class="text-grey-8">
              <q-icon name="event" size="xs" class="q-mr-xs" />
              {{ props.value }}
            </div>
          </q-td>
        </template>

        <template v-slot:body-cell-actions="props">
          <q-td align="center" :props="props">
            <q-btn
              flat round dense
              icon="visibility"
              color="info"
              size="sm"
              @click="viewSale(props.row)"
              class="q-mr-xs"
            >
              <q-tooltip>Ver Detalle</q-tooltip>
            </q-btn>
            <q-btn
              flat round dense
              icon="print"
              color="secondary"
              size="sm"
              @click="printSale(props.row)"
              class="q-mr-xs"
            >
              <q-tooltip>Imprimir</q-tooltip>
            </q-btn>
            <q-btn
              flat round dense
              icon="delete"
              color="negative"
              size="sm"
              @click="confirmDelete(props.row.id)"
            >
              <q-tooltip>Eliminar</q-tooltip>
            </q-btn>
          </q-td>
        </template>
      </q-table>
    </q-card>

    <!-- Modal Crear Venta Mejorado -->
    <q-dialog v-model="dialog" persistent transition-show="slide-up" transition-hide="slide-down">
      <q-card class="sale-dialog" style="min-width: 700px; max-width: 900px;">
        <!-- Header del Modal -->
        <q-card-section class="bg-gradient-primary text-white q-pa-md">
          <div class="row items-center">
            <q-icon name="add_shopping_cart" size="36px" class="q-mr-sm" />
            <div>
              <div class="text-h5 text-weight-bold">Nueva Venta</div>
              <div class="text-caption">Registra una nueva venta de medicamentos</div>
            </div>
          </div>
        </q-card-section>

        <q-card-section class="q-pa-lg">
            <!-- Selección de Cliente con mejor diseño -->
            <div class="q-mb-lg">
            <div class="text-subtitle1 text-weight-medium q-mb-sm">
                <q-icon name="person" class="q-mr-xs" />
                Información del Cliente
            </div>
                <q-select
                    v-model="form.client_id"
                    :options="clients"
                    option-label="name"
                    option-value="id"
                    emit-value
                    map-options
                    label="Seleccione el cliente"
                    outlined
                    />

          </div>

          <!-- Lista de productos mejorada -->
          <div>
            <div class="row items-center justify-between q-mb-md">
              <div class="text-subtitle1 text-weight-medium">
                <q-icon name="medication" class="q-mr-xs" />
                Productos a Vender
              </div>
              <q-btn
                label="Agregar Producto"
                color="secondary"
                icon="add"
                size="sm"
                unelevated
                @click="addItem"
                no-caps
              />
            </div>

            <q-card flat bordered v-if="form.items.length > 0" class="q-mb-md">
              <q-list separator>
                <q-item v-for="(item, index) in form.items" :key="index" class="q-py-md">
                  <q-item-section>
                    <div class="row q-col-gutter-md">
                      <div class="col-12 col-md-6">
                        <q-select
                        v-model="item.product_id"
                        :options="products"
                        option-label="name"
                        option-value="id"
                        emit-value
                        map-options
                        label="Medicamento"
                        outlined
                        dense
                        />

                      </div>
                      <div class="col-12 col-md-4">
                        <q-input
                          v-model.number="item.quantity"
                          type="number"
                          label="Cantidad"
                          outlined
                          dense
                          min="1"
                          :rules="[val => val > 0 || 'Cantidad debe ser mayor a 0']"
                        >
                          <template v-slot:prepend>
                            <q-icon name="inventory" color="secondary" />
                          </template>
                        </q-input>
                      </div>
                      <div class="col-12 col-md-2 flex items-center">
                        <q-btn
                          dense
                          flat
                          round
                          icon="delete_outline"
                          color="negative"
                          @click="removeItem(index)"
                          size="md"
                        >
                          <q-tooltip>Eliminar</q-tooltip>
                        </q-btn>
                      </div>
                    </div>
                  </q-item-section>
                </q-item>
              </q-list>
            </q-card>

            <q-banner v-else rounded class="bg-grey-2 text-grey-7">
              <template v-slot:avatar>
                <q-icon name="info" color="info" />
              </template>
              Agregue productos para comenzar la venta
            </q-banner>
          </div>

          <!-- Resumen del total -->
          <q-card v-if="form.items.length > 0" flat bordered class="bg-blue-1 q-mt-md">
            <q-card-section class="row items-center justify-between">
              <div class="text-h6 text-weight-medium">Total Estimado:</div>
              <div class="text-h5 text-weight-bold text-primary">
                S/ {{ calculateTotal().toFixed(2) }}
              </div>
            </q-card-section>
          </q-card>
        </q-card-section>

        <q-separator />

        <q-card-actions align="right" class="q-pa-md">
          <q-btn
            flat
            label="Cancelar"
            color="grey-7"
            v-close-popup
            no-caps
            size="md"
          />
          <q-btn
            label="Registrar Venta"
            color="positive"
            unelevated
            @click="saveSale"
            icon-right="check_circle"
            no-caps
            size="md"
            :disable="!isFormValid"
            class="q-px-lg"
          />
        </q-card-actions>
      </q-card>
    </q-dialog>

    <!-- Modal Ver Detalle Mejorado -->
    <q-dialog v-model="detailDialog" transition-show="fade" transition-hide="fade">
      <q-card style="min-width: 500px; max-width: 700px;" class="detail-card">
        <q-card-section class="bg-gradient-info text-white q-pa-md">
          <div class="text-h5 text-weight-bold">
            <q-icon name="receipt" size="32px" class="q-mr-sm" />
            Detalle de Venta #{{ selectedSale?.id }}
          </div>
        </q-card-section>

        <q-card-section class="q-pa-lg">
          <!-- Información del Cliente -->
          <q-card flat bordered class="q-mb-md bg-blue-1">
            <q-card-section>
              <div class="row items-center">
                <q-avatar size="48px" color="primary" text-color="white" class="q-mr-md">
                  <q-icon name="person" size="28px" />
                </q-avatar>
                <div>
                  <div class="text-caption text-grey-7">Cliente</div>
                  <div class="text-h6 text-weight-bold">{{ selectedSale?.client?.name }}</div>
                </div>
              </div>
            </q-card-section>
          </q-card>

          <!-- Lista de Productos -->
          <div class="text-subtitle1 text-weight-medium q-mb-md">
            <q-icon name="list" class="q-mr-xs" />
            Productos Vendidos
          </div>
          
          <q-list bordered separator>
            <q-item v-for="item in selectedSale?.items" :key="item.id" class="q-py-md">
              <q-item-section avatar>
                <q-avatar color="secondary" text-color="white" size="40px">
                  <q-icon name="medication" />
                </q-avatar>
              </q-item-section>
              <q-item-section>
                <q-item-label class="text-weight-medium">{{ item.product.name }}</q-item-label>
                <q-item-label caption>Cantidad: {{ item.quantity }} unidad(es)</q-item-label>
              </q-item-section>
              <q-item-section side>
                <q-item-label class="text-h6 text-positive text-weight-bold">
                  S/ {{ item.subtotal }}
                </q-item-label>
              </q-item-section>
            </q-item>
          </q-list>

          <!-- Total -->
          <q-separator class="q-my-md" />
          <div class="row items-center justify-between bg-positive text-white q-pa-md rounded-borders">
            <div class="text-h6 text-weight-bold">TOTAL</div>
            <div class="text-h4 text-weight-bold">S/ {{ selectedSale?.total }}</div>
          </div>
        </q-card-section>

        <q-card-actions align="right" class="q-pa-md">
          <q-btn
            flat
            label="Cerrar"
            color="grey-7"
            v-close-popup
            no-caps
            size="md"
          />
          <q-btn
            label="Imprimir"
            color="primary"
            icon="print"
            unelevated
            @click="printSale(selectedSale)"
            no-caps
            size="md"
          />
        </q-card-actions>
      </q-card>
    </q-dialog>

  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { api } from 'boot/axios'
import Swal from 'sweetalert2'

const sales = ref([])
const clients = ref([])
const products = ref([])

const dialog = ref(false)
const detailDialog = ref(false)
const form = ref({ client_id: null, items: [] })
const selectedSale = ref(null)

// Estadísticas calculadas
const totalRevenue = computed(() => {
  return sales.value.reduce((sum, sale) => sum + parseFloat(sale.total || 0), 0)
})

const averageSale = computed(() => {
  return sales.value.length > 0 ? totalRevenue.value / sales.value.length : 0
})

const isFormValid = computed(() => {
  return form.value.client_id && 
         form.value.items.length > 0 && 
         form.value.items.every(item => item.product_id && item.quantity > 0)
})

const columns = [
  { name: 'id', label: 'ID', field: 'id', align: 'left', sortable: true },
  { name: 'client', label: 'Cliente', field: row => row.client?.name, align: 'left', sortable: true },
  { name: 'items_count', label: 'Productos', field: row => row.items?.length || 0, align: 'center' },
  { name: 'total', label: 'Total', field: 'total', align: 'right', format: val => `S/ ${parseFloat(val).toFixed(2)}`, sortable: true },
  { name: 'date', label: 'Fecha', field: 'created_at', align: 'left', format: val => new Date(val).toLocaleDateString() },
  { name: 'actions', label: 'Acciones', align: 'center' }
]

// Cargar ventas
const fetchSales = async () => {
  try {
    const { data } = await api.get('/sales')
    sales.value = data
  } catch (error) {
    console.error(error)
    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: 'No se pudieron cargar las ventas.',
      confirmButtonColor: '#1976d2'
    })
  }
}

// Cargar clientes y productos
const fetchClients = async () => {
  try {
    const { data } = await api.get('/clients')
    clients.value = data
  } catch (error) {
    console.error(error)
  }
}

const fetchProducts = async () => {
  try {
    const { data } = await api.get('/products')
    products.value = data
  } catch (error) {
    console.error(error)
  }
}

// Abrir modal de creación
const openCreateDialog = () => {
  form.value = { client_id: null, items: [] }
  dialog.value = true
}

// Calcular total estimado
const calculateTotal = () => {
  return form.value.items.reduce((sum, item) => {
    const product = products.value.find(p => p.id === item.product_id)
    return sum + (product ? product.price * item.quantity : 0)
  }, 0)
}

// Guardar venta
const saveSale = async () => {
  if (!isFormValid.value) {
    Swal.fire({
      icon: 'warning',
      title: 'Datos incompletos',
      text: 'Complete todos los campos requeridos.',
      confirmButtonColor: '#1976d2'
    })
    return
  }

  try {
    await api.post('/sales', form.value)
    Swal.fire({
      icon: 'success',
      title: '¡Éxito!',
      text: 'Venta registrada correctamente.',
      confirmButtonColor: '#21ba45'
    })
    dialog.value = false
    fetchSales()
  } catch (error) {
    console.error(error)
    Swal.fire({
      icon: 'error',
      title: 'Error',
      text: error.response?.data?.message || 'No se pudo registrar la venta.',
      confirmButtonColor: '#c10015'
    })
  }
}

// Eliminar venta
const confirmDelete = async (id) => {
  const result = await Swal.fire({
    title: '¿Eliminar venta?',
    text: "Esta acción restaurará el stock de los productos vendidos.",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#c10015',
    cancelButtonColor: '#6c757d',
    confirmButtonText: 'Sí, eliminar',
    cancelButtonText: 'Cancelar'
  })

  if (result.isConfirmed) {
    try {
      await api.delete(`/sales/${id}`)
      Swal.fire({
        icon: 'success',
        title: '¡Eliminada!',
        text: 'La venta fue eliminada y el stock restaurado.',
        confirmButtonColor: '#21ba45'
      })
      fetchSales()
    } catch (error) {
      console.error(error)
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'No se pudo eliminar la venta.',
        confirmButtonColor: '#c10015'
      })
    }
  }
}

// Ver detalle
const viewSale = (sale) => {
  selectedSale.value = sale
  detailDialog.value = true
}

// 🔹 Imprimir venta -> descarga PDF
const printSale = async (sale) => {
  try {
    const res = await api.get(`/sales/${sale.id}/print`, {
      responseType: 'blob'
    })

    const url = window.URL.createObjectURL(new Blob([res.data]))
    const link = document.createElement('a')
    link.href = url
    link.setAttribute('download', `venta_${sale.id}.pdf`)
    document.body.appendChild(link)
    link.click()
  } catch (error) {
    console.error("Error al imprimir:", error)
    Swal.fire('Error', 'No se pudo generar la impresión', 'error')
  }
}

// Items
const addItem = () => {
  form.value.items.push({ product_id: null, quantity: 1 })
}

const removeItem = (index) => {
  form.value.items.splice(index, 1)
}

onMounted(() => {
  fetchSales()
  fetchClients()
  fetchProducts()
})
</script>


<style scoped>
.sales-container {
  max-width: 1400px;
  margin: 0 auto;
}

.header-section {
  background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
  padding: 2rem;
  border-radius: 16px;
  box-shadow: 0 4px 20px rgba(0,0,0,0.08);
}

.btn-nueva-venta {
  transition: all 0.3s ease;
}

.btn-nueva-venta:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 24px rgba(25, 118, 210, 0.4);
}

.stats-card {
  border-radius: 12px;
  transition: all 0.3s ease;
  border-left: 4px solid #1976d2;
  background: white;
}

.stats-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 8px 24px rgba(0,0,0,0.15) !important;
}

.table-card {
  border-radius: 12px;
  overflow: hidden;
  animation: fadeInUp 0.6s ease;
}

.modern-table {
  font-family: 'Roboto', sans-serif;
}

.modern-table :deep(thead tr) {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
}

.modern-table :deep(thead th) {
  font-weight: 600;
  color: white !important;
  font-size: 0.9rem;
}

.modern-table :deep(tbody tr) {
  transition: all 0.2s ease;
}

.modern-table :deep(tbody tr:hover) {
  background-color: #f5f9ff;
  transform: scale(1.01);
}

.sale-dialog {
  border-radius: 16px;
  overflow: hidden;
  animation: slideInUp 0.4s ease;
}

.detail-card {
  border-radius: 16px;
  overflow: hidden;
}

.bg-gradient-primary {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.bg-gradient-info {
  background: linear-gradient(135deg, #00b4db 0%, #0083b0 100%);
}

.modern-input :deep(.q-field__control) {
  border-radius: 8px;
}

.rounded-borders {
  border-radius: 8px;
}

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

@keyframes slideInUp {
  from {
    opacity: 0;
    transform: translateY(40px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Responsive */
@media (max-width: 600px) {
  .header-section {
    padding: 1rem;
  }
  
  .text-h4 {
    font-size: 1.5rem !important;
  }
}
</style>