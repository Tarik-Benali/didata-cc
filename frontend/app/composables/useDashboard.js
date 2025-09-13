// frontend/composables/useDashboard.js
import { ref } from 'vue'
import axios from 'axios'

const api = axios.create({
  baseURL: 'http://localhost:8000/api/v1'
})

export function useDashboard() {
  const dashboards = ref([])
  const dashboard = ref(null)
  const versions = ref([])

  const fetchDashboards = async () => {
    const res = await api.get('/dashboards')
    dashboards.value = res.data
  }

  const fetchDashboard = async (id) => {
    const res = await api.get(`/dashboards/${id}`)
    dashboard.value = res.data
  }

  const addDashboard = async (data) => {
    const res = await api.post('/dashboards', data)
    dashboards.value.push(res.data)
    return res.data
  }

  const updateDashboard = async (id, data) => {
    const res = await api.put(`/dashboards/${id}`, data)
    const index = dashboards.value.findIndex(d => d.id === id)
    if(index !== -1) dashboards.value[index] = res.data
    return res.data
  }

  const deleteDashboard = async (id) => {
    await api.delete(`/dashboards/${id}`)
    dashboards.value = dashboards.value.filter(d => d.id !== id)
  }

  const addWidget = async (dashboardId, type, config) => {
    const res = await api.post(`/dashboards/${dashboardId}/widgets`, { type, config })
    return res.data
  }

  const updateWidget = async (widgetId, data) => {
    const res = await api.put(`/widgets/${widgetId}`, data)
    return res.data
  }

  const deleteWidget = async (widgetId) => {
    await api.delete(`/widgets/${widgetId}`)
  }

  const fetchVersions = async (dashboardId) => {
    const res = await api.get(`/dashboards/${dashboardId}/versions`)
    versions.value = res.data
  }

  const rollback = async (dashboardId, versionNumber) => {
    const res = await api.post(`/dashboards/${dashboardId}/rollback/${versionNumber}`)
    dashboard.value = res.data
  }

  return {
    dashboards,
    dashboard,
    versions,
    fetchDashboards,
    fetchDashboard,
    addDashboard,
    updateDashboard,
    deleteDashboard,
    addWidget,
    updateWidget,
    deleteWidget,
    fetchVersions,
    rollback
  }
}
