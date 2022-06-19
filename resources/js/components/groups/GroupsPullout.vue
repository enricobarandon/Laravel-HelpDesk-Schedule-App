<template>
<div v-bind="$attrs">
        <table class="table table-bordered table-striped sm-global-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Group Name</th>
                    <th>Address</th>
                    <th>Group Type</th>
                    <th>Owner</th>
                    <th>Contact</th>
                    <th>Group Code</th>
                    <th>Site</th>
                    <th>Province</th>
                    <th>Guarantor</th>
                    <th>Operation Date</th>
                    <th>Pullout Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <template v-for="(item, itemKey) in filteredPulledOutGroups" :key="item.id">
                    <tr>
                        <td>{{ itemKey+1 }}</td>
                        <td>{{ item.name }}</td>
                        <td>{{ item.address }}</td>
                        <td>{{ item.group_type }}</td>
                        <td>{{ item.owner }}</td>
                        <td>{{ item.contact }}</td>
                        <td>{{ item.code }}</td>
                        <td  :class="item.site == 'wpc2040' ? 'td-blue' : 'td-red'">{{ item.site }}</td>
                        <td>{{ item.province_name }}</td>
                        <td>{{ item.guarantor }}</td>
                        <td>{{ format_date(item.operation_date) }}</td>
                        <td>{{ format_date(item.pullout_date) }}</td>
                        <td class="display-center">{{ item.status }}</td>
                    </tr>
                </template>
            </tbody>
        </table>
    </div>
</template>

<script>
import { onMounted } from 'vue'
import useGroups from '../../composables/groups'
import moment from 'moment'

export default {
    setup() {
        
        const { groups, getPulledOutGroups, filteredPulledOutGroups } = useGroups()

        onMounted(getPulledOutGroups)

        return {
            filteredPulledOutGroups
        }

    },
    methods: { 
      format_date(value){
         if (value) {
           return moment(String(value)).format('MMM DD, YYYY')
          }
      }
   }
}
</script>