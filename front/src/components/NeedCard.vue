<script setup>
defineProps({
  need: {
    type: Object,
    required: true,
  },
})

function valueOf(need, keys, fallback = 'Non renseigné') {
  const key = keys.find((candidate) => need[candidate] !== undefined && need[candidate] !== null)
  return key ? need[key] : fallback
}
</script>

<template>
  <article class="need-card">
    <div class="need-card__header">
      <h3>{{ valueOf(need, ['titre', 'title', 'poste', 'intitule'], 'Besoin sans titre') }}</h3>
      <span class="need-card__status">
        {{ valueOf(need, ['statut', 'status'], 'En attente') }}
      </span>
    </div>

    <p class="need-card__description">
      {{ valueOf(need, ['description', 'details', 'motif'], 'Aucune description disponible.') }}
    </p>

    <dl class="need-card__details">
      <div>
        <dt>Service</dt>
        <dd>{{ valueOf(need, ['service', 'departement', 'departements']) }}</dd>
      </div>
      <div>
        <dt>Poste</dt>
        <dd>{{ valueOf(need, ['intitule_poste']) }}</dd>
      </div>
      <div>
        <dt>Nombre de postes</dt>
        <dd>{{ valueOf(need, ['nombre_postes', 'nombrePostes', 'quantity', 'quantite','nb_postes']) }}</dd>
      </div>
      <div>
        <dt>Date de création</dt>
        <dd>{{ valueOf(need, ['created_at', 'createdAt', 'date_creation']) }}</dd>
      </div>
    </dl>
  </article>
</template>
