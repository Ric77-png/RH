import { reactive } from 'vue'
import { getNeeds } from '../services/api'

const state = reactive({
  needs: [],
  loading: false,
  error: null,
})

function extractNeeds(payload) {
  if (Array.isArray(payload)) {
    return payload
  }

  if (Array.isArray(payload?.data)) {
    return payload.data
  }

  if (Array.isArray(payload?.besoins)) {
    return payload.besoins
  }

  throw new Error('Le format de la liste des besoins est invalide.')
}

export async function fetchNeeds() {
  state.loading = true
  state.error = null

  try {
    state.needs = extractNeeds(await getNeeds())
  } catch (error) {
    state.error = error instanceof Error
      ? error.message
      : 'Impossible de charger les besoins.'
    throw error
  } finally {
    state.loading = false
  }
}

export function useNeedsStore() {
  return state
}
