import { ref } from 'vue'
import axios from 'axios'
import Swal from 'sweetalert2'
import { useRouter } from 'vue-router'

export default function useGroups () {
    const groups = ref([])
    const errors = ref('')
    const group = ref([])
    const filteredGroups = ref([])
    const filteredDeactivatedGroups = ref([])
    const filteredPulledOutGroups = ref([])
    const router = useRouter()

    const getActiveGroups = async () => {
        let response = await axios.get('/api/groups/view/active')
        groups.value = response.data.data
        filteredGroups.value = response.data.data
    }

    const getDeactivatedGroups = async () => {
        let response = await axios.get('/api/groups/view/deactivated')
        groups.value = response.data.data
        filteredDeactivatedGroups.value = response.data.data
    }

    const getPulledOutGroups = async () => {
        let response = await axios.get('/api/groups/view/pullout')
        groups.value = response.data.data
        filteredPulledOutGroups.value = response.data.data
    }

    const getGroup = async (id) => {
        let response = await axios.get('/api/groups/' + id)
        group.value = response.data.data
    }

    const updateGroup = async (id) => {
        errors.value = '';
        try {
            let update = await axios.put('/api/groups/' + id, group.value)
            if (update.status === 200) {
                Swal.fire({
                    title: "Group Updated!",
                    text: "",
                    icon: "success"
                }).then(function() {
                    if (update.data.data.is_active == 1) {
                        router.push({ name: 'groups.active' })
                    } else {
                        router.push({ name: 'groups.deactivated' })
                    }
                });
            }
            // await router.push({name: 'groups.index'})
        } catch (e) {
            if (e.response.status === 422) {
                errors.value = e.response.data.errors
            }
        }
    }

    // const filterActiveGroups = async () => {
    //     let response = await axios.get('/api/groups/view/active')
    //     groups.value = response.data.data
    // }

    return {
        groups,
        getActiveGroups,
        getDeactivatedGroups,
        errors,
        group,
        getGroup,
        updateGroup,
        // filterActiveGroups,
        filteredGroups,
        filteredDeactivatedGroups,
        getPulledOutGroups,
        filteredPulledOutGroups
    }
}