<template>
  <transition name="fade-slide" mode="out-in">
    <div class="relative min-h-[100px] border border-gray-300 rounded-lg p-3 bg-white shadow-sm">
      <textarea v-if="isEditing" v-model="text" class="w-full h-[100px] border border-gray-300 rounded-lg p-3 text-lg resize-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors duration-200"
      ></textarea>
      <div v-else class="min-h-[100px] p-2">
        <p class="text-gray-700 text-base leading-relaxed whitespace-pre-wrap">{{ widget.config.text }}</p>
      </div>

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

      <div v-if="isEditing" class="mt-4 text-left">
        <button @click="saveText" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition-all" >
          Sauvegarder
        </button>
      </div>


    </div>
  </transition>
</template>

<script setup>
import { ref } from 'vue'
import { useDashboard } from '~/composables/useDashboard'

const props = defineProps({ widget: Object, editable: Boolean, dashboardId: Number })
const emit = defineEmits(['widget-updated'])

const text = ref(props.widget.config.text)
const isEditing = ref(false)

const { updateWidget, deleteWidget } = useDashboard()

const toggleEdit = () => {
  isEditing.value = !isEditing.value
}

const saveText = async () => {
  try {
    await updateWidget(props.widget.id, { config: { text: text.value } })
    props.widget.config.text = text.value
    emit('widget-updated', { text: text.value })
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
