import { onMounted, toRefs } from 'vue'
import { fetchNeeds, useNeedsStore } from '../stores/needs'

export function useNeeds() {
  const store = useNeedsStore()

  onMounted(() => {
    if (!store.needs.length && !store.loading) {
      void fetchNeeds().catch(() => undefined)
    }
  })

  return {
    ...toRefs(store),
    refresh: fetchNeeds,
  }
};