<script setup>
import { computed, reactive, ref } from 'vue'
import { useRoute, RouterLink } from 'vue-router'
import { submitApplication } from '../services/api'

const route = useRoute()
const announcementReference = computed(() => {
  const reference = route.query.reference
  return typeof reference === 'string' ? reference : ''
})
const announcementId = computed(() => {
  const id = Number(route.query.announcementId)
  return Number.isInteger(id) && id > 0 ? id : null
})

const form = reactive({
  nom: '',
  prenom: '',
  telephone: '',
  email: '',
  curriculumVitae: null,
  lettreMotivation: null,
})
const submitted = ref(false)
const fileError = ref('')
const submitting = ref(false)
const submitError = ref('')

function updateFile(field, event) {
  const file = event.target.files?.[0] || null
  const isPdf = file && (file.type === 'application/pdf' || file.name.toLowerCase().endsWith('.pdf'))

  if (file && !isPdf) {
    form[field] = null
    event.target.value = ''
    fileError.value = 'Seuls les fichiers PDF sont acceptés.'
    return
  }

  fileError.value = ''
  form[field] = file
}

async function submitApplicationForm() {
  if (!window.confirm('Confirmez-vous l’envoi définitif de votre candidature ?')) {
    return
  }

  submitError.value = ''
  submitting.value = true

  const applicationData = new FormData()
  applicationData.append('annonce_id', String(announcementId.value))
  applicationData.append('nom', form.nom)
  applicationData.append('prenom', form.prenom)
  applicationData.append('telephone', form.telephone)
  applicationData.append('email', form.email)
  applicationData.append('curriculumVitae', form.curriculumVitae)
  applicationData.append('lettreMotivation', form.lettreMotivation)

  try {
    await submitApplication(applicationData)
    submitted.value = true
  } catch (cause) {
    submitError.value = cause instanceof Error
      ? cause.message
      : 'Impossible d’envoyer la candidature.'
  } finally {
    submitting.value = false
  }
}
</script>

<template>
  <main class="application">
    <p class="eyebrow">Candidature</p>
    <h1>Postuler à une annonce</h1>

    <p v-if="announcementReference" class="reference">
      Référence de l’annonce :
      <strong>{{ announcementReference }}</strong>
    </p>
    <p v-else class="error-state" role="alert">
      La référence de l’annonce est introuvable.
    </p>

    <p v-if="announcementReference && !announcementId" class="error-state" role="alert">
      L’identifiant de l’annonce est introuvable. Veuillez revenir à la liste des annonces.
    </p>

    <form
      v-if="announcementReference && announcementId && !submitted"
      class="application-form"
      @submit.prevent="submitApplicationForm"
    >
      <div class="form-grid">
        <label>
          Nom
          <input v-model.trim="form.nom" type="text" name="nom" autocomplete="family-name" required />
        </label>

        <label>
          Prénom
          <input v-model.trim="form.prenom" type="text" name="prenom" autocomplete="given-name" required />
        </label>

        <label>
          Téléphone
          <input
            v-model.trim="form.telephone"
            type="tel"
            name="telephone"
            autocomplete="tel"
            required
          />
        </label>

        <label>
          E-mail
          <input v-model.trim="form.email" type="email" name="email" autocomplete="email" required />
        </label>

        <label>
          Curriculum Vitae (PDF)
          <input
            type="file"
            name="curriculumVitae"
            accept=".pdf,application/pdf"
            required
            @change="updateFile('curriculumVitae', $event)"
          />
        </label>

        <label>
          Lettre de motivation (PDF)
          <input
            type="file"
            name="lettreMotivation"
            accept=".pdf,application/pdf"
            required
            @change="updateFile('lettreMotivation', $event)"
          />
        </label>
      </div>

      <p v-if="fileError || submitError" class="error-state" role="alert">
        {{ fileError || submitError }}
      </p>
      <button type="submit" :disabled="submitting">
        {{ submitting ? 'Envoi en cours...' : 'Envoyer ma candidature' }}
      </button>
    </form>

    <p v-if="submitted" class="success-state" role="status">
      Votre candidature a été envoyée avec succès. Vous ne pouvez pas l’envoyer une seconde fois.
    </p>

    <RouterLink class="back-link" to="/">Retour aux annonces</RouterLink>
  </main>
</template>

<style scoped>
.application {
  width: min(100%, 700px);
  margin: 0 auto;
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

.application-form {
  margin: 1.5rem 0;
  padding: 1.5rem;
  border: 1px solid #dbe3ef;
  border-radius: 0.5rem;
  background: #fff;
}

.form-grid {
  display: grid;
  grid-template-columns: repeat(2, minmax(0, 1fr));
  gap: 1rem;
}

label {
  display: flex;
  flex-direction: column;
  gap: 0.4rem;
  color: #172033;
  font-weight: 600;
}

input {
  box-sizing: border-box;
  width: 100%;
  padding: 0.7rem;
  border: 1px solid #cbd5e1;
  border-radius: 0.4rem;
  font: inherit;
}

button {
  margin-top: 1.5rem;
  border: 0;
  border-radius: 0.5rem;
  padding: 0.7rem 1rem;
  color: #fff;
  background: #2563eb;
  cursor: pointer;
  font: inherit;
  font-weight: 600;
}

button:disabled {
  cursor: wait;
  opacity: 0.65;
}

.reference,
.error-state,
.success-state {
  margin: 1.5rem 0;
  padding: 1rem;
  border-radius: 0.5rem;
}

.reference {
  background: #eff6ff;
  color: #1e3a8a;
}

.error-state {
  border: 1px solid #fecaca;
  color: #991b1b;
  background: #fef2f2;
}

.success-state {
  color: #166534;
  background: #f0fdf4;
}

.back-link {
  color: #2563eb;
  font-weight: 600;
}

@media (max-width: 600px) {
  .form-grid {
    grid-template-columns: 1fr;
  }
}
</style>
