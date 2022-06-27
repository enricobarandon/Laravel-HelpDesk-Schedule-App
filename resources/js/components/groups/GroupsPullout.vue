<template>
    <div v-bind="$attrs">

        <a href="/groups/view/pullout?download-pullout=1" class="btn btn-success btn-top-right">Download Excel</a>
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

                <div class="col-md-3">
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
import { onMounted, reactive } from 'vue'
import useGroups from '../../composables/groups'
import moment from 'moment'
import Multiselect from '@vueform/multiselect'

export default {
    components: {
      Multiselect,
    },
    setup() {
        
        const { groups, getPulledOutGroups, filteredPulledOutGroups, groupTypes } = useGroups()

        onMounted(getPulledOutGroups)

        const filter = reactive({
            'code' : '',
            'site' : '',
            'type' : [],
            'guarantor' : ''
        })

        const postFilterGroup = async () => {
            filteredPulledOutGroups.value = groups.value.filter((val) => {
                if (filter.code) {
                    return val.code.toLowerCase() == filter.code.toLowerCase()
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
            })
        }

        const resetFilter = () => {
            // router.go()
            filteredPulledOutGroups.value = groups.value
            filter.code = ''
            filter.site = ''
            filter.type = []
            filter.guarantor = ''
        }

        return {
            filteredPulledOutGroups,
            groupTypes,
            filter,
            postFilterGroup,
            resetFilter,
            selectGroupTypes: {
                mode: 'tags',
                closeOnSelect: false,
                options: groupTypes,
                placeholder: 'Select Group Type'
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