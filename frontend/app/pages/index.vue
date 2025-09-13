<template>
  <nav class="bg-blue-600 text-white p-4 flex justify-between">
    <div class="font-bold text-xl">Dashmerde</div>
    <div class="space-x-4">
      <NuxtLink to="/" class="hover:underline">Dashboards</NuxtLink>
      <NuxtLink to="/dashboards/create" class="hover:underline">Ajouter Dashboard</NuxtLink>
    </div>
  </nav>

  <div class="p-4">
    <h1 class="text-2xl font-bold mb-4">Dashboards</h1>
    <div v-if="dashboards.length === 0">Aucun dashboard trouvé.</div>
    <ul v-else class="space-y-2">
      <li v-for="d in dashboards" :key="d.id" class="p-4 border rounded hover:shadow-lg transition relative">
        <NuxtLink :to="`/dashboards/${d.id}`" class="font-semibold text-blue-600 hover:underline">
          {{ d.name }}
        </NuxtLink>
        <p>{{ d.description }}</p>

        <div class="absolute bottom-2 right-2 flex space-x-2">
          <NuxtLink :to="`/dashboards/edit/${d.id}`" title="Modifier" class="text-orange-500 hover:text-orange-700">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2v-5M18.5 2.5l3 3-11 11H7v-3l11-11z" />
            </svg>
          </NuxtLink>

          <button @click="deleteThisDashboard(d.id)" title="Supprimer" class="text-red-500 hover:text-red-700">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
              <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M1 7h22m-5-4H6a1 1 0 00-1 1v2h12V4a1 1 0 00-1-1z" />
            </svg>
          </button>
        </div>

      </li>
    </ul>
  </div>
</template>

<script setup>
import { onMounted } from 'vue'
import { useDashboard } from '~/composables/useDashboard'
import { useRouter } from 'vue-router'

const router = useRouter()
const { dashboards, fetchDashboards, deleteDashboard } = useDashboard()

onMounted(async () => {
  await fetchDashboards()
})

const deleteThisDashboard = async (id) => {
  const confirmed = window.confirm("Êtes-vous sûr de vouloir supprimer ce dashboard ?")
  if (!confirmed) return

  try {
    await deleteDashboard(id)
    await fetchDashboards()
    alert("Dashboard supprimé avec succès !")
  } catch (err) {
    if (err.response && err.response.data && err.response.data.message) {
      alert(err.response.data.message)
    } else {
      alert("Erreur lors de la suppression du dashboard")
    }
  }
}
</script>
