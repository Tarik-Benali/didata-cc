<template>
  <nav class="bg-blue-600 text-white p-4 flex justify-between">
    <div class="font-bold text-xl">Dashmerde</div>
    <div class="space-x-4">
      <NuxtLink to="/" class="hover:underline">Dashboards</NuxtLink>
      <NuxtLink to="/dashboards/create" class="hover:underline">Ajouter Dashboard</NuxtLink>
    </div>
  </nav>
  
  <div class="min-h-screen bg-gray-100 p-8 md:p-12 lg:p-16">
    <header class="mb-10 text-center">
      <h1 class="text-4xl md:text-5xl font-extrabold text-gray-900 leading-tight">{{ dashboard?.name }}</h1>
      <h5>{{ dashboard?.description }}</h5>
    </header>
    
    <!-- Messages succès / erreur -->
    <div v-if="successMessage" class="fixed top-6 right-6 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
      {{ successMessage }}
    </div>

    <div v-if="errorMessage" class="fixed top-6 right-6 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50">
      {{ errorMessage }}
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8 mb-12">
      <div
        v-for="widget in dashboard?.widgets"
        :key="widget.id"
        class="bg-white rounded-3xl shadow-xl p-6 relative transition-all duration-300 hover:shadow-2xl hover:scale-[1.02]"
      >
        <WidgetLink
          v-if="widget.type === 'link'"
          :widget="widget"
          :editable="true"
          :dashboardId="dashboard.id"
          @widget-updated="() => showSuccess('Widget modifié avec succès !')"
        />

        <WidgetText
          v-if="widget.type === 'text'"
          :widget="widget"
          :editable="true"
          :dashboardId="dashboard.id"
          @widget-updated="() => showSuccess('Widget modifié avec succès !')"
        />        
      </div>
    </div>

    <div class="bg-white rounded-3xl shadow-xl p-8">
      <h2 class="text-2xl font-bold mb-6 text-gray-800">Historique des versions</h2>
      <ul class="space-y-4">
        <li v-for="v in versions" :key="v.id" class="flex justify-between items-center p-4 border border-gray-200 rounded-xl hover:bg-gray-50 transition-colors duration-200">
          <div class="flex items-center space-x-3">
            <span class="text-sm font-medium text-gray-500">Version</span>
            <span class="text-lg font-semibold text-gray-800">#{{ v.version_number }}</span>
          </div>
          <button
            @click="rollbackVersion(v.version_number)"
            class="bg-blue-600 text-white px-5 py-2 rounded-lg font-semibold shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transition-all duration-200"
          >
            Rollback
          </button>
        </li>
      </ul>
    </div>

    <button
      @click="isModalOpen = true"
      class="fixed bottom-6 right-6 bg-blue-600 text-white w-16 h-16 rounded-full shadow-lg text-3xl flex items-center justify-center hover:bg-blue-700 transition-all"
      title="Ajouter un widget"
    >
      +
    </button>

    <div v-if="isModalOpen" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
      <div class="bg-white p-6 rounded shadow-lg w-96 relative">
        <button @click="isModalOpen = false" class="absolute top-2 right-2 text-gray-500 hover:text-gray-700 text-xl">&times;</button>
        <h2 class="text-xl font-bold mb-4">Ajouter un widget</h2>

        <div class="mb-4">
          <label class="block mb-1">Type</label>
          <select v-model="newWidgetType" class="border p-2 rounded w-full">
            <option value="text">Text</option>
            <option value="link">Link</option>
          </select>
        </div>

        <div v-if="newWidgetType === 'text'" class="mb-4">
          <label class="block mb-1">Texte</label>
          <input type="text" v-model="newWidgetText" class="border p-2 rounded w-full" placeholder="Texte du widget" />
        </div>

        <div v-if="newWidgetType === 'link'" class="mb-4 space-y-2">
          <div>
            <label class="block mb-1">Label</label>
            <input type="text" v-model="newWidgetLabel" class="border p-2 rounded w-full" placeholder="Label du lien" />
          </div>
          <div>
            <label class="block mb-1">URL</label>
            <input type="text" v-model="newWidgetUrl" class="border p-2 rounded w-full" placeholder="URL du lien" />
          </div>
        </div>

        <button
          @click="submitNewWidget"
          class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition-all w-full"
        >
          Ajouter
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import { useDashboard } from '~/composables/useDashboard'
import WidgetLink from '~/components/WidgetLink.vue'
import WidgetText from '~/components/WidgetText.vue'

const route = useRoute()
const { dashboard, versions, fetchDashboard, fetchVersions, rollback, addWidget, deleteWidget } = useDashboard()
const dashboardId = Number(route.params.id)

const isModalOpen = ref(false)
const newWidgetType = ref('text')
const newWidgetText = ref('')
const newWidgetLabel = ref('')
const newWidgetUrl = ref('')

const successMessage = ref('')
const errorMessage = ref('')

const showSuccess = (msg) => {
  successMessage.value = msg
  setTimeout(() => successMessage.value = '', 3000)
}

const showError = (msg) => {
  errorMessage.value = msg
  setTimeout(() => errorMessage.value = '', 3000)
}

onMounted(async () => {
  await fetchDashboard(dashboardId)
  await fetchVersions(dashboardId)
})

const rollbackVersion = async (versionNumber) => {
  await rollback(dashboardId, versionNumber)
}

const submitNewWidget = async () => {
  try {
    let config
    if (newWidgetType.value === 'text') {
      if (!newWidgetText.value) return showError("Le texte est requis")
      config = { text: newWidgetText.value }
    } else {
      if (!newWidgetLabel.value || !newWidgetUrl.value) return showError("Label et URL requis")
      config = { label: newWidgetLabel.value, url: newWidgetUrl.value }
    }

    await addWidget(dashboardId, newWidgetType.value, config)
    await fetchDashboard(dashboardId)

    showSuccess("Widget ajouté avec succès !")

    newWidgetType.value = 'text'
    newWidgetText.value = ''
    newWidgetLabel.value = ''
    newWidgetUrl.value = ''
    isModalOpen.value = false
  } catch (err) {
    if (err.response && err.response.data && err.response.data.message) {
      showError(err.response.data.message)
    } else {
      showError("Erreur lors de l'ajout du widget")
    }
  }
}

const deleteWidgetItem = async (id) => {
  const confirmed = window.confirm("Êtes-vous sûr de vouloir supprimer ce widget ?")
  if (!confirmed) return
  try {
    await deleteWidget(id)
    await fetchDashboard(dashboardId)
    showSuccess("Widget supprimé avec succès !")
  } catch (err) {
    if (err.response && err.response.data && err.response.data.message) {
      showError(err.response.data.message)
    } else {
      showError("Erreur lors de l'ajout du widget")
    }
  }
}
</script>
