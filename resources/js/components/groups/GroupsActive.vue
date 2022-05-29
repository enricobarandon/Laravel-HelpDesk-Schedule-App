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
                        <!-- <button type="button" class="btn btn-danger" @click="postDeactivationRequest(item.uuid)"><i class="fas fa-times"></i> Deactivate</button> -->
                        <router-link :to="{ name: 'groups.edit', params: {id: item.id} }"  class="btn btn-primary">
                            <i class="fas fa-cog"></i>Edit
                        </router-link>
                    </td>
                </tr>
            </template>
        </tbody>
    </table>
</template>

<script>
import { onMounted, reactive } from 'vue'
import useGroups from '../../composables/groups'
import useRequests from '../../composables/requests'

export default {
    setup() {

        const count = 1;

        const form = reactive({
            'api_key' : '',
            'uuid' : '',
            'operation' : '',
            'status' : 'pending',
            'data' : ''
        })

        const { groups, getActiveGroups } = useGroups()

        const { storeRequest } = useRequests()

        onMounted(getActiveGroups)

        const postDeactivationRequest = async (uuid) => {
            form.api_key = '4e829e510539afcc43365a18acc91ede41fb555e'
            form.uuid = uuid
            form.operation = 'groups.update'
            form.data = JSON.stringify({'is_active': 0})
            await storeRequest({...form})
        }

        return {
            groups,
            count,
            form,
            postDeactivationRequest,
            // testFilter
        }
    }
}
</script>