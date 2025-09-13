import { describe, it, expect, vi, beforeEach, afterEach } from 'vitest'
import { mount } from '@vue/test-utils'
import IndexPage from '../../app/pages/index.vue'

vi.mock('../../app/composables/useDashboard', () => ({
  useDashboard: vi.fn()
}))

import { useDashboard } from '../../app/composables/useDashboard'

const globalComponents = {
  NuxtLink: { template: '<a><slot /></a>' }
}

describe('IndexPage', () => {
  let fetchDashboardsMock, deleteDashboardMock

  beforeEach(() => {
    fetchDashboardsMock = vi.fn()
    deleteDashboardMock = vi.fn()

    useDashboard.mockReturnValue({
      dashboards: [{ id: 1, name: 'Dash1', description: 'Desc' }],
      fetchDashboards: fetchDashboardsMock,
      deleteDashboardById: deleteDashboardMock
    })
  })

  afterEach(() => {
    vi.restoreAllMocks()
  })

  it('affiche la liste des dashboards', async () => {
    const wrapper = mount(IndexPage, { global: { components: globalComponents } })
    expect(wrapper.text()).toContain('Dash1')
    expect(wrapper.text()).toContain('Desc')
  })


  it('annule la suppression si non confirmé', async () => {
    vi.spyOn(window, 'confirm').mockReturnValue(false)
    vi.spyOn(window, 'alert').mockImplementation(() => {})

    const wrapper = mount(IndexPage, { global: { components: globalComponents } })
    await wrapper.find('button').trigger('click')
    expect(deleteDashboardMock).not.toHaveBeenCalled()
  })
})
