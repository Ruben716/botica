<template>
  <q-page class="register-page flex flex-center">
    <!-- Background decorativo -->
    <div class="background-decoration">
      <div class="circle circle-1"></div>
      <div class="circle circle-2"></div>
      <div class="circle circle-3"></div>
    </div>

    <q-card class="register-card" flat bordered>
      <!-- Header con gradiente -->
      <div class="card-header">
        <div class="icon-wrapper">
          <q-icon name="local_pharmacy" size="56px" color="white" />
        </div>
        <div class="text-h4 text-weight-bold q-mt-md">Bienvenido</div>
        <div class="text-subtitle1 q-mt-xs">
          Crea tu cuenta en nuestro sistema farmacéutico
        </div>
      </div>

      <!-- Formulario -->
      <q-card-section class="q-pa-lg">
        <q-form @submit="register" class="form-content">
          <!-- Nombre completo -->
          <div class="input-group">
            <q-input
              v-model="name"
              label="Nombre completo"
              outlined
              :rules="[val => !!val || 'El nombre es requerido']"
              lazy-rules
              class="custom-input"
            >
              <template v-slot:prepend>
                <q-icon name="person" color="primary" />
              </template>
              <template v-slot:append>
                <q-icon 
                  v-if="name" 
                  name="check_circle" 
                  color="positive" 
                  size="20px"
                />
              </template>
            </q-input>
          </div>

          <!-- Email -->
          <div class="input-group">
            <q-input
              v-model="email"
              label="Correo electrónico"
              type="email"
              outlined
              :rules="[
                val => !!val || 'El correo es requerido',
                val => /.+@.+\..+/.test(val) || 'Correo inválido'
              ]"
              lazy-rules
              class="custom-input"
            >
              <template v-slot:prepend>
                <q-icon name="email" color="primary" />
              </template>
              <template v-slot:append>
                <q-icon 
                  v-if="email && /.+@.+\..+/.test(email)" 
                  name="check_circle" 
                  color="positive" 
                  size="20px"
                />
              </template>
            </q-input>
          </div>

          <!-- Contraseña -->
          <div class="input-group">
            <q-input
              v-model="password"
              :label="'Contraseña'"
              :type="showPassword ? 'text' : 'password'"
              outlined
              :rules="[
                val => !!val || 'La contraseña es requerida',
                val => val.length >= 6 || 'Mínimo 6 caracteres'
              ]"
              lazy-rules
              class="custom-input"
            >
              <template v-slot:prepend>
                <q-icon name="lock" color="primary" />
              </template>
              <template v-slot:append>
                <q-icon
                  :name="showPassword ? 'visibility' : 'visibility_off'"
                  color="grey-6"
                  class="cursor-pointer"
                  @click="showPassword = !showPassword"
                />
              </template>
            </q-input>
            
            <!-- Indicador de fortaleza -->
            <div v-if="password" class="password-strength q-mt-sm">
              <div class="strength-bar">
                <div 
                  class="strength-fill" 
                  :class="passwordStrength.class"
                  :style="{ width: passwordStrength.width }"
                ></div>
              </div>
              <div class="text-caption q-mt-xs" :class="`text-${passwordStrength.color}`">
                {{ passwordStrength.text }}
              </div>
            </div>
          </div>

          <!-- Términos y condiciones -->
          <div class="q-mt-md">
            <q-checkbox 
              v-model="acceptTerms" 
              dense
              color="primary"
            >
              <span class="text-caption">
                Acepto los 
                <a href="#" class="text-primary text-weight-medium">términos y condiciones</a>
              </span>
            </q-checkbox>
          </div>

          <!-- Botón de registro -->
          <q-btn
            type="submit"
            :loading="loading"
            :disable="!acceptTerms"
            class="register-btn full-width q-mt-lg"
            unelevated
            no-caps
            size="lg"
          >
            <div class="btn-content">
              <q-icon name="person_add" size="24px" class="q-mr-sm" />
              <span class="text-weight-bold">Crear mi cuenta</span>
            </div>
            
            <template v-slot:loading>
              <q-spinner-hourglass color="white" size="24px" />
            </template>
          </q-btn>
        </q-form>
      </q-card-section>

      <!-- Footer -->
      <q-separator class="q-mx-lg" />
      
      <q-card-section class="text-center q-py-md">
        <div class="text-body2 text-grey-7">
          ¿Ya tienes una cuenta?
          <q-btn
            flat
            dense
            no-caps
            color="primary"
            label="Inicia sesión aquí"
            class="text-weight-bold"
            @click="router.push('/login')"
          />
        </div>
      </q-card-section>
    </q-card>
  </q-page>
</template>

<script setup>
import { ref, computed } from 'vue'
import { useRouter } from 'vue-router'
import Swal from 'sweetalert2'
import { api } from 'boot/axios'

const name = ref('')
const email = ref('')
const password = ref('')
const showPassword = ref(false)
const acceptTerms = ref(false)
const loading = ref(false)
const router = useRouter()

// Calcular fortaleza de contraseña
const passwordStrength = computed(() => {
  const pwd = password.value
  if (!pwd) return { width: '0%', class: '', color: '', text: '' }
  
  let strength = 0
  if (pwd.length >= 6) strength++
  if (pwd.length >= 10) strength++
  if (/[a-z]/.test(pwd) && /[A-Z]/.test(pwd)) strength++
  if (/\d/.test(pwd)) strength++
  if (/[^a-zA-Z0-9]/.test(pwd)) strength++
  
  if (strength <= 2) {
    return { 
      width: '33%', 
      class: 'weak', 
      color: 'negative',
      text: 'Débil' 
    }
  } else if (strength <= 3) {
    return { 
      width: '66%', 
      class: 'medium', 
      color: 'warning',
      text: 'Media' 
    }
  } else {
    return { 
      width: '100%', 
      class: 'strong', 
      color: 'positive',
      text: 'Fuerte' 
    }
  }
})

async function register() {
  loading.value = true
  
  try {
    const res = await api.post('/register', {
      name: name.value,
      email: email.value,
      password: password.value
    })

    localStorage.setItem('token', res.data.token)

    await Swal.fire({
      icon: 'success',
      title: '¡Registro exitoso!',
      text: 'Tu cuenta ha sido creada correctamente',
      confirmButtonColor: '#00897b',
      timer: 2000,
      timerProgressBar: true
    })
    
    router.push('/')
  } catch (error) {
    console.error("🔴 Error en register:", error)
    console.error("📌 error.response:", error.response)

    let msg = 'No se pudo registrar el usuario'

    if (error.response?.data?.errors) {
      msg = Object.values(error.response.data.errors).flat().join('<br>')
    } else if (error.response?.data?.message) {
      msg = error.response.data.message
    }

    await Swal.fire({
      icon: 'error',
      title: 'Error al registrar',
      html: msg,
      confirmButtonColor: '#00897b'
    })
  } finally {
    loading.value = false
  }
}
</script>

<style scoped lang="scss">
.register-page {
  min-height: 100vh;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  position: relative;
  overflow: hidden;
}

.background-decoration {
  position: absolute;
  width: 100%;
  height: 100%;
  overflow: hidden;
  
  .circle {
    position: absolute;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.1);
    animation: float 20s infinite ease-in-out;
    
    &.circle-1 {
      width: 300px;
      height: 300px;
      top: -100px;
      left: -100px;
      animation-delay: 0s;
    }
    
    &.circle-2 {
      width: 200px;
      height: 200px;
      bottom: -50px;
      right: -50px;
      animation-delay: 5s;
    }
    
    &.circle-3 {
      width: 150px;
      height: 150px;
      top: 50%;
      right: 10%;
      animation-delay: 10s;
    }
  }
}

@keyframes float {
  0%, 100% {
    transform: translateY(0) rotate(0deg);
  }
  50% {
    transform: translateY(-30px) rotate(180deg);
  }
}

.register-card {
  width: 480px;
  max-width: 92vw;
  border-radius: 24px;
  overflow: hidden;
  background: white;
  box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
  position: relative;
  z-index: 1;
  animation: slideUp 0.6s ease-out;
}

@keyframes slideUp {
  from {
    opacity: 0;
    transform: translateY(30px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

.card-header {
  background: linear-gradient(135deg, #00897b 0%, #00695c 100%);
  padding: 40px 32px;
  text-align: center;
  color: white;
  position: relative;
  overflow: hidden;
  
  &::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
    animation: pulse 4s infinite;
  }
  
  .icon-wrapper {
    display: inline-flex;
    padding: 16px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    backdrop-filter: blur(10px);
    animation: iconBounce 2s infinite ease-in-out;
  }
}

@keyframes pulse {
  0%, 100% {
    transform: translate(0, 0) scale(1);
  }
  50% {
    transform: translate(-5%, -5%) scale(1.1);
  }
}

@keyframes iconBounce {
  0%, 100% {
    transform: translateY(0);
  }
  50% {
    transform: translateY(-10px);
  }
}

.form-content {
  .input-group {
    margin-bottom: 20px;
    
    .custom-input {
      :deep(.q-field__control) {
        border-radius: 12px;
        transition: all 0.3s ease;
        
        &:hover {
          box-shadow: 0 4px 12px rgba(0, 137, 123, 0.15);
        }
      }
      
      :deep(.q-field__control::before) {
        border-color: #e0e0e0;
      }
      
      :deep(.q-field--focused .q-field__control) {
        box-shadow: 0 0 0 2px rgba(0, 137, 123, 0.2);
      }
    }
  }
}

.password-strength {
  .strength-bar {
    height: 4px;
    background: #e0e0e0;
    border-radius: 2px;
    overflow: hidden;
    
    .strength-fill {
      height: 100%;
      transition: all 0.3s ease;
      border-radius: 2px;
      
      &.weak {
        background: linear-gradient(90deg, #f44336, #e91e63);
      }
      
      &.medium {
        background: linear-gradient(90deg, #ff9800, #ffc107);
      }
      
      &.strong {
        background: linear-gradient(90deg, #4caf50, #8bc34a);
      }
    }
  }
}

.register-btn {
  background: linear-gradient(135deg, #00897b 0%, #00695c 100%);
  color: white;
  border-radius: 12px;
  padding: 12px 0;
  transition: all 0.3s ease;
  box-shadow: 0 4px 12px rgba(0, 137, 123, 0.3);
  
  &:hover:not(:disabled) {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(0, 137, 123, 0.4);
  }
  
  &:active:not(:disabled) {
    transform: translateY(0);
  }
  
  .btn-content {
    display: flex;
    align-items: center;
    justify-content: center;
  }
}

// Responsive
@media (max-width: 600px) {
  .register-card {
    border-radius: 16px;
  }
  
  .card-header {
    padding: 32px 24px;
    
    .text-h4 {
      font-size: 1.75rem;
    }
  }
}
</style>