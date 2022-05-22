<template>
    <!-- <router-link :to="{ name: 'groups.create' }"  class="btn btn-primary float-right"><i class="fas fa-plus"></i> Create Group</router-link> -->
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
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <template v-for="item in groups" :key="item.id">
                <tr>
                    <td>{{ count++ }}</td>
                    <td>{{ item.name }}</td>
                    <td>{{ item.group_type }}</td>
                    <td>{{ item.owner }}</td>
                    <td>{{ item.contact }}</td>
                    <td>{{ item.code }}</td>
                    <td>{{ item.site }}</td>
                    <td>{{ item.province_name }}</td>
                    <td>{{ item.active_staff }}</td>
                    <td>{{ item.installed_pc }}</td>
                    <td class="display-center">
                        
                    </td>
                </tr>
            </template>
        </tbody>
    </table>
</template>

<script>
import { onMounted } from 'vue'
import useGroups from '../../composables/groups'

export default {
    setup() {

        const count = 1;

        const { groups, getActiveGroups } = useGroups()

        onMounted(getActiveGroups)

        return {
            groups,
            count
        }
    }
}
</script>