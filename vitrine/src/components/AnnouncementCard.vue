<script setup>
import { ref } from 'vue'
import { getAnnouncementDetails } from '../services/api'

defineProps({
  announcement: {
    type: Object,
    required: true,
  },
})

const details = ref(null)
const loadingDetails = ref(false)
const detailsError = ref(null)

async function showDetails(reference) {
  details.value = null
  detailsError.value = null
  loadingDetails.value = true

  try {
    details.value = await getAnnouncementDetails(reference)
  } catch (cause) {
    detailsError.value = cause instanceof Error
      ? cause.message
      : 'Impossible de charger le détail de l’annonce.'
  } finally {
    loadingDetails.value = false
  }
}
</script>

<template>
  <tr class="need-row">
    <td>
      <a href="#" @click.prevent="showDetails(announcement.reference)">
        {{ announcement.reference }}
      </a>
    </td>
    <td>{{ announcement.departement || 'Non renseigné' }}</td>
    <td>{{ announcement.intitule_poste || 'Non renseigné' }}</td>
    <td>{{ announcement.nb_postes ?? 'Non renseigné' }}</td>
    <td>{{ announcement.salaire_propose ?? '—' }} Ar</td>
    <td><span class="badge">{{ announcement.statut }}</span></td>
  </tr>
  <tr v-if="loadingDetails || detailsError || details" class="need-details-row">
    <td colspan="6">
      <p v-if="loadingDetails">Chargement du détail...</p>
      <p v-else-if="detailsError" role="alert">{{ detailsError }}</p>
      <pre v-else>{{ JSON.stringify(details, null, 2) }}</pre>
    </td>
  </tr>
</template>
