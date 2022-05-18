<template>
    <div v-if="errors">
        <div v-for="(v, k) in errors" :key="k">
            <p v-for="error in v" :key="error" class="text-sm text-red">
            {{ error }}
            </p>
        </div>
    </div>

    <form @submit.prevent="saveSchedule">
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" class="form-control" id="name" placeholder="Lorem ipsum dolor isit" v-model="form.name">
        </div>
        <div class="form-group">
            <label for="date_time">Schedule Date and Time</label>
            <input type="text" name="date_time" class="form-control" id="date_time" placeholder="Y-m-d H:i:s a" v-model="form.date_time">
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>

    </form>
</template>

<script>
import { reactive } from 'vue'
import useSchedules from '../../composables/schedules'

export default {
    setup() {

        const form = reactive({
            'name': '',
            'date_time': ''
        })

        const { errors, storeSchedule } = useSchedules()

        const saveSchedule = async() => {
            await storeSchedule({...form})
        }

        return {
            form,
            errors,
            saveSchedule
        }
    }
}
</script>