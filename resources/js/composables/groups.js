import { ref } from 'vue'
import axios from 'axios'

export default function useGroups () {
    const groups = ref([])

    const getActiveGroups = async () => {
        let response = await axios.get('/api/groups/active')
        groups.value = response.data.data
    }

    const getDeactivatedGroups = async () => {
        let response = await axios.get('/api/groups/deactivated')
        groups.value = response.data.data
    }

    return {
        groups,
        getActiveGroups,
        getDeactivatedGroups
    }
}