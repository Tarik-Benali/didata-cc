<template>
  <div class="max-w-xl mx-auto mt-10 p-6 bg-white rounded shadow">
    <h1 class="text-2xl font-bold mb-4">Edit Dashboard</h1>
    <DashboardForm :dashboard="dashboard" :isEdit="true" @saved="onSaved" />
  </div>
</template>

<script setup>
import DashboardForm from '~/components/DashboardForm.vue'
import { useRoute, useRouter } from 'vue-router'
import { onMounted } from 'vue'
import { useDashboard } from '~/composables/useDashboard'

const route = useRoute()
const router = useRouter()
const { dashboard, fetchDashboard } = useDashboard()

onMounted(async () => {
  await fetchDashboard(Number(route.params.id))
})

const onSaved = (updated) => {
  router.push(`/dashboards/${updated.id}`)
}
</script>
