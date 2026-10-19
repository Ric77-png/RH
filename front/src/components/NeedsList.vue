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
      <NeedCard v-for="need in needs" :key="need.id" :need="need" />
    </tbody>
  </table>
</template>


