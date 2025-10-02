<template>
  <q-page class="login-page flex flex-center">
    <!-- Background decorativo -->
    <div class="background-decoration">
      <div class="pill pill-1"></div>
      <div class="pill pill-2"></div>
      <div class="cross cross-1"></div>
      <div class="cross cross-2"></div>
    </div>

    <q-card class="login-card" flat bordered>
      <!-- Header con gradiente -->
      <div class="card-header">
        <div class="icon-wrapper">
          <q-icon name="medical_services" size="56px" color="white" />
        </div>
        <div class="text-h4 text-weight-bold q-mt-md">¡Bienvenido!</div>
        <div class="text-subtitle1 q-mt-xs">
          Ingresa a tu sistema de gestión farmacéutica
        </div>
      </div>

      <!-- Formulario -->
      <q-card-section class="q-pa-lg">
        <q-form @submit="login" class="form-content">
          <!-- Email -->
          <div class="input-group">
            <label class="input-label">Correo electrónico</label>
            <q-input
              v-model="email"
              type="email"
              outlined
              :rules="[
                val => !!val || 'El correo es requerido',
                val => /.+@.+\..+/.test(val) || 'Correo inválido'
              ]"
              lazy-rules
              class="custom-input"
              placeholder="tu@email.com"
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
            <div class="row justify-between items-center q-mb-xs">
              <label class="input-label">Contraseña</label>
              <a href="#" class="forgot-link">¿Olvidaste tu contraseña?</a>
            </div>
            <q-input
              v-model="password"
              :type="showPassword ? 'text' : 'password'"
              outlined
              :rules="[val => !!val || 'La contraseña es requerida']"
              lazy-rules
              class="custom-input"
              placeholder="••••••••"
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
          </div>

          <!-- Recordarme -->
          <div class="q-mt-md">
            <q-checkbox 
              v-model="rememberMe" 
              dense
              color="primary"
              label="Mantener sesión iniciada"
            />
          </div>

          <!-- Botón de login -->
          <q-btn
            type="submit"
            :loading="loading"
            class="login-btn full-width q-mt-lg"
            unelevated
            no-caps
            size="lg"
          >
            <div class="btn-content">
              <q-icon name="login" size="24px" class="q-mr-sm" />
              <span class="text-weight-bold">Iniciar Sesión</span>
            </div>
            
            <template v-slot:loading>
              <q-spinner-dots color="white" size="32px" />
            </template>
          </q-btn>

          <!-- Divider -->
          <div class="divider-section q-my-lg">
            <div class="divider-line"></div>
            <span class="divider-text">O continuar con</span>
            <div class="divider-line"></div>
          </div>

          <!-- Botones sociales -->
          <div class="social-buttons row q-gutter-sm">
            <q-btn
              outline
              no-caps
              class="col social-btn"
              @click="socialLogin('google')"
            >
              <q-icon name="img:https://cdn.cdnlogo.com/logos/g/35/google-icon.svg" size="20px" />
            </q-btn>
            <q-btn
              outline
              no-caps
              class="col social-btn"
              @click="socialLogin('facebook')"
            >
              <q-icon name="img:https://cdn.cdnlogo.com/logos/f/83/facebook.svg" size="20px" />
            </q-btn>
            <q-btn
              outline
              no-caps
              class="col social-btn"
              @click="socialLogin('microsoft')"
            >
              <q-icon name="img:https://cdn.cdnlogo.com/logos/m/17/microsoft.svg" size="20px" />
            </q-btn>
          </div>
        </q-form>
      </q-card-section>

      <!-- Footer -->
      <q-separator class="q-mx-lg" />
      
      <q-card-section class="text-center q-py-md">
        <div class="text-body2 text-grey-7">
          ¿No tienes una cuenta?
          <q-btn
            flat
            dense
            no-caps
            color="primary"
            label="Regístrate gratis"
            class="text-weight-bold"
            @click="router.push('/register')"
          />
        </div>
      </q-card-section>
    </q-card>

    <!-- Info footer -->
    <div class="info-footer">
      <q-icon name="shield" size="16px" class="q-mr-xs" />
      <span class="text-caption">Conexión segura y cifrada</span>
    </div>
  </q-page>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import Swal from 'sweetalert2'
import { api } from 'boot/axios'

const email = ref('')
const password = ref('')
const showPassword = ref(false)
const rememberMe = ref(false)
const loading = ref(false)
const router = useRouter()

async function login() {
  loading.value = true
  
  try {
    const res = await api.post('/login', {
      email: email.value,
      password: password.value
    })
    
    localStorage.setItem('token', res.data.token)
    
    if (rememberMe.value) {
      localStorage.setItem('rememberMe', 'true')
    }

    await Swal.fire({
      icon: 'success',
      title: '¡Bienvenido de vuelta!',
      text: 'Has iniciado sesión correctamente',
      confirmButtonColor: '#00897b',
      timer: 2000,
      timerProgressBar: true,
      showConfirmButton: false
    })
    
    router.push('/')
  } catch (error) {
    console.error("🔴 Error en login:", error)
    console.error("📌 error.response:", error.response)

    await Swal.fire({
      icon: 'error',
      title: 'Error de autenticación',
      text: error.response?.data?.message || 'Credenciales inválidas. Verifica tu correo y contraseña.',
      confirmButtonColor: '#00897b'
    })
  } finally {
    loading.value = false
  }
}

function socialLogin(provider) {
  Swal.fire({
    icon: 'info',
    title: 'Próximamente',
    text: `Login con ${provider} estará disponible pronto`,
    confirmButtonColor: '#00897b'
  })
}
</script>

<style scoped lang="scss">
.login-page {
  min-height: 100vh;
  background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
  position: relative;
  overflow: hidden;
}

.background-decoration {
  position: absolute;
  width: 100%;
  height: 100%;
  overflow: hidden;
  opacity: 0.6;
  
  .pill {
    position: absolute;
    width: 120px;
    height: 40px;
    background: rgba(255, 255, 255, 0.15);
    border-radius: 20px;
    animation: floatPill 15s infinite ease-in-out;
    
    &.pill-1 {
      top: 10%;
      left: 15%;
      animation-delay: 0s;
    }
    
    &.pill-2 {
      bottom: 20%;
      right: 10%;
      animation-delay: 7s;
      transform: rotate(45deg);
    }
  }
  
  .cross {
    position: absolute;
    width: 60px;
    height: 60px;
    
    &::before,
    &::after {
      content: '';
      position: absolute;
      background: rgba(255, 255, 255, 0.2);
    }
    
    &::before {
      width: 100%;
      height: 15px;
      top: 50%;
      transform: translateY(-50%);
    }
    
    &::after {
      width: 15px;
      height: 100%;
      left: 50%;
      transform: translateX(-50%);
    }
    
    animation: rotateCross 20s infinite linear;
    
    &.cross-1 {
      top: 20%;
      right: 20%;
    }
    
    &.cross-2 {
      bottom: 15%;
      left: 10%;
      animation-delay: 10s;
    }
  }
}

@keyframes floatPill {
  0%, 100% {
    transform: translateY(0) rotate(0deg);
  }
  50% {
    transform: translateY(-40px) rotate(180deg);
  }
}

@keyframes rotateCross {
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(360deg);
  }
}

.login-card {
  width: 450px;
  max-width: 92vw;
  border-radius: 24px;
  overflow: hidden;
  background: white;
  box-shadow: 0 20px 60px rgba(0, 0, 0, 0.25);
  position: relative;
  z-index: 1;
  animation: scaleIn 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}

@keyframes scaleIn {
  from {
    opacity: 0;
    transform: scale(0.9);
  }
  to {
    opacity: 1;
    transform: scale(1);
  }
}

.card-header {
  background: linear-gradient(135deg, #00897b 0%, #00695c 100%);
  padding: 40px 32px 35px;
  text-align: center;
  color: white;
  position: relative;
  overflow: hidden;
  
  &::before {
    content: '';
    position: absolute;
    width: 200px;
    height: 200px;
    background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, transparent 70%);
    top: -100px;
    right: -50px;
    animation: glow 3s infinite ease-in-out;
  }
  
  .icon-wrapper {
    display: inline-flex;
    padding: 20px;
    background: rgba(255, 255, 255, 0.25);
    border-radius: 50%;
    backdrop-filter: blur(10px);
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
    position: relative;
    
    &::after {
      content: '';
      position: absolute;
      inset: -4px;
      border-radius: 50%;
      border: 2px solid rgba(255, 255, 255, 0.3);
      animation: pulse 2s infinite;
    }
  }
}

@keyframes glow {
  0%, 100% {
    opacity: 0.5;
    transform: translate(0, 0);
  }
  50% {
    opacity: 0.8;
    transform: translate(-20px, 20px);
  }
}

@keyframes pulse {
  0%, 100% {
    transform: scale(1);
    opacity: 1;
  }
  50% {
    transform: scale(1.1);
    opacity: 0.5;
  }
}

.form-content {
  .input-group {
    margin-bottom: 20px;
    
    .input-label {
      display: block;
      font-size: 14px;
      font-weight: 600;
      color: #424242;
      margin-bottom: 8px;
    }
    
    .forgot-link {
      font-size: 12px;
      color: #00897b;
      text-decoration: none;
      font-weight: 500;
      transition: color 0.3s;
      
      &:hover {
        color: #00695c;
        text-decoration: underline;
      }
    }
    
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

.login-btn {
  background: linear-gradient(135deg, #00897b 0%, #00695c 100%);
  color: white;
  border-radius: 12px;
  padding: 14px 0;
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

.divider-section {
  display: flex;
  align-items: center;
  gap: 16px;
  
  .divider-line {
    flex: 1;
    height: 1px;
    background: linear-gradient(90deg, transparent, #e0e0e0, transparent);
  }
  
  .divider-text {
    font-size: 12px;
    color: #9e9e9e;
    font-weight: 500;
    white-space: nowrap;
  }
}

.social-buttons {
  .social-btn {
    border-radius: 10px;
    padding: 10px;
    border-color: #e0e0e0;
    transition: all 0.3s ease;
    
    &:hover {
      border-color: #00897b;
      background: rgba(0, 137, 123, 0.05);
      transform: translateY(-2px);
    }
    
    :deep(.q-icon) {
      width: 20px;
      height: 20px;
    }
  }
}

.info-footer {
  position: absolute;
  bottom: 20px;
  display: flex;
  align-items: center;
  color: rgba(255, 255, 255, 0.9);
  background: rgba(255, 255, 255, 0.15);
  padding: 8px 16px;
  border-radius: 20px;
  backdrop-filter: blur(10px);
  font-weight: 500;
  animation: fadeIn 1s ease-in 0.5s both;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

// Responsive
@media (max-width: 600px) {
  .login-card {
    border-radius: 16px;
  }
  
  .card-header {
    padding: 32px 24px 28px;
    
    .text-h4 {
      font-size: 1.75rem;
    }
    
    .icon-wrapper {
      padding: 16px;
    }
  }
  
  .social-buttons {
    flex-direction: column;
    
    .social-btn {
      width: 100%;
    }
  }
  
  .info-footer {
    bottom: 10px;
    font-size: 11px;
  }
}
</style>