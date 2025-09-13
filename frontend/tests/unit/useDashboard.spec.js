import { describe, it, expect, beforeEach, afterEach } from 'vitest'
import { useDashboard } from '../../app/composables/useDashboard'
import MockAdapter from 'axios-mock-adapter'
import api from '../../app/composables/useDashboard' // ⚠️ il faut exporter `api` dans ton fichier useDashboard.js

let mock
let composable

describe('useDashboard composable', () => {
  beforeEach(() => {
    // brancher axios-mock-adapter sur l'instance axios utilisée par le composable
    mock = new MockAdapter(api)
    composable = useDashboard()
  })

  afterEach(() => {
    mock.reset()
    mock.restore()
  })

  it('fetchDashboards récupère la liste', async () => {
    const data = [{ id: 1, name: 'Dash1' }]
    mock.onGet('/dashboards').reply(200, data)

    await composable.fetchDashboards()
    expect(composable.dashboards.value).toEqual(data)
  })

  it('fetchDashboard récupère un dashboard', async () => {
    const data = { id: 2, name: 'SingleDash' }
    mock.onGet('/dashboards/2').reply(200, data)

    await composable.fetchDashboard(2)
    expect(composable.dashboard.value).toEqual(data)
  })

  it('addDashboard ajoute un dashboard', async () => {
    const newDash = { id: 3, name: 'Dash3' }
    mock.onPost('/dashboards').reply(201, newDash)

    const res = await composable.addDashboard({ name: 'Dash3' })
    expect(res).toEqual(newDash)
    expect(composable.dashboards.value).toContainEqual(newDash)
  })

  it('updateDashboard met à jour un dashboard', async () => {
    composable.dashboards.value = [{ id: 4, name: 'OldName' }]
    const updated = { id: 4, name: 'NewName' }
    mock.onPut('/dashboards/4').reply(200, updated)

    const res = await composable.updateDashboard(4, { name: 'NewName' })
    expect(res).toEqual(updated)
    expect(composable.dashboards.value[0]).toEqual(updated)
  })

  it('deleteDashboard supprime un dashboard', async () => {
    composable.dashboards.value = [{ id: 5, name: 'ToDelete' }]
    mock.onDelete('/dashboards/5').reply(204)

    await composable.deleteDashboard(5)
    expect(composable.dashboards.value).toEqual([])
  })

  it('addWidget ajoute un widget', async () => {
    const widget = { id: 10, type: 'text', config: { content: 'Hello' } }
    mock.onPost('/dashboards/1/widgets').reply(201, widget)

    const res = await composable.addWidget(1, 'text', { content: 'Hello' })
    expect(res).toEqual(widget)
  })

  it('updateWidget met à jour un widget', async () => {
    const widget = { id: 20, type: 'link', config: { url: 'https://test' } }
    mock.onPut('/widgets/20').reply(200, widget)

    const res = await composable.updateWidget(20, { config: { url: 'https://test' } })
    expect(res).toEqual(widget)
  })

  it('deleteWidget supprime un widget', async () => {
    mock.onDelete('/widgets/30').reply(204)

    await composable.deleteWidget(30)
    expect(true).toBe(true)
  })

  it('fetchVersions récupère les versions', async () => {
    const versions = [{ id: 1, number: 1 }, { id: 2, number: 2 }]
    mock.onGet('/dashboards/1/versions').reply(200, versions)

    await composable.fetchVersions(1)
    expect(composable.versions.value).toEqual(versions)
  })

  it('rollback restaure une version', async () => {
    const rolled = { id: 1, name: 'RolledBack' }
    mock.onPost('/dashboards/1/rollback/2').reply(200, rolled)

    await composable.rollback(1, 2)
    expect(composable.dashboard.value).toEqual(rolled)
  })
})
