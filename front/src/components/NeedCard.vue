<script setup>
import { ref } from 'vue'
import { getNeedDetails } from '../services/api'

const details = ref(null)
const loadingDetails = ref(false)
const detailsError = ref(null)

defineProps({
  need: {
    type: Object,
    required: true,
  },
})

async function showDetails(reference) {
  details.value = null
  detailsError.value = null
  loadingDetails.value = true

  try {
    details.value = await getNeedDetails(reference)
  } catch (error) {
    detailsError.value = error instanceof Error
      ? error.message
      : 'Impossible de charger le détail du besoin.'
  } finally {
    loadingDetails.value = false
  }
}

function valueOf(need, keys, fallback = 'Non renseigné') {
  const key = keys.find((candidate) => need[candidate] !== undefined && need[candidate] !== null)
  return key ? need[key] : fallback
}
</script>

<template>
  <tr class="need-row">
    <td>
      <a href="#" @click.prevent="showDetails(valueOf(need, ['reference']))">
        {{ valueOf(need, ['reference']) }}
      </a>
    </td>
    <td>{{ valueOf(need, ['service', 'departement', 'departements']) }}</td>
    <td>{{ valueOf(need, ['intitule_poste']) }}</td>
    <td>{{ valueOf(need, ['nombre_postes', 'nombrePostes', 'quantity', 'quantite', 'nb_postes']) }}</td>
    <td>{{ valueOf(need, ['salaire_propose', 'salaire', 'salary'], '—') }} Ar</td>
    <td>
      <span class="badge">{{ valueOf(need, ['statut', 'status']) }}</span>
    </td>
  </tr>
  <tr v-if="loadingDetails || detailsError || details" class="need-details-row">
    <td colspan="6">
      <p v-if="loadingDetails">Chargement du détail...</p>
      <p v-else-if="detailsError" role="alert">{{ detailsError }}</p>
      <pre v-else>{{ JSON.stringify(details, null, 2) }}</pre>
    </td>
  </tr>
</template>