<template>
  <div class="q-pa-md products-container">
    <!-- Header Moderno -->
    <div class="header-section q-mb-lg">
      <div class="row items-center justify-between">
        <div class="col-auto">
          <div class="text-h4 text-weight-bold text-primary q-mb-xs">
            <q-icon name="inventory_2" size="40px" class="q-mr-sm" />
            Gestión de Productos
          </div>
          <div class="text-subtitle1 text-grey-7">
            Control y administración del inventario de la botica
          </div>
        </div>
        <div class="col-auto">
          <q-btn 
            label="Nuevo Producto" 
            color="primary" 
            icon="add"
            @click="openCreateDialog" 
            unelevated
            size="lg"
            class="shadow-3 btn-nuevo"
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
            <q-icon name="category" size="48px" color="primary" class="q-mr-md" />
            <div>
              <div class="text-h5 text-weight-bold">{{ products.length }}</div>
              <div class="text-grey-7">Productos Registrados</div>
            </div>
          </q-card-section>
        </q-card>
      </div>
      <div class="col-12 col-md-4">
        <q-card class="stats-card shadow-4" flat bordered>
          <q-card-section class="row items-center">
            <q-icon name="inventory" size="48px" color="secondary" class="q-mr-md" />
            <div>
              <div class="text-h5 text-weight-bold">{{ totalStock }}</div>
              <div class="text-grey-7">Unidades en Stock</div>
            </div>
          </q-card-section>
        </q-card>
      </div>
      <div class="col-12 col-md-4">
        <q-card class="stats-card shadow-4" flat bordered>
          <q-card-section class="row items-center">
            <q-icon name="savings" size="48px" color="positive" class="q-mr-md" />
            <div>
              <div class="text-h5 text-weight-bold">S/ {{ totalValue.toFixed(2) }}</div>
              <div class="text-grey-7">Valor Total Inventario</div>
            </div>
          </q-card-section>
        </q-card>
      </div>
    </div>

    <!-- Tabla de productos -->
    <q-card flat bordered class="shadow-4 table-card">
      <q-card-section class="bg-grey-2 q-py-md">
        <div class="text-h6 text-weight-bold">
          <q-icon name="list_alt" class="q-mr-sm" />
          Lista de Productos
        </div>
      </q-card-section>

      <q-table
        :rows="products"
        :columns="columns"
        row-key="id"
        flat
        bordered
        :rows-per-page-options="[10,25,50]"
        separator="cell"
        no-data-label="No hay productos registrados"
        class="modern-table"
      >
        <template v-slot:body-cell-id="props">
          <q-td :props="props">
            <q-chip dense color="primary" text-color="white" size="sm">
              #{{ props.value }}
            </q-chip>
          </q-td>
        </template>

        <template v-slot:body-cell-price="props">
          <q-td :props="props">
            <div class="text-weight-bold text-positive">S/ {{ props.value }}</div>
          </q-td>
        </template>

        <template v-slot:body-cell-stock="props">
          <q-td :props="props">
            <q-badge 
              :color="props.value > 20 ? 'positive' : props.value > 0 ? 'warning' : 'negative'" 
              :label="props.value + ' und.'" 
            />
          </q-td>
        </template>

        <template v-slot:body-cell-actions="props">
          <q-td align="center">
            <q-btn flat round dense icon="edit" color="secondary" @click="openEditDialog(props.row)">
              <q-tooltip>Editar</q-tooltip>
            </q-btn>
            <q-btn flat round dense icon="delete" color="negative" @click="confirmDelete(props.row.id)">
              <q-tooltip>Eliminar</q-tooltip>
            </q-btn>
          </q-td>
        </template>
      </q-table>
    </q-card>

    <!-- Modal Crear/Editar -->
    <q-dialog v-model="dialog" transition-show="slide-up" transition-hide="slide-down" persistent>
      <q-card class="product-dialog" style="min-width: 600px; max-width: 900px;">
        <!-- Header del modal -->
        <q-card-section class="bg-gradient-primary text-white q-pa-md">
          <div class="row items-center">
            <q-icon :name="isEdit ? 'edit' : 'add_circle'" size="32px" class="q-mr-sm" />
            <div class="text-h5 text-weight-bold">
              {{ isEdit ? '✏️ Editar Producto' : '🆕 Nuevo Producto' }}
            </div>
          </div>
        </q-card-section>

        <!-- Formulario -->
        <q-card-section class="q-pa-lg">
          <div class="row q-col-gutter-md">
            <div class="col-12 col-md-6">
              <q-input v-model="form.name" label="Nombre" outlined dense autofocus>
                <template v-slot:prepend><q-icon name="label" /></template>
              </q-input>
            </div>
            <div class="col-12 col-md-6">
              <q-input v-model.number="form.price" label="Precio (S/)" type="number" outlined dense>
                <template v-slot:prepend><q-icon name="attach_money" /></template>
              </q-input>
            </div>
            <div class="col-12 col-md-6">
              <q-input v-model.number="form.stock" label="Stock" type="number" outlined dense>
                <template v-slot:prepend><q-icon name="inventory_2" /></template>
              </q-input>
            </div>
            <div class="col-12">
              <q-input v-model="form.description" type="textarea" label="Descripción" outlined dense autogrow>
                <template v-slot:prepend><q-icon name="description" /></template>
              </q-input>
            </div>
          </div>
        </q-card-section>

        <!-- Acciones -->
        <q-separator />
        <q-card-actions align="right" class="q-pa-md">
          <q-btn flat label="Cancelar" color="grey-7" v-close-popup no-caps />
          <q-btn 
            :label="isEdit ? 'Actualizar' : 'Guardar'" 
            color="positive" 
            unelevated 
            @click="saveProduct" 
            icon-right="check_circle"
            no-caps
            class="q-px-md"
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

const products = ref([])
const dialog = ref(false)
const isEdit = ref(false)
const form = ref({ id: null, name: '', description: '', price: 0, stock: 0 })

// Columnas
const columns = [
  { name: 'id', label: 'ID', field: 'id', align: 'left', sortable: true },
  { name: 'name', label: 'Nombre', field: 'name', align: 'left', sortable: true },
  { name: 'price', label: 'Precio', field: 'price', align: 'left', sortable: true },
  { name: 'stock', label: 'Stock', field: 'stock', align: 'left', sortable: true },
  { name: 'actions', label: 'Acciones', align: 'center' }
]

// Estadísticas
const totalStock = computed(() => products.value.reduce((sum, p) => sum + (p.stock || 0), 0))
const totalValue = computed(() => products.value.reduce((sum, p) => sum + (p.price * p.stock), 0))

// CRUD
const fetchProducts = async () => {
  try {
    const { data } = await api.get('/products')
    products.value = data
  } catch (error) {
    console.error(error)
    Swal.fire('Error', 'No se pudieron cargar los productos.', 'error')
  }
}

const openCreateDialog = () => {
  form.value = { id: null, name: '', description: '', price: 0, stock: 0 }
  isEdit.value = false
  dialog.value = true
}

const openEditDialog = (product) => {
  form.value = { ...product }
  isEdit.value = true
  dialog.value = true
}

const saveProduct = async () => {
  try {
    if (isEdit.value) {
      await api.put(`/products/${form.value.id}`, form.value)
      Swal.fire('Éxito', 'Producto actualizado con éxito.', 'success')
    } else {
      await api.post('/products', form.value)
      Swal.fire('Éxito', 'Producto creado con éxito.', 'success')
    }
    dialog.value = false
    fetchProducts()
  } catch (error) {
    console.error(error)
    Swal.fire('Error', 'No se pudo guardar el producto.', 'error')
  }
}

const confirmDelete = async (id) => {
  const result = await Swal.fire({
    title: '¿Eliminar producto?',
    text: "Esta acción no se puede deshacer.",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#3085d6',
    confirmButtonText: 'Sí, eliminar',
    cancelButtonText: 'Cancelar'
  })
  if (result.isConfirmed) {
    try {
      await api.delete(`/products/${id}`)
      Swal.fire('Eliminado!', 'El producto ha sido eliminado.', 'success')
      fetchProducts()
    } catch (error) {
      console.error(error)
      Swal.fire('Error', 'No se pudo eliminar el producto.', 'error')
    }
  }
}

onMounted(fetchProducts)
</script>

<style scoped>
.products-container {
  max-width: 1400px;
  margin: 0 auto;
}
.header-section {
  background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
  padding: 2rem;
  border-radius: 16px;
  box-shadow: 0 4px 20px rgba(0,0,0,0.08);
}
.btn-nuevo {
  transition: all 0.3s ease;
}
.btn-nuevo:hover {
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
.product-dialog {
  border-radius: 16px;
  overflow: hidden;
}
.bg-gradient-primary {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
</style>
<!--  -->