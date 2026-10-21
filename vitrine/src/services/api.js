const apiBaseUrl = (import.meta.env.VITE_API_BASE_URL || 'http://localhost:5174').replace(/\/$/, '')

export async function getPublishedNeeds() {
  const response = await fetch(`${apiBaseUrl}/api/rh/needs/annonces`, {
    headers: {
      Accept: 'application/json',
    },
  })

  if (!response.ok) {
    throw new Error(`La requête a échoué (${response.status} ${response.statusText})`)
  }

  const payload = await response.json()
  if (!Array.isArray(payload?.data)) {
    throw new Error('Le format des annonces est invalide.')
  }

  return payload.data
}

export async function getAnnouncementDetails(reference) {
  const response = await fetch(`${apiBaseUrl}/api/rh/needs/${encodeURIComponent(reference)}`, {
    headers: {
      Accept: 'application/json',
    },
  })

  if (!response.ok) {
    throw new Error(`La requête a échoué (${response.status} ${response.statusText})`)
  }

  return response.json()
}
