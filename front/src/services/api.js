
const apiBaseUrl = (import.meta.env.VITE_API_BASE_URL || '').replace(/\/$/, '')

export class ApiError extends Error {
  constructor(message, status) {
    super(message)
    this.name = 'ApiError'
    this.status = status
  }
}

export async function apiFetch(path, options = {}) {
  const response = await fetch(
    `${apiBaseUrl}/${path.replace(/^\//, '')}`,
    {
      ...options,
      credentials: 'include',
      headers: {
        Accept: 'application/json',
        ...options.headers,
      },
    },
  )

  if (!response.ok) {
    const message = await response.text()
    console.error('Erreur API :', response.status, message)

    throw new ApiError(
      `Erreur HTTP ${response.status} : ${message}`,
      response.status,
    )
  }

  const contentType = response.headers.get('content-type') || ''

  if (!contentType.includes('application/json')) {
    throw new ApiError(
      'Le serveur a renvoyé une réponse qui n’est pas au format JSON.',
      response.status,
    )
  }

  return response.json()
}

export function getNeeds() {
  return apiFetch('/api/rh/needs')
}

export function getNeedDetails(reference) {
  return apiFetch(
    `/api/rh/needs/${encodeURIComponent(reference)}`
  )
}
