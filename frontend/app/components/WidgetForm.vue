<template>
  <form @submit.prevent="submit" class="space-y-4">
    <div>
      <label class="block font-bold mb-1">Type</label>
      <select v-model="form.type" class="border rounded w-full p-2">
        <option value="link">Link</option>
        <option value="text">Text</option>
      </select>
    </div>

    <div v-if="form.type === 'link'" class="space-y-2">
      <input v-model="form.config.url" placeholder="URL" class="border rounded w-full p-2" required />
      <input v-model="form.config.label" placeholder="Label" class="border rounded w-full p-2" required />
    </div>

    <div v-if="form.type === 'text'" class="space-y-2">
      <textarea v-model="form.config.text" placeholder="Text" class="border rounded w-full p-2" required />
    </div>

    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
      Add Widget
    </button>
  </form>
</template>

<script setup>
import { reactive } from 'vue'
import { useDashboard } from '~/composables/useDashboard'

const props = defineProps({ dashboardId: Number })
const emit = defineEmits(['added'])
const { addWidget } = useDashboard()

const form = reactive({
  type: 'link',
  config: {}
})

const submit = async () => {
  const saved = await addWidget(props.dashboardId, form.type, form.config)
  emit('added', saved)
  form.type = 'link'
  form.config = {}
}
</script>
