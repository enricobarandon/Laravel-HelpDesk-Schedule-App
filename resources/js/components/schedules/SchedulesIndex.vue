<template>
    <router-link :to="{ name: 'schedules.create' }"  class="btn btn-primary float-right btn-create" v-if="user_type == 1"><i class="fas fa-plus"></i> Create Schedule</router-link>
    <!-- <a href="/schedules/create" class="btn btn-primary float-right"><i class="fas fa-plus"></i> Create Schedule</a> -->
    <table class="table table-bordered table-striped global-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Schedule Name</th>
                <th>Schedule Date</th>
                <th>Created At</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <template v-for="item in schedules" :key="item.id">
                <tr>
                    <td>{{ item.id }}</td>
                    <td>{{ item.name }}</td>
                    <td>{{ format_date(item.date_time) }}</td>
                    <td>{{ format_datetime(item.created_at) }}</td>
                    <td class="display-center">
                        <router-link :to="{ name: 'schedules.edit', params: {id: item.id} }" class="btn btn-primary btn-edit" v-if="user_type == 1">
                            <i class="fas fa-cog"></i>Edit
                        </router-link>
                        <a class="btn btn-secondary btn-manage" :href="'/schedules/manage/' + item.id"><i class="fas fa-cog"></i> Manage Schedule</a>
                        <a class="btn btn-info btn-view" :href="'/schedules/view/' + item.id"><i class="fas fa-eye"></i> View Schedule</a>
                        <!-- <button type="button" class="btn btn-info"><i class="fas fa-eye"></i> View Schedule</button> -->
                    </td>
                </tr>
            </template>
        </tbody>
    </table>
</template>

<script>
import { onMounted, inject } from "vue"
import useSchedules from "../../composables/schedules"
import moment from 'moment'

export default {
    props: {
        user: Object
    },
    setup(props) {
        const user_type = props.user.user_type_id

        const { schedules, getSchedules } = useSchedules()

        onMounted(getSchedules)

        return {
            schedules,
            user_type
        }
    },
    methods: { 
      format_date(value){
         if (value) {
           return moment(String(value)).format('MMMM DD, YYYY')
          }
      },
      format_datetime(value){
         if (value) {
           return moment(String(value)).format('MMMM DD, YYYY hh:mm a')
          }
      }
   }
}
</script>