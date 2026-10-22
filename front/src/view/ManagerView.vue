<script setup>
import { ref } from 'vue'
import NeedsList from '../components/NeedsList.vue'
import ManagerNeedForm from '../components/ManagerNeedForm.vue'
import { useManagerNeeds } from '../composables/useManagerNeeds'

const showForm = ref(false)

const {
  needs,
  departments,
  loading,
  error,
  form,
  refresh,
  submitNeed,
} = useManagerNeeds()

async function handleSubmit(payload) {
  const success = await submitNeed(payload) // <-- garder le payload (is_draft)
  if (success) {
    showForm.value = false
  }
}
</script>

<template>
  <main class="manager-page">
    <header class="manager-page__header">
      <div>
        <p class="eyebrow">Manager</p>
        <h2>Besoins envoyés</h2>
        <p class="intro">
          Gérez les besoins en recrutement de votre département.
        </p>
      </div>

      <div class="header-actions">
        <button type="button" class="secondary-button" :disabled="loading" @click="refresh">
          {{ loading ? 'Actualisation...' : 'Actualiser' }}
        </button>

        <button type="button" class="primary-button" @click="showForm = !showForm">
          {{ showForm ? 'Fermer' : 'Ajouter un besoin' }}
        </button>
      </div>
    </header>

   <ManagerNeedForm
      v-if="showForm"
      :form="form"
      :departments="departments"
      @submit="handleSubmit"
      @cancel="showForm = false"
    />

    <div v-if="error" class="error-state" role="alert">
      <p>{{ error }}</p>
      <button type="button" @click="refresh">Réessayer</button>
    </div>

    <p v-else-if="loading && !needs.length" class="loading-state" role="status">
      Chargement des besoins...
    </p>

    <NeedsList v-else :needs="needs" />
  </main>
</template>

<style scoped>
.manager-page {
  max-width: 1100px;
  margin: 0 auto;
  padding: 3rem 1.5rem;
}

.manager-page__header {
  display: flex;
  align-items: flex-end;
  justify-content: space-between;
  gap: 1.5rem;
  margin-bottom: 2rem;
}

.eyebrow {
  margin-bottom: 0.35rem;
  color: #2563eb;
  font-size: 0.8rem;
  font-weight: 700;
  letter-spacing: 0.08em;
  text-transform: uppercase;
}

h2 {
  margin: 0;
  color: #172033;
  font-size: clamp(1.8rem, 4vw, 2.5rem);
  font-weight: 700;
}

.intro {
  margin-top: 0.5rem;
  color: #64748b;
}

.header-actions {
  display: flex;
  gap: 0.75rem;
}

.primary-button,
.secondary-button,
.error-state button {
  padding: 0.75rem 1rem;
  border: 0;
  border-radius: 0.5rem;
  font-weight: 600;
  cursor: pointer;
}

.primary-button,
.error-state button {
  color: white;
  background: #2563eb;
}

.secondary-button {
  color: #0f172a;
  background: #e2e8f0;
}

button:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.loading-state,
.error-state {
  padding: 1rem;
  border-radius: 0.75rem;
  background: #f8fafc;
  border: 1px solid #e2e8f0;
}

.error-state {
  color: #b91c1c;
}

@media (max-width: 700px) {
  .manager-page__header {
    flex-direction: column;
    align-items: flex-start;
  }
}
</style>