<script setup>
import NeedCard from './NeedCard.vue'

defineProps({
  needs: {
    type: Array,
    required: true,
  },
})
</script>

<template>
  <table class="needs-table">
    <thead>
      <tr>
        <th>Réf.</th>
        <th>Département</th>
        <th>Poste</th>
        <th>Nb</th>
        <th>Salaire proposé</th>
        <th>Statut</th>
      </tr>
    </thead>

    <tbody>
      <NeedCard
        v-for="need in needs"
        :key="need.id ?? need.reference"
        :need="need"
      />

      <tr v-if="!needs.length">
        <td colspan="6" class="empty-state">
          Aucun besoin trouvé.
        </td>
      </tr>
    </tbody>
  </table>
</template>

<style scoped>
.needs-table {
  width: 100%;
  border-collapse: collapse;
  background: #fff;
  border: 1px solid #e2e8f0;
  border-radius: 0.75rem;
  overflow: hidden;
}

th,
td {
  padding: 0.85rem 1rem;
  text-align: left;
  border-bottom: 1px solid #e2e8f0;
}

thead {
  background: #f8fafc;
}

.empty-state {
  text-align: center;
  color: #64748b;
  padding: 1rem;
}
</style>