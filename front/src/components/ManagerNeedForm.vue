<script setup>
import { computed, toRefs } from 'vue'

const props = defineProps({
  form: { type: Object, required: true },
  departments: { type: Array, required: true },
  postes: { type: Array, required: true },
})

const { form, departments, postes } = toRefs(props)
const emit = defineEmits(['submit', 'cancel'])

const selectedPoste = computed(() => {
  const id = Number(form.value.poste_id)
  return postes.value.find((poste) => Number(poste.id) === id) || null
})

function submitNeed(isDraft = false) {
  emit('submit', {
    ...form.value,
    is_draft: isDraft,
  })
}
</script>

<template>
  <section class="form-panel">
    <h3>Nouveau besoin</h3>

    <form class="need-form" @submit.prevent="submitNeed(false)">
      <div class="form-grid">
        <div>
          <label for="reference">Référence</label>
          <input id="reference" v-model="form.reference" type="text" required />
        </div>

        <div>
          <label for="intitule_poste">Intitulé du poste</label>
          <input id="intitule_poste" v-model="form.intitule_poste" type="text" required />
        </div>

        <div>
          <label for="departement_id">Département</label>
          <select id="departement_id" v-model="form.departement_id" required>
            <option value="">Sélectionner</option>
            <option v-for="dept in departments" :key="dept.id" :value="dept.id">
              {{ dept.nom }}
            </option>
          </select>
        </div>

        <div>
          <label for="poste_id">Poste</label>
          <select id="poste_id" v-model="form.poste_id" required>
            <option value="">Sélectionner</option>
            <option v-for="poste in postes" :key="poste.id" :value="poste.id">
              {{ poste.nom }} ({{ poste.salaire_min }} - {{ poste.salaire_max }})
            </option>
          </select>
        </div>

        <div>
          <label for="nb_postes">Nombre de postes</label>
          <input id="nb_postes" v-model.number="form.nb_postes" type="number" min="1" required />
        </div>

        <div>
          <label for="type_contrat">Type de contrat</label>
          <select id="type_contrat" v-model="form.type_contrat" required>
            <option value="CDI">CDI</option>
            <option value="CDD">CDD</option>
            <option value="CE">CE</option>
          </select>
        </div>

        <div>
          <label for="motif">Motif</label>
          <select id="motif" v-model="form.motif" required>
            <option value="manque_effectif">Manque effectif</option>
            <option value="remplacement">Remplacement</option>
            <option value="nouveau_projet">Nouveau projet</option>
          </select>
        </div>

        <div>
          <label for="niveau_experience">Niveau d’expérience</label>
          <select id="niveau_experience" v-model="form.niveau_experience" required>
            <option value="Junior">Junior</option>
            <option value="Confirmé">Confirmé</option>
            <option value="Senior">Senior</option>
            <option value="Expert">Expert</option>
          </select>
        </div>

        <div>
          <label for="date_prise_poste">Date de prise de poste</label>
          <input id="date_prise_poste" v-model="form.date_prise_poste" type="date" required />
        </div>

        <div>
          <label for="urgence">Urgence</label>
          <select id="urgence" v-model="form.urgence" required>
            <option value="normal">Normal</option>
            <option value="urgent">Urgent</option>
          </select>
        </div>

        <div class="full-width">
          <label for="missions">Missions</label>
          <textarea id="missions" v-model="form.missions" rows="3" required />
        </div>

        <div class="full-width">
          <label for="competences">Compétences</label>
          <textarea id="competences" v-model="form.competences" rows="3" required />
        </div>

        <div v-if="selectedPoste" class="full-width salary-box">
          <strong>Fourchette salariale :</strong>
          {{ selectedPoste.salaire_min }} - {{ selectedPoste.salaire_max }} MGA
        </div>
      </div>

      <div class="form-actions">
        <button type="button" @click="submitNeed(true)">Enregistrer en brouillon</button>
        <button type="button" @click="submitNeed(false)">Envoyer au RH</button>
        <button type="button" class="secondary-button" @click="$emit('cancel')">Annuler</button>
      </div>
    </form>
  </section>
</template>

<style scoped>
.form-panel {
  margin-bottom: 1.5rem;
  padding: 1.25rem;
  background: #f8fafc;
  border: 1px solid #e2e8f0;
  border-radius: 0.75rem;
}

h3 {
  margin-top: 0;
  margin-bottom: 1.25rem;
  color: #172033;
}

.need-form {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.form-grid {
  display: grid;
  grid-template-columns: repeat(2, minmax(0, 1fr));
  gap: 1rem;
}

.full-width {
  grid-column: 1 / -1;
}

label {
  display: block;
  margin-bottom: 0.4rem;
  color: #334155;
  font-weight: 600;
}

input,
select,
textarea {
  width: 100%;
  box-sizing: border-box;
  padding: 0.7rem 0.8rem;
  border: 1px solid #cbd5e1;
  border-radius: 0.5rem;
  font: inherit;
}

.salary-box {
  padding: 0.75rem 1rem;
  background: #e0f2fe;
  border: 1px solid #bae6fd;
  border-radius: 0.5rem;
  color: #0f172a;
}

.form-actions {
  display: flex;
  gap: 0.75rem;
}

.primary-button,
.secondary-button {
  padding: 0.75rem 1rem;
  border: 0;
  border-radius: 0.5rem;
  font-weight: 600;
  cursor: pointer;
}

.primary-button {
  color: white;
  background: #2563eb;
}

.secondary-button {
  color: #0f172a;
  background: #e2e8f0;
}

@media (max-width: 700px) {
  .form-grid {
    grid-template-columns: 1fr;
  }

  .form-actions {
    flex-direction: column;
  }
}
</style>