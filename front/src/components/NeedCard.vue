<script setup>
defineProps({
  need: {
    type: Object,
    required: true,
  },
})

function valueOf(need, keys, fallback = '—') {
  const key = keys.find((k) => need?.[k] !== undefined && need?.[k] !== null && need?.[k] !== '')
  return key ? need[key] : fallback
}
</script>

<template>
  <tr class="need-row">
    <td>{{ valueOf(need, ['reference']) }}</td>
    <td>{{ valueOf(need, ['departement']) }}</td>
    <td>{{ valueOf(need, ['intitule_poste']) }}</td>
    <td>{{ valueOf(need, ['nb_postes']) }}</td>
    <td>{{ need.poste ? `${need.poste} (${need.salaire_min} - ${need.salaire_max})` : '-' }}</td>
    <td><span class="badge">{{ valueOf(need, ['statut']) }}</span></td>
  </tr>
</template>

<style scoped>
.need-row:hover {
  background: #f8fafc;
}

.badge {
  display: inline-block;
  padding: 0.3rem 0.6rem;
  border-radius: 999px;
  background: #e0f2fe;
  color: #0f172a;
  font-size: 0.75rem;
  font-weight: 600;
}
</style>