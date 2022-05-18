import { ref } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'

export default function useSchedules() {
    const schedules = ref([])
    const router = useRouter()
    const errors = ref('')
    const schedule = ref([])

    const getSchedules = async () => {
        let response = await axios.get('/api/schedules');
        schedules.value = response.data.data;
    }

    const storeSchedule = async (data) => {
        errors.value = '';
        try {
            await axios.post('/api/schedules', data)
            await router.push({ name: 'schedules.index' })
        } catch (e) {
            if (e.response.status === 422) {
                errors.value = e.response.data.errors
            }
        }
    }

    const getSchedule = async (id) => {
        let response = await axios.get('/api/schedules/' + id)
        schedule.value = response.data.data
    }

    const updateSchedule = async(id) => {
        errors.value = '';
        try {
            await axios.put('/api/schedules/' + id, schedule.value)
            await router.push({name: 'schedules.index'})
        } catch (e) {
            if (e.response.status === 422) {
                errors.value = e.response.data.errors
            }
        }
    }

    return {
        schedules,
        getSchedules,
        errors,
        storeSchedule,
        schedule,
        getSchedule,
        updateSchedule
    }
}