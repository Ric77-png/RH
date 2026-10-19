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
  <section v-if="needs.length" class="needs-list" aria-label="Liste des besoins">
    <NeedCard v-for="(need, index) in needs" :key="need.id ?? index" :need="need" />
  </section>

  <p v-else class="empty-state">Aucun besoin à afficher.</p>
</template>
