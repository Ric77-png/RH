<template>
  <div class="auth-shell">
    <div class="auth-card">
      <div class="tabs">
        <button :class="{ active: isLoginTab }" type="button" @click="isLoginTab = true">Connexion</button>
        <button :class="{ active: !isLoginTab }" type="button" @click="isLoginTab = false">Créer un compte</button>
      </div>

      <div v-if="isLoginTab">
        <h2>Login</h2>
        <form @submit.prevent="handleLogin" novalidate>
          <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" v-model="email" required />
          </div>
          <div class="form-group">
            <label for="password">Mot de passe</label>
            <input type="password" id="password" v-model="password" required />
          </div>
          <div v-if="error" class="error-msg">{{ error }}</div>
          <button type="submit" :disabled="isLoading">
            {{ isLoading ? 'Connexion...' : 'Se connecter' }}
          </button>
        </form>
      </div>

      <div v-else>
        <h2>Inscription candidat</h2>
        <form @submit.prevent="handleRegister" novalidate>
          <div class="form-group">
            <label for="firstName">Prénom</label>
            <input id="firstName" v-model="firstName" required />
          </div>
          <div class="form-group">
            <label for="lastName">Nom</label>
            <input id="lastName" v-model="lastName" required />
          </div>
          <div class="form-group">
            <label for="department">Département souhaité</label>
            <select id="department" v-model="departmentId" required>
              <option value="">Choisir un département</option>
              <option value="1">Informatique</option>
              <option value="2">Finance</option>
              <option value="3">RH</option>
              <option value="4">Marketing</option>
              <option value="5">Administration</option>
            </select>
          </div>
          <div class="form-group">
            <label for="telephone">Numéro de téléphone</label>
            <input id="telephone" v-model="telephone" type="tel" inputmode="tel" placeholder="0601020304" required />
          </div>
          <div class="form-group">
            <label for="registerEmail">Email</label>
            <input type="email" id="registerEmail" v-model="registerEmail" required />
          </div>
          <div class="form-group">
            <label for="registerPassword">Mot de passe</label>
            <input type="password" id="registerPassword" v-model="registerPassword" required />
          </div>
          <div v-if="registerError" class="error-msg">{{ registerError }}</div>
          <button type="submit" :disabled="isLoading">
            {{ isLoading ? 'Création...' : 'Créer mon compte' }}
          </button>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/authStore'

const email = ref('')
const password = ref('')
const firstName = ref('')
const lastName = ref('')
const departmentId = ref('')
const telephone = ref('')
const registerEmail = ref('')
const registerPassword = ref('')
const error = ref('')
const registerError = ref('')
const isLoading = ref(false)
const isLoginTab = ref(true)

const router = useRouter()
const authStore = useAuthStore()

const validateEmail = (value) => /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)
const validatePhone = (value) => /^\+?[0-9\s.-]{8,15}$/.test(value)

const redirectByRole = (role) => {
  switch (role) {
    case 'manager':
      router.push({ name: 'manager-dashboard' })
      break
    case 'rh':
      router.push({ name: 'rh-dashboard' })
      break
    case 'finance':
      router.push({ name: 'finance-dashboard' })
      break
    case 'direction':
      router.push({ name: 'direction-dashboard' })
      break
    case 'candidat':
      router.push({ name: 'candidat-dashboard' })
      break
    default:
      router.push({ name: 'accueil' })
  }
}

const handleLogin = async () => {
  error.value = ''

  if (!validateEmail(email.value)) {
    error.value = 'Veuillez entrer un email valide.'
    return
  }

  if (!password.value || password.value.length < 6) {
    error.value = 'Le mot de passe doit contenir au moins 6 caractères.'
    return
  }

  isLoading.value = true

  try {
    const user = await authStore.login(email.value, password.value)
    redirectByRole(user.role)
  } catch (err) {
    error.value = err.message || 'Erreur de connexion'
  } finally {
    isLoading.value = false
  }
}

const handleRegister = async () => {
  registerError.value = ''

  if (!firstName.value.trim() || !lastName.value.trim()) {
    registerError.value = 'Le prénom et le nom sont obligatoires.'
    return
  }

  if (!departmentId.value) {
    registerError.value = 'Veuillez choisir un département.'
    return
  }

  if (!validatePhone(telephone.value)) {
    registerError.value = 'Veuillez saisir un numéro de téléphone valide.'
    return
  }

  if (!validateEmail(registerEmail.value)) {
    registerError.value = 'Veuillez entrer un email valide.'
    return
  }

  if (!registerPassword.value || registerPassword.value.length < 6) {
    registerError.value = 'Le mot de passe doit contenir au moins 6 caractères.'
    return
  }

  isLoading.value = true

  try {
    const user = await authStore.register(
      lastName.value.trim(),
      firstName.value.trim(),
      registerEmail.value.trim(),
      registerPassword.value,
      Number(departmentId.value),
      telephone.value.trim(),
    )
    redirectByRole(user.role)
  } catch (err) {
    registerError.value = err.message || 'Erreur d’inscription'
  } finally {
    isLoading.value = false
  }
}
</script>

<style scoped>
.auth-shell {
  min-height: 100vh;
  display: grid;
  place-items: center;
  background: #f3f4f6;
}

.auth-card {
  width: min(560px, calc(100% - 2rem));
  background: white;
  border-radius: 16px;
  box-shadow: 0 20px 50px rgba(15, 23, 42, 0.08);
  padding: 2rem;
}

.tabs {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 0.5rem;
  margin-bottom: 1.5rem;
}

.tabs button {
  border: 1px solid #d1d5db;
  border-radius: 999px;
  background: #f9fafb;
  padding: 0.7rem 1rem;
  cursor: pointer;
  font-weight: 600;
}

.tabs button.active {
  background: #2563eb;
  color: white;
  border-color: #2563eb;
}

h2 {
  margin-bottom: 1.5rem;
  font-size: clamp(2rem, 3vw, 3rem);
  font-weight: 800;
}

.form-group {
  margin-bottom: 15px;
}

.form-group label {
  display: block;
  margin-bottom: 5px;
  font-weight: 600;
  font-size: 1.1rem;
}

.form-group input,
.form-group select {
  width: 100%;
  padding: 12px 14px;
  border: 1px solid #d1d5db;
  border-radius: 10px;
  box-sizing: border-box;
  font-size: 1rem;
  background: #fff;
}

.error-msg {
  color: #dc2626;
  margin-bottom: 10px;
  font-weight: 600;
}

button[type='submit'] {
  width: 100%;
  padding: 14px;
  background: #111827;
  color: white;
  border: none;
  border-radius: 10px;
  cursor: pointer;
  font-weight: 700;
  font-size: 1.1rem;
}

button:disabled {
  opacity: 0.7;
  cursor: wait;
}
</style>