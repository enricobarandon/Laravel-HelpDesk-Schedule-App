<template>
    <div v-if="errors">
        <div v-for="(v, k) in errors" :key="k">
            <p v-for="error in v" :key="error" class="text-sm text-red">
            {{ error }}
            </p>
        </div>
    </div>

    <form @submit.prevent="saveSchedule">
        <a href='/schedules' class="btn btn-primary">Back to Schedule Management page</a>
        <div class="form-group">
            <label for="name">Name</label>
            <input type="text" name="name" class="form-control" id="name" placeholder="Description" v-model="schedule.name">
        </div>
        <div class="form-group">
            <label for="date_time">Schedule Date</label>
            <!-- <input type="text" name="date_time" class="form-control" id="date_time" placeholder="Y-m-d H:i:s a" v-model="schedule.date_time"> -->
            <Datepicker :format="formatDate" name="date_time" id="date_time" placeholder="Y-m-d" v-model="schedule.date_time" />
        </div>
        <div class="form-group">
            <label for="status">Status</label>
            <select class="form-control" name="status" v-model="schedule.status">
                <option selected value="active">Active</option>
                <option value="finished">Finished</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Submit</button>

    </form>
</template>

<script>
import { onMounted } from 'vue'
import useSchedules from '../../composables/schedules'
import Datepicker from '@vuepic/vue-datepicker'
import '@vuepic/vue-datepicker/dist/main.css'
import moment from 'moment'

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
    },
    components: {
        Datepicker
    },
    methods: {
        formatDate(date){
            return moment(date).format('YYYY-MM-DD');
        }
    }
}
</script>