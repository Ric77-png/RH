<script setup>
import AnnouncementCard from './AnnouncementCard.vue'

defineProps({
  announcements: {
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
      <AnnouncementCard
        v-for="announcement in announcements"
        :key="announcement.reference"
        :announcement="announcement"
      />
    </tbody>
  </table>
</template>
