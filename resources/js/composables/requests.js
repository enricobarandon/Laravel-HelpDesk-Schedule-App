import { ref } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'
import Swal from 'sweetalert2'

export default function useRequests() {
    const requests = ref([])
    const router = useRouter()
    const errors = ref('')
    
    const storeRequest = async (data) => {
        console.log(data)
        errors.value = '';
        try {
            let store = await axios.post('/api/requests/groups', data)
            console.log(store)
            if (store.status === 201) {
                Swal.fire({
                    title: "Request Posted!",
                    text: store.data.message,
                    icon: "success"
                }).then(function() {
                    window.location.href = '/requests'
                });
            } else {
                Swal.fire({
                    title: "Request Failed!",
                    text: store.data.message,
                    icon: "error"
                })
            }
        } catch (e) {
            if (e.response.status === 422) {
                errors.value = e.response.data.errors
            }
        }
    }

    return {
        requests,
        errors,
        storeRequest
    }
}