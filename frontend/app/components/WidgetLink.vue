<template>
  <div class="relative min-h-[100px] border border-gray-300 rounded-lg p-3 bg-white shadow-sm">
    <div v-if="isEditing">
      <input v-model="label" class="border p-2 rounded w-full mb-2" placeholder="Label" />
      <input v-model="url" class="border p-2 rounded w-full mb-2" placeholder="URL" />
      <button @click="saveLink" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
        Sauvegarder
      </button>
    </div>

    <a v-else :href="widget.config.url" target="_blank" class="min-h-[100px] p-2 block w-full text-blue-600 font-semibold p-4 rounded-lg hover:underline hover:bg-blue-50 transition-colors duration-200">
      <div class="flex items-center space-x-3">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
        </svg>
        <span class="text-xl">{{ widget.config.label }}</span>
      </div>
    </a>

    <div class="absolute bottom-2 right-2 flex space-x-2">
      <button @click="toggleEdit" title="Modifier" class="text-orange-500 hover:text-orange-700">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2v-5M18.5 2.5l3 3-11 11H7v-3l11-11z" />
        </svg>
      </button>
      <button @click="deleteWidgetItem" title="Supprimer" class="text-red-500 hover:text-red-700">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
          <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M1 7h22m-5-4H6a1 1 0 00-1 1v2h12V4a1 1 0 00-1-1z" />
        </svg>
      </button>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useDashboard } from '~/composables/useDashboard'

const props = defineProps({ widget: Object, editable: Boolean, dashboardId: Number })
const emit = defineEmits(['widget-updated'])

const { updateWidget, deleteWidget } = useDashboard()
const label = ref(props.widget.config.label)
const url = ref(props.widget.config.url)
const isEditing = ref(false)

const toggleEdit = () => {
  isEditing.value = !isEditing.value
}

const saveLink = async () => {
  try {
    await updateWidget(props.widget.id, { config: { label: label.value, url: url.value } })
    props.widget.config.label = label.value
    props.widget.config.url = url.value
    emit('widget-updated')
    isEditing.value = false
  } catch (err) {
    alert("Erreur lors de la modification du widget")
  }
}

const deleteWidgetItem = async () => {
  const confirmed = window.confirm("Êtes-vous sûr de vouloir supprimer ce widget ?")
  if (!confirmed) return
  await deleteWidget(props.widget.id)
  emit('widget-updated')
}
</script>
