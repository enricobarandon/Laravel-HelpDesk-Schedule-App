<template>
    <router-link :to="{ name: 'schedules.create' }"  class="btn btn-primary float-right"><i class="fas fa-plus"></i> Create Schedule</router-link>
        <table class="table table-bordered table-striped global-table">
            <thead>
                <tr>
                    <th>Schedule Name</th>
                    <th>Schedule Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <template v-for="item in schedules" :key="item.id">
                    <tr>
                        <td>{{ item.name }}</td>
                        <td>{{ item.date_time }}</td>
                        <td class="display-center">
                            <router-link :to="{ name: 'schedules.edit', params: {id: item.id} }"  class="btn btn-primary">
                                <i class="fas fa-cog"></i>Edit
                            </router-link>
                            <button type="button" class="btn btn-secondary"><i class="fas fa-cog"></i> Manage Schedule</button>
                            <button type="button" class="btn btn-info"><i class="fas fa-eye"></i> View Schedule</button>
                        </td>
                    </tr>
                </template>
            </tbody>
        </table>
</template>

<script>
import { onMounted } from "vue"
import useSchedules from "../../composables/schedules"

export default {
    setup() {

        const { schedules, getSchedules } = useSchedules()

        onMounted(getSchedules)

        return {
            schedules
        }
    }
}
</script>