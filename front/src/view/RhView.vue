<script setup>
import NeedsList from '../components/NeedsList.vue'
import { useNeeds } from '../composables/useNeeds'

const { needs, loading, error, refresh } = useNeeds()
</script>

<template>
  <main class="rh-page">
    <div class="rh-page__heading">
      <div>
        <p class="eyebrow">Ressources humaines</p>
        <p>Notification:{{needs.length }}</p>
        <h2>Liste des besoins</h2>
        <p class="rh-page__intro">Consultez les besoins transmis au service RH.</p>
      </div>
      <button class="refresh-button" type="button" :disabled="loading" @click="refresh">
        {{ loading ? 'Actualisation...' : 'Actualiser' }}
      </button>
    </div>

    <p v-if="loading && !needs.length" class="loading-state" role="status">
      Chargement des besoins...
    </p>

    <div v-else-if="error" class="error-state" role="alert">
      <p>{{ error }}</p>
      <button type="button" @click="refresh">Réessayer</button>
    </div>

    <NeedsList v-else :needs="needs" />
  </main>
</template>
