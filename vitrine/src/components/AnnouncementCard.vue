<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { getAnnouncementDetails } from '../services/api'

const props = defineProps({
  announcement: {
    type: Object,
    required: true,
  },
})

const details = ref(null)
const loadingDetails = ref(false)
const detailsError = ref(null)
const router = useRouter()

function applyForAnnouncement(reference) {
  void router.push({
    name: 'postuler',
    query: {
      reference,
      announcementId: props.announcement.annonce_id,
    },
  })
}

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
  <li class="need-row">
    <li>
      <a href="#" @click.prevent="showDetails(announcement.reference)">
        {{ announcement.reference }}
      </a>
    </li>
    <li>{{ announcement.departement || 'Non renseigné' }}</li>
    <li>{{ announcement.intitule_poste || 'Non renseigné' }}</li>
    <li>{{ announcement.nb_postes ?? 'Non renseigné' }}</li>
    <li>{{ announcement.salaire_propose ?? '—' }} Ar</li>
    <li>{{ announcement.type_contrat || 'Non renseigné' }}</li>
    <li><span class="badge">{{ announcement.statut }}</span></li>
    <li>
      <button type="button" class="apply-button" @click="applyForAnnouncement(announcement.reference)">
        Postuler
      </button>
    </li>
  </li>
  <li v-if="loadingDetails || detailsError || details" class="need-details-row">
    <li colspan="6">
      <p v-if="loadingDetails">Chargement du détail...</p>
      <p v-else-if="detailsError" role="alert">{{ detailsError }}</p>
      <pre v-else>{{ JSON.stringify(details, null, 2) }}</pre>
    </li>
  </li>
</template>
