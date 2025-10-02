<template>
  <div class="q-pa-md clients-container">
    <!-- Header Moderno -->
    <div class="header-section q-mb-lg">
      <div class="row items-center justify-between">
        <div class="col-auto">
          <div class="text-h4 text-weight-bold text-primary q-mb-xs">
            <q-icon name="groups" size="40px" class="q-mr-sm" />
            Gestión de Clientes
          </div>
          <div class="text-subtitle1 text-grey-7">
            Registro y administración de clientes frecuentes
          </div>
        </div>
        <div class="col-auto">
          <q-btn 
            label="Nuevo Cliente" 
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

    <!-- Tabla de clientes -->
    <q-card flat bordered class="shadow-4 table-card">
      <q-card-section class="bg-grey-2 q-py-md">
        <div class="text-h6 text-weight-bold">
          <q-icon name="list_alt" class="q-mr-sm" />
          Lista de Clientes
        </div>
      </q-card-section>

      <q-table
        :rows="clients"
        :columns="columns"
        row-key="id"
        flat
        bordered
        :rows-per-page-options="[10,25,50]"
        separator="cell"
        no-data-label="No hay clientes registrados"
        class="modern-table"
      >
        <template v-slot:body-cell-id="props">
          <q-td :props="props">
            <q-chip dense color="primary" text-color="white" size="sm">
              #{{ props.value }}
            </q-chip>
          </q-td>
        </template>

        <template v-slot:body-cell-email="props">
          <q-td :props="props">
            <q-icon name="email" size="16px" class="q-mr-xs text-primary" />
            {{ props.value }}
          </q-td>
        </template>

        <template v-slot:body-cell-phone="props">
          <q-td :props="props">
            <q-badge color="secondary" :label="props.value" />
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
      <q-card class="client-dialog" style="min-width: 600px; max-width: 800px;">
        <q-card-section class="bg-gradient-primary text-white q-pa-md">
          <div class="text-h5 text-weight-bold">
            {{ isEdit ? '✏️ Editar Cliente' : '🆕 Nuevo Cliente' }}
          </div>
        </q-card-section>

        <q-card-section class="q-pa-md">
          <q-input v-model="form.name" label="Nombre" outlined dense autofocus>
            <template v-slot:prepend><q-icon name="person" /></template>
          </q-input>
          <q-input v-model="form.email" label="Correo" type="email" outlined dense class="q-mt-sm">
            <template v-slot:prepend><q-icon name="email" /></template>
          </q-input>
          <q-input v-model="form.phone" label="Teléfono" outlined dense class="q-mt-sm">
            <template v-slot:prepend><q-icon name="phone" /></template>
          </q-input>
          <q-input v-model="form.address" label="Dirección" outlined dense class="q-mt-sm">
            <template v-slot:prepend><q-icon name="home" /></template>
          </q-input>
        </q-card-section>

        <q-card-actions align="right" class="q-pa-md">
          <q-btn flat label="Cancelar" color="grey-7" v-close-popup no-caps />
          <q-btn label="Guardar" color="positive" unelevated @click="saveClient" icon-right="check_circle" no-caps class="q-px-md" />
        </q-card-actions>
      </q-card>
    </q-dialog>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { api } from 'boot/axios'
import Swal from 'sweetalert2'

const clients = ref([])
const dialog = ref(false)
const isEdit = ref(false)
const form = ref({ id: null, name: '', email: '', phone: '', address: '' })

// Columnas de la tabla
const columns = [
  { name: 'id', label: 'ID', field: 'id', align: 'left', sortable: true },
  { name: 'name', label: 'Nombre', field: 'name', align: 'left', sortable: true },
  { name: 'email', label: 'Correo', field: 'email', align: 'left', sortable: true },
  { name: 'phone', label: 'Teléfono', field: 'phone', align: 'left', sortable: true },
  { name: 'address', label: 'Dirección', field: 'address', align: 'left', sortable: true },
  { name: 'actions', label: 'Acciones', align: 'center' }
]

// CRUD
const fetchClients = async () => {
  try {
    const { data } = await api.get('/clients')
    clients.value = data
  } catch (error) {
    console.error(error)
    Swal.fire('Error', 'No se pudieron cargar los clientes.', 'error')
  }
}

const openCreateDialog = () => {
  form.value = { id: null, name: '', email: '', phone: '', address: '' }
  isEdit.value = false
  dialog.value = true
}

const openEditDialog = (client) => {
  form.value = { ...client }
  isEdit.value = true
  dialog.value = true
}

const saveClient = async () => {
  try {
    if (isEdit.value) {
      await api.put(`/clients/${form.value.id}`, form.value)
      Swal.fire('Éxito', 'Cliente actualizado con éxito.', 'success')
    } else {
      await api.post('/clients', form.value)
      Swal.fire('Éxito', 'Cliente creado con éxito.', 'success')
    }
    dialog.value = false
    fetchClients()
  } catch (error) {
    console.error(error)
    Swal.fire('Error', 'No se pudo guardar el cliente.', 'error')
  }
}

const confirmDelete = async (id) => {
  const result = await Swal.fire({
    title: '¿Eliminar cliente?',
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
      await api.delete(`/clients/${id}`)
      Swal.fire('Eliminado!', 'El cliente ha sido eliminado.', 'success')
      fetchClients()
    } catch (error) {
      console.error(error)
      Swal.fire('Error', 'No se pudo eliminar el cliente.', 'error')
    }
  }
}

onMounted(fetchClients)
</script>

<style scoped>
.clients-container {
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
.client-dialog {
  border-radius: 16px;
  overflow: hidden;
}
.bg-gradient-primary {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}
</style>
