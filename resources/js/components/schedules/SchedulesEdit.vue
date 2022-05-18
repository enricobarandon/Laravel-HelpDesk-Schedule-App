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
            <input type="text" name="name" class="form-control" id="name" placeholder="Lorem ipsum dolor isit" v-model="schedule.name">
        </div>
        <div class="form-group">
            <label for="date_time">Schedule Date and Time</label>
            <input type="text" name="date_time" class="form-control" id="date_time" placeholder="Y-m-d H:i:s a" v-model="schedule.date_time">
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>

    </form>
</template>

<script>
import { onMounted } from 'vue'
import useSchedules from '../../composables/schedules'

export default {
    props: {
        id: {
            required: true,
            type: String
        }
    },
    setup(props) {
        const { errors, schedule, getSchedule, updateSchedule } = useSchedules()

        onMounted(getSchedule(props.id))

        const saveSchedule = async () => {
            await updateSchedule(props.id)
        }

        return {
            errors,
            schedule,
            saveSchedule
        }
    }
}
</script>