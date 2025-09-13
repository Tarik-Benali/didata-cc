<template>
  <nav class="bg-blue-600 text-white p-4 flex justify-between">
    <div class="font-bold text-xl">Dashmerde</div>
    <div class="space-x-4">
      <NuxtLink to="/" class="hover:underline">Dashboards</NuxtLink>
      <NuxtLink to="/dashboards/create" class="hover:underline">Ajouter Dashboard</NuxtLink>
    </div>
  </nav>
  
  <div class="p-4 max-w-md mx-auto">
    <h1 class="text-2xl font-bold mb-4">Ajouter Dashboard</h1>
    <form @submit.prevent="createDashboard" class="space-y-4">
      <input v-model="name" placeholder="Nom" class="w-full border p-2 rounded"/>
      <textarea v-model="description" placeholder="Description" class="w-full border p-2 rounded"></textarea>
      <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
        Créer
      </button>
    </form>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useDashboard } from '~/composables/useDashboard'

const { addDashboard } = useDashboard()
const router = useRouter()

const name = ref('')
const description = ref('')

const createDashboard = async () => {
  const newDashboard = await addDashboard({ name: name.value, description: description.value })
  router.push(`/dashboards/${newDashboard.id}`)
}
</script>