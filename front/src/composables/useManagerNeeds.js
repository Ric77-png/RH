import { computed, onMounted, ref } from 'vue'
import { apiFetch } from '../services/api'

function createEmptyForm() {
  return {
    reference: '',
    departement_id: '',
    intitule_poste: '',
    nb_postes: 1,
    type_contrat: 'CDI',
    motif: 'manque_effectif',
    niveau_experience: 'Junior',
    salaire_propose: '',
    date_prise_poste: '',
    missions: '',
    competences: '',
    urgence: 'normal',
  }
}

export function useManagerNeeds() {
  const needs = ref([])
  const departments = ref([])
  const loading = ref(false)
  const error = ref('')
  const form = ref(createEmptyForm())
  const selectedStatus = ref('all')

  const filteredNeeds = computed(() => {
    if (!needs.value) return []
    if (selectedStatus.value === 'all') return needs.value
    return needs.value.filter((need) => need.statut === selectedStatus.value)
  })

  async function loadDepartments() {
    try {
      const response = await apiFetch('/api/manager/departements')
      departments.value = Array.isArray(response?.data) ? response.data : []
    } catch {
      departments.value = []
    }
  }

  async function refresh() {
    loading.value = true
    error.value = ''

    try {
      const response = await apiFetch('/api/manager/needs')

      needs.value = Array.isArray(response)
        ? response
        : Array.isArray(response?.data)
          ? response.data
          : []
    } catch (err) {
      error.value = err instanceof Error
        ? err.message
        : 'Impossible de charger les besoins.'
    } finally {
      loading.value = false
    }
  }

  async function submitNeed(payloadFromForm) {
    error.value = ''

    try {
      const payload = {
        ...payloadFromForm,
        departement_id: Number(payloadFromForm.departement_id),
        nb_postes: Number(payloadFromForm.nb_postes),
        salaire_propose: Number(payloadFromForm.salaire_propose),
        is_draft: Boolean(payloadFromForm.is_draft),
      }

      await apiFetch('/api/manager/needs', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(payload),
      })

      form.value = createEmptyForm()
      await refresh()
      return true
    } catch (err) {
      error.value = err instanceof Error
        ? err.message
        : 'Erreur lors de l’ajout du besoin.'
      return false
    }
  }

  onMounted(async () => {
    await loadDepartments()
    await refresh()
  })

  return {
    needs,
    filteredNeeds,
    departments,
    loading,
    error,
    form,
    selectedStatus,
    refresh,
    submitNeed,
  }
}