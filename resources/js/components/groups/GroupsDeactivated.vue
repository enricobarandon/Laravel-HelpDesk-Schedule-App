<template> 
        <a href="/groups/view/deactivated?download-deactivated=1" class="btn btn-success btn-top-right">Download All</a>
        <div class="form-group row">

            <div class="col-md-3">
                <input type="text" class="form-control" name="filterCode" id="filterCode" placeholder="Group" @keyup.enter="postFilterGroup()" v-model='filter.code'>
            </div>

            <div class="col">
                <select class="form-control" name="filterStatus" placeholder="Status" v-model='filter.status'>
                    <option selected value="">Select Status</option>
                    <option value="forpullout">For Pullout</option>
                    <option value="onhold">On Hold</option>
                    <option value="temporarydeactivated">Temporarily Deactivated</option>
                </select>
            </div>

            <div class="col">
                <select class="form-control" name="filterSite" placeholder="Site" v-model='filter.site'>
                    <option selected value="">Select Site</option>
                    <option value="wpc2040">WPC2040</option>
                    <option value="wpc2040aa">WPC2040AA</option>
                </select>
            </div>

            <div class="col-md-2">
                <Multiselect
                v-model="filter.type"
                v-bind="selectGroupTypes"
                :create-option="true"
                @keyup.enter="postFilterGroup()"
                />
            </div>

            <div class="col-md-2">
                <input type="text" class="form-control" name="filterGuarantor" id="filterGuarantor" placeholder="Guarantor" @keyup.enter="postFilterGroup()" v-model='filter.guarantor'>
            </div>

            <div class="col-md-2">
                <button type="button" class="btn btn-success" @click="postFilterGroup()"><i class="fas fa-search"></i> Submit</button>
                <a href="#" class="btn btn-danger" @click="resetFilter()">Reset</a>
            </div>
        </div>
    <!-- </form> -->

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
                <th>Status</th>
                <th>Operation Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <template v-for="(item, itemKey) in filteredDeactivatedGroups" :key="item.id">
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
                    <td>{{ item.status }}</td>
                    <td>{{ format_date(item.operation_date) }}</td>
                    <td class="display-center">
                        <router-link :to="{ name: 'groups.edit', params: {id: item.id} }"  class="btn btn-xs btn-primary" v-if="user_type != '5'">
                            <i class="fas fa-cog"></i>Edit
                        </router-link>
                        <!-- <router-link :to="{ name: 'groups.requests.edit', params: {id: item.id} }"  class="btn btn-xs btn-primary" v-else-if="user_type == '3' || user_type == '4'">
                            <i class="fas fa-cog"></i>Edit
                        </router-link> -->
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
import moment from 'moment'
import Multiselect from '@vueform/multiselect'

export default {
    props: {
        user: Object
    },
    components: {
      Multiselect,
    },
    setup(props) {

        const user_type = props.user.user_type_id

        const form = reactive({
            'api_key' : '',
            'uuid' : '',
            'operation' : '',
            'status' : 'pending',
            'data' : ''
        })

        const filter = reactive({
            'code' : '',
            'status' : '',
            'site' : '',
            'type' : [],
            'guarantor' : ''
        })

        const { groups, filteredDeactivatedGroups, getDeactivatedGroups, groupTypes } = useGroups()

        const { storeRequest } = useRequests()

        onMounted(getDeactivatedGroups)

        const postActivateRequest = async (uuid) => {
            form.api_key = '4e829e510539afcc43365a18acc91ede41fb555e'
            form.uuid = uuid
            form.operation = 'groups.update'
            form.data = JSON.stringify({'is_active': 1})
            await storeRequest({...form})
        }

        const postFilterGroup = async () => {
            // filteredDeactivatedGroups.value = groups.value.filter(val => val.code.toLowerCase() == filter.code.toLowerCase() || val.status == filter.status || val.site == filter.site)
            // if (filter.status) {
            //     return val.status == filter.status
            // }
            filteredDeactivatedGroups.value = groups.value.filter((val) => {
                if (filter.code) {
                    return val.name.toLowerCase().includes(filter.code.toLowerCase())
                } 
                return true
            }).filter((val) => {
                if (filter.site) {
                    return val.site == filter.site
                }
                return true
            }).filter((val) => {
                if (filter.type.length > 0) {
                    return filter.type.includes(val.group_type)
                }
                return true
            }).filter((val) => {
                if (filter.guarantor) {
                    if (!val.guarantor) {
                        return false;
                    }
                    return val.guarantor.toLowerCase().includes(filter.guarantor.toLowerCase())
                }
                return true
            }).filter((val) => {
                if (filter.status) {
                    return val.status == filter.status
                }
                return true
            })
        }

        const resetFilter = () => {
            filteredDeactivatedGroups.value = groups.value
            filter.code = ''
            filter.status = ''
            filter.site = ''
            filter.type = []
            filter.guarantor = ''

        }

        return {
            groups,
            form,
            postActivateRequest,
            filteredDeactivatedGroups,
            filter,
            postFilterGroup,
            resetFilter,
            user_type,
            groupTypes,
            selectGroupTypes: {
                mode: 'tags',
                closeOnSelect: false,
                options: groupTypes,
                placeholder: 'Group Type'
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