<template>
<div v-bind="$attrs">
        <table class="table table-bordered table-striped global-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Group Name</th>
                    <th>Group Type</th>
                    <th>Owner</th>
                    <th>Contact</th>
                    <th>Group Code</th>
                    <th>Site</th>
                    <th>Province</th>
                    <th>Active Staff</th>
                    <th>Installed PC</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <template v-for="(item, itemKey) in filteredPulledOutGroups" :key="item.id">
                    <tr>
                        <td>{{ itemKey+1 }}</td>
                        <td>{{ item.name }}</td>
                        <td>{{ item.group_type }}</td>
                        <td>{{ item.owner }}</td>
                        <td>{{ item.contact }}</td>
                        <td>{{ item.code }}</td>
                        <td>{{ item.site }}</td>
                        <td>{{ item.province_name }}</td>
                        <td>{{ item.active_staff }}</td>
                        <td>{{ item.installed_pc }}</td>
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

export default {
    setup() {
        
        const { groups, getPulledOutGroups, filteredPulledOutGroups } = useGroups()

        onMounted(getPulledOutGroups)

        return {
            filteredPulledOutGroups
        }

    }
}
</script>