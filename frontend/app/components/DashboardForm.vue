<template>
  <form @submit.prevent="submit" class="space-y-4">
    <div>
      <label class="block font-bold mb-1">Name</label>
      <input v-model="form.name" class="border rounded w-full p-2" required />
    </div>

    <div>
      <label class="block font-bold mb-1">Description</label>
      <textarea v-model="form.description" class="border rounded w-full p-2"></textarea>
    </div>

    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
      {{ isEdit ? 'Update' : 'Create' }}
    </button>
  </form>
</template>

<script setup>
import { reactive, watch } from 'vue'
import { useDashboard } from '~/composables/useDashboard'

const props = defineProps({ dashboard: Object, isEdit: Boolean })
const emit = defineEmits(['saved'])
const { addDashboard, updateDashboard } = useDashboard()

const form = reactive({
  name: '',
  description: ''
})

watch(
  () => props.dashboard,
  (newVal) => {
    if (newVal) {
      form.name = newVal.name
      form.description = newVal.description
    }
  },
  { immediate: true } // exécute directement au montage si dashboard est déjà là
)

const submit = async () => {
  let saved
  if (props.isEdit) {
    saved = await updateDashboard(props.dashboard.id, form)
  } else {
    saved = await addDashboard(form)
  }
  emit('saved', saved)
}
</script>
