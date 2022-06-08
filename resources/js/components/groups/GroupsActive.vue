<template>
    <div v-bind="$attrs">
    <div class="form-horizontal">
        <div class="form-group row">

            <!-- <div class="col-md-3">
                <input type="text" class="form-control" name="filterName" id="filterName" placeholder="Group Name" v-model='filter.name'>
            </div> -->

            <div class="col-md-3">
                <input type="text" class="form-control" name="filterCode" id="filterCode" placeholder="Group Code" v-model='filter.code'>
            </div>

            <div class="col">
                <button type="button" class="btn btn-success" @click="postFilterGroup()"><i class="fas fa-search"></i> Submit</button>
                <a href="#" class="btn btn-danger" @click="resetFilter()">Reset</a>
            </div>
        </div>
    </div>

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
            <template v-for="(item, itemKey) in filteredGroups" :key="item.id">
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
                    <td class="display-center">
                        <!-- <button type="button" class="btn btn-danger" @click="postDeactivationRequest(item.uuid)"><i class="fas fa-times"></i> Deactivate</button> -->
                        <router-link :to="{ name: 'groups.edit', params: {id: item.id} }"  class="btn btn-primary" v-if="props.user.user_type_id == '1'">
                            <i class="fas fa-cog"></i>Edit
                        </router-link>
                        <!-- <a :to="'{{ /groups/request/edit/' + item.id" class="btn btn-danger">Edit 3</a>
                        <button type="button" class="btn btn-danger" @click="redirectToEditForm(item.id)">Edit 2</button> -->
                        <router-link :to="{ name: 'groups.requests.edit', params: {id: item.id} }"  class="btn btn-primary" v-else>
                            <i class="fas fa-cog"></i>Edit 2
                        </router-link>
                    </td>
                </tr>
            </template>
        </tbody>
    </table>
    </div>
</template>

<script>
import { onMounted, reactive } from 'vue'
import useGroups from '../../composables/groups'
import useRequests from '../../composables/requests'
// import { useRouter } from 'vue-router'

export default {
    // inheritAttrs: false,
    props: {
        user: Object
    },
    setup(props) {
        // const router = useRouter()

        console.log('type: ' + props.user.user_type_id)

        const form = reactive({
            'api_key' : '',
            'uuid' : '',
            'operation' : '',
            'status' : 'pending',
            'data' : ''
        })

        const filter = reactive({
            // 'name' : '',
            'code' : ''
        })

        const { groups, filteredGroups, getActiveGroups } = useGroups()

        const { storeRequest } = useRequests()

        onMounted(getActiveGroups)

        const postDeactivationRequest = async (uuid) => {
            form.api_key = '4e829e510539afcc43365a18acc91ede41fb555e'
            form.uuid = uuid
            form.operation = 'groups.update'
            form.data = JSON.stringify({'is_active': 0})
            await storeRequest({...form})
        }

        const postFilterGroup = async () => {
            // let filtered = groups.value.filter(val => val.code == 'kik')
            // let filtered = groups.value.filter(val => val.code == filter.code.toUpperCase())
            filteredGroups.value = groups.value.filter(val => val.code.toLowerCase() == filter.code.toLowerCase())
        }

        const resetFilter = () => {
            // router.go()
            filteredGroups.value = groups.value
        }

        // const redirectToEditForm = (id) => {
        //     window.location.href = '/groups/request/edit/' + id
        // }

        return {
            groups,
            form,
            postDeactivationRequest,
            filter,
            postFilterGroup,
            resetFilter,
            filteredGroups,
            props,
            // redirectToEditForm
        }
    }
}
</script>