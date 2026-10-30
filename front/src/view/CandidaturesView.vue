<script setup>
import { onMounted, ref } from 'vue'
import { getPendingApplications } from '@/services/api'

const applications = ref([])
const loading = ref(true)
const error = ref('')

async function loadApplications() {
  loading.value = true
  error.value = ''

  try {
    const payload = await getPendingApplications()
    applications.value = Array.isArray(payload?.data) ? payload.data : []
  } catch (cause) {
    error.value = cause instanceof Error
      ? cause.message
      : 'Impossible de charger les candidatures.'
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  void loadApplications()
})
</script>

<template>
  <main class="applications-page">
    <div class="page-heading">
      <div>
        <p class="eyebrow">Ressources humaines</p>
        <h2>Candidatures à traiter</h2>
        <p class="intro">Liste des candidatures reçues et encore non traitées.</p>
      </div>
      <button type="button" :disabled="loading" @click="loadApplications">
        {{ loading ? 'Actualisation...' : 'Actualiser' }}
      </button>
    </div>

    <p v-if="loading" class="state" role="status">Chargement des candidatures...</p>

    <div v-else-if="error" class="error-state" role="alert">
      <p>{{ error }}</p>
      <button type="button" @click="loadApplications">Réessayer</button>
    </div>

    <p v-else-if="!applications.length" class="state">
      Aucune candidature non traitée.
    </p>

    <div v-else class="table-wrapper">
      <table>
        <thead>
          <tr>
            <th>Candidat</th>
            <th>E-mail</th>
            <th>Téléphone</th>
            <th>Annonce</th>
            <th>Date de candidature</th>
            <th>Statut</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="application in applications" :key="application.id">
            <td>{{ application.prenom }} {{ application.nom }}</td>
            <td>{{ application.email }}</td>
            <td>{{ application.telephone || 'Non renseigné' }}</td>
            <td>{{ application.titre || `Annonce #${application.annonce_id}` }}</td>
            <td>{{ new Date(application.date_candidature).toLocaleString('fr-FR') }}</td>
            <td><span class="badge">{{ application.statut }}</span></td>
          </tr>
        </tbody>
      </table>
    </div>
  </main>
</template>

<style scoped>
.applications-page {
  width: 100%;
}

.page-heading {
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

.table-wrapper {
  overflow-x: auto;
  border: 1px solid #e2e8f0;
  border-radius: 0.75rem;
  background: #fff;
}

table {
  width: 100%;
  border-collapse: collapse;
}

th,
td {
  padding: 0.85rem 1rem;
  border-bottom: 1px solid #e2e8f0;
  text-align: left;
  white-space: nowrap;
}

th {
  background: #f8fafc;
}

.badge {
  display: inline-block;
  padding: 0.2rem 0.55rem;
  border-radius: 999px;
  color: #92400e;
  background: #fef3c7;
  font-size: 0.8rem;
  font-weight: 600;
}

@media (max-width: 600px) {
  .page-heading,
  .error-state {
    align-items: stretch;
    flex-direction: column;
  }
}
</style>
