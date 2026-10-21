<script setup>
import { onMounted, ref } from 'vue'
import { getPublishedNeeds } from '../services/api'
import AnnouncementsList from '../components/AnnouncementsList.vue'

const announcements = ref([])
const loading = ref(true)
const error = ref(null)

async function refresh() {
  loading.value = true
  error.value = null

  try {
    announcements.value = await getPublishedNeeds()
  } catch (cause) {
    error.value = cause instanceof Error
      ? cause.message
      : 'Impossible de charger les annonces.'
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  void refresh()
})
</script>

<template>
  <main class="announcements">
    <div class="announcements__heading">
      <div>
        <p class="eyebrow">Opportunités professionnelles</p>
        <h1>Annonces disponibles</h1>
        <p class="intro">Consultez les besoins dont l’annonce a été publiée.</p>
      </div>
      <button type="button" :disabled="loading" @click="refresh">
        {{ loading ? 'Actualisation...' : 'Actualiser' }}
      </button>
    </div>

    <p v-if="loading && !announcements.length" class="state" role="status">
      Chargement des annonces...
    </p>
    <div v-else-if="error" class="error-state" role="alert">
      <p>{{ error }}</p>
      <button type="button" @click="refresh">Réessayer</button>
    </div>
    <p v-else-if="!announcements.length" class="state">
      Aucune annonce publiée pour le moment.
    </p>
    <AnnouncementsList v-else :announcements="announcements" />
  </main>
</template>

<style scoped>
.announcements {
  width: min(100%, 1100px);
  margin: 0 auto;
}

.announcements__heading {
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

h1 {
  color: #172033;
}

.intro {
  margin-top: 0.5rem;
  color: #64748b;
}

button {
  border: 0;
  border-radius: 0.5rem;
  padding: 0.7rem 1rem;
  color: #fff;
  background: #2563eb;
  cursor: pointer;
  font-weight: 600;
}

button:disabled {
  cursor: wait;
  opacity: 0.65;
}

.state,
.error-state {
  padding: 1rem;
  border-radius: 0.5rem;
}

.state {
  border: 1px dashed #cbd5e1;
  color: #64748b;
  text-align: center;
}

.error-state {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 1rem;
  border: 1px solid #fecaca;
  color: #991b1b;
  background: #fef2f2;
}

@media (max-width: 600px) {
  .announcements__heading,
  .error-state {
    align-items: stretch;
    flex-direction: column;
  }
}
</style>
