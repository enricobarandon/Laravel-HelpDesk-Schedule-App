import { ref } from 'vue'
import axios from 'axios'

export default function useGroups () {
    const groups = ref([])
    const errors = ref('')
    const group = ref([])

    const getActiveGroups = async () => {
        let response = await axios.get('/api/groups/view/active')
        groups.value = response.data.data
    }

    const getDeactivatedGroups = async () => {
        let response = await axios.get('/api/groups/view/deactivated')
        groups.value = response.data.data
    }

    const getGroup = async (id) => {
        let response = await axios.get('/api/groups/' + id)
        group.value = response.data.data
    }

    const updateGroup = async (id) => {
        errors.value = '';
        try {
            await axios.put('/api/groups/' + id, group.value)
            // await router.push({name: 'groups.index'})
        } catch (e) {
            if (e.response.status === 422) {
                errors.value = e.response.data.errors
            }
        }
    }

    return {
        groups,
        getActiveGroups,
        getDeactivatedGroups,
        errors,
        group,
        getGroup,
        updateGroup
    }
}