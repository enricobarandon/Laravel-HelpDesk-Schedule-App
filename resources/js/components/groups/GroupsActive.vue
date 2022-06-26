<template>
    <div v-bind="$attrs">
        <a href="/groups/view/active?download-active=1" class="btn btn-success btn-top-right">Download Excel</a>
        <div class="form-horizontal">
            <div class="form-group row">

                <div class="col-md-2">
                    <input type="text" class="form-control" name="filterCode" id="filterCode" placeholder="Group Code" @keyup.enter="postFilterGroup()" v-model='filter.code'>
                </div>

                <div class="col-md-2">
                    <select class="form-control" name="filterSite" placeholder="Site" @keyup.enter="postFilterGroup()" v-model='filter.site'>
                        <option selected value="">Select Site</option>
                        <option value="wpc2040">WPC2040</option>
                        <option value="wpc2040aa">WPC2040AA</option>
                    </select>
                </div>

                <!-- <div class="col-md-2">
                    <select class="form-control" name="filterType" placeholder="Group Type" @keyup.enter="postFilterGroup()" v-model='filter.type'>
                        <option selected value="">Select Group Type</option>
                        <option :value="value" v-for="(value, name) in groupTypes" :key="name">{{ value }}</option>
                    </select>
                </div> -->

                <div class="col-md-2">
                    <Multiselect
                    v-model="filter.type"
                    v-bind="selectGroupTypes"
                    :create-option="true"
                    @keyup.enter="postFilterGroup()"
                    />
                </div>

                <div class="col-md-3">
                    <input type="text" class="form-control" name="filterGuarantor" id="filterGuarantor" placeholder="Guarantor" @keyup.enter="postFilterGroup()" v-model='filter.guarantor'>
                </div>

                <div class="col">
                    <button type="button" class="btn btn-success" @click="postFilterGroup()"><i class="fas fa-search"></i> Submit</button>
                    <a href="#" class="btn btn-danger" @click="resetFilter()">Reset</a>
                </div>
            </div>
        </div>

        <!-- <router-link :to="{ name: 'groups.create' }"  class="btn btn-primary float-right"><i class="fas fa-plus"></i> Create Group</router-link> -->
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
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <template v-for="(item, itemKey) in filteredGroups" :key="item.id">
                    <tr>
                        <td>{{ itemKey+1 }}</td>
                        <td>{{ item.name }}</td>
                        <td>{{ item.address }}</td>
                        <td>{{ item.group_type }}</td>
                        <td>{{ item.owner }}</td>
                        <td>{{ item.contact }}</td>
                        <td>{{ item.code }}</td>
                        <td :class="item.site == 'wpc2040' ? 'td-blue' : 'td-red'">{{ item.site }}</td>
                        <td>{{ item.province_name }}</td>
                        <td>{{ item.guarantor }}</td>
                        <td>{{ format_date(item.operation_date) }}</td>
                        <td class="display-center">
                            <!-- <button type="button" class="btn btn-danger" @click="postDeactivationRequest(item.uuid)"><i class="fas fa-times"></i> Deactivate</button> -->
                            <router-link :to="{ name: 'groups.edit', params: {id: item.id} }"  class="btn btn-xs btn-primary" v-if="user_type == '1' || user_type == '2'">
                                <i class="fas fa-cog"></i>Edit
                            </router-link>
                            <!-- <a :to="'{{ /groups/request/edit/' + item.id" class="btn btn-danger">Edit 3</a>
                            <button type="button" class="btn btn-danger" @click="redirectToEditForm(item.id)">Edit 2</button> -->
                            <router-link :to="{ name: 'groups.requests.edit', params: {id: item.id} }"  class="btn btn-xs btn-primary" v-else-if="user_type == '3' || user_type == '4'">
                                <i class="fas fa-cog"></i>Edit
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
import moment from 'moment'
// import { useRouter } from 'vue-router'
import Multiselect from '@vueform/multiselect'

export default {
    // inheritAttrs: false,
    props: {
        user: Object
    },
    components: {
      Multiselect,
    },
    setup(props) {
        // const router = useRouter()

        const user_type = props.user.user_type_id

        const form = reactive({
            'api_key' : '',
            'uuid' : '',
            'operation' : '',
            'status' : 'pending',
            'data' : ''
        })

        const filter = reactive({
            // 'name' : '',
            'code' : '',
            'site' : '',
            'type' : [],
            'guarantor' : ''
        })

        const { groups, filteredGroups, getActiveGroups, groupTypes } = useGroups()

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
            // filteredGroups.value = groups.value.filter(val => val.code.toLowerCase() == filter.code.toLowerCase())
            filteredGroups.value = groups.value.filter((val) => {
                if (filter.code) {
                    return val.code.toLowerCase() == filter.code.toLowerCase()
                }
                if (filter.site) {
                    return val.site == filter.site
                }
                if (filter.type.length > 0) {
                    return filter.type.includes(val.group_type)
                }
                if (filter.guarantor) {
                    if (!val.guarantor) {
                        return false;
                    }
                    return val.guarantor.toLowerCase().includes(filter.guarantor.toLowerCase())
                }

                return true;
            })
        }

        const resetFilter = () => {
            // router.go()
            filteredGroups.value = groups.value
            filter.code = ''
            filter.site = ''
            filter.type = []
            filter.guarantor = ''
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
            user_type,
            // redirectToEditForm
            groupTypes,
            selectGroupTypes: {
                mode: 'tags',
                closeOnSelect: false,
                options: groupTypes
            }
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

<style src="@vueform/multiselect/themes/default.css"></style>