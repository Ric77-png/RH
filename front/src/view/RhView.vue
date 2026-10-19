<script setup>
import NeedsList from '../components/NeedsList.vue'
import { useNeeds } from '../composables/useNeeds'

const { needs, loading, error, refresh } = useNeeds()
</script>

<template>
  <main class="rh-page">
    <div class="rh-page__heading">
      <div>
        <p class="eyebrow">Ressources humaines</p>
        
        Notification:{{ needs.length }}
        
        <h2>Liste des besoins</h2>
        <p class="rh-page__intro">Consultez les besoins transmis au service RH.</p>
      </div>
      <button class="refresh-button" type="button" :disabled="loading" @click="refresh">
        {{ loading ? 'Actualisation...' : 'Actualiser' }}
      </button>
    </div>

    <p v-if="loading && !needs.length" class="loading-state" role="status">
      Chargement des besoins...
    </p>

    <div v-else-if="error" class="error-state" role="alert">
      <p>{{ error }}</p>
      <button type="button" @click="refresh">Réessayer</button>
    </div>

    <NeedsList v-else :needs="needs" />
  </main>
</template>

<style scoped>
.rh-page {
  max-width: 1100px;
  margin: 0 auto;
  padding: 3rem 1.5rem;
}

.rh-page__heading {
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
  color: #172033;
  font-size: clamp(1.8rem, 4vw, 2.5rem);
  font-weight: 700;
}

.rh-page__intro {
  margin-top: 0.5rem;
  color: #64748b;
}

.refresh-button,
.error-state button {
  border: 0;
  border-radius: 0.5rem;
  padding: 0.7rem 1rem;
  color: #fff;
  background: #2563eb;
  cursor: pointer;
  font-weight: 600;
}

.refresh-button:disabled {
  cursor: wait;
  opacity: 0.65;
}

.loading-state,
.empty-state {
  padding: 3rem;
  border: 1px dashed #cbd5e1;
  border-radius: 0.75rem;
  color: #64748b;
  text-align: center;
}

.error-state {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 1rem;
  padding: 1rem;
  border: 1px solid #fecaca;
  border-radius: 0.5rem;
  color: #991b1b;
  background: #fef2f2;
}

@media (max-width: 600px) {
  .rh-page__heading,
  .error-state {
    align-items: stretch;
    flex-direction: column;
  }
}
</style>