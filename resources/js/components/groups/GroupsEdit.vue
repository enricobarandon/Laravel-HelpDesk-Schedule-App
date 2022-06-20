<template>

    <div class="row">

        <div v-if="errors">
            <div v-for="(v, k) in errors" :key="k">
                <p v-for="error in v" :key="error" class="text-sm text-red">
                {{ error }}
                </p>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group"> 
                <label>Group Name</label>
                <textarea id="group-name" name="group-name" class="form-control" rows="2" v-model="group.name"></textarea>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label>Address</label>
                <textarea id="group-address" name="group-address" class="form-control" rows="2" v-model="group.address"></textarea>
            </div>
        </div>


        <div class="col-md-4">

            <div class="form-group">
                <label>Group Type</label>
                <input type="text" class="form-control" id="group-type" name="group-type" v-model="group.group_type">
            </div>

            <div class="form-group">
                <label>Group Code</label>
                <input type="text" class="form-control" id="group-code" name="group-code" v-model="group.code">
            </div>

        </div>

        <div class="col-md-4">

            <div class="form-group">
                <label>Operator</label>
                <input type="text" class="form-control" id="group-operator" name="group-operator" v-model="group.owner">
            </div>

            <div class="form-group">
                <label>Site</label>
                <input type="text" class="form-control" id="group-site" name="group-site" v-model="group.site">
            </div>
            
        </div>

        <div class="col-md-4">

            <div class="form-group">
                <label>Contact</label>
                <input type="number" class="form-control" id="group-contact" name="group-contact" v-model="group.contact">
            </div>

            <div class="form-group">
                <label>Guarantor</label>
                <input type="text" class="form-control" id="group-guarantor" name="group-guarantor" v-model="group.guarantor">
            </div>

        </div>

        <div class="col-md-4">
            <label>Status</label>
            <div class="form-control text-center">
                <label class="radio-active" :for="active">
                <input 
                    type="radio"
                    name="is_active" 
                    :id="active"
                    :value="1" 
                    v-model="group.is_active">
                Active</label>
                <label class="radio-deactivated" :for="deactivated">
                <input 
                    type="radio"
                    name="is_active" 
                    :id="deactivated"
                    :value="0" 
                    v-model="group.is_active">
                Deactivated</label>
            </div>
        </div>

        <div class="col-md-8">
            <div class="form-group">
                <label>Request Remarks</label>
                <textarea id="remarks" name="remarks" class="form-control" rows="2" v-model="group.remarks"></textarea>
            </div>
        </div>

        <div class="col-md-12 text-center">
            <button type="submit" class="btn btn-primary" @click="postUpdateRequest(group.uuid)">Submit Update Request</button>
        </div>

        <hr class="hr-css"/>

        <form @submit.prevent="saveGroup" ref="form" class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>No. of Staff</label>
                    <input type="text" class="form-control" id="group-staff" name="group-staff" v-model="group.active_staff">
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label>No. of PC Installed</label>
                    <input type="text" class="form-control" id="group-installed-pc" name="group-installed-pc" v-model="group.installed_pc">
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="form-group">
                    <label>Operation Date</label>
                    <Datepicker :format="formatDate" name="operation-date" id="operation-date" placeholder="Select Date" v-model="group.operation_date" />
                </div>
            </div>

            <div class="col-md-6">
                <div class="form-group">
                    <label>Pullout Date</label>
                    <Datepicker :format="formatDate" name="pullout-date" id="pullout-date" placeholder="Select Date" v-model="group.pullout_date" />
                </div>
            </div> 

            <div class="col-md-6">
                <div class="form-group">
                    <label>Status</label>
                    <select class="form-control" id="group-status" name="group-status" v-model="group.status">
                        <option selected disabled value="">-- Select Status --</option>
                        <option value="">No Status</option>
                        <option value="forpullout">For Pullout</option>
                        <option value="pullout">Pullout</option>
                        <option value="onhold">On Hold</option>
                        <option value="temporarydeactivated">Temporarily Deactivated</option>
                    </select>
                </div>
            </div>

            <div class="col-md-12 text-center">
                <button type="button" v-on:click="sweetAlert" class="btn btn-primary">Submit</button>
                <button type="submit" ref="submit" style="display: none">Submit</button>
            </div>

        </form>

    </div>

</template>

<script>
import { reactive, onMounted } from 'vue'
import useGroups from '../../composables/groups'
import useRequests from '../../composables/requests'
import Datepicker from '@vuepic/vue-datepicker'
import '@vuepic/vue-datepicker/dist/main.css'
import moment from 'moment'

export default {
    props: {
        id: {
            required: true,
            type: String
        }
    },
    setup(props) {

        const { errors, group, getGroup, updateGroup,oldGroupData } = useGroups()

        const { storeRequest } = useRequests()

        const form = reactive({
            'api_key' : '',
            'uuid' : '',
            'operation' : '',
            'status' : 'pending',
            'data' : '',
            'remarks': '',
            'reference_number' : ''
        })

        onMounted(getGroup(props.id))

        const saveGroup = async () => {
            await updateGroup(props.id)
        }

        const postUpdateRequest = async (uuid) => {
            form.api_key = '4e829e510539afcc43365a18acc91ede41fb555e'
            form.uuid = uuid
            form.operation = 'groups.update'
            form.data = JSON.stringify({
                'name': group.value.name,
                'address' : group.value.address,
                // 'group_type' : group.value.group_type,
                'owner' : group.value.owner,
                'contact' : group.value.contact,
                'code' : group.value.code,
                'guarantor' : group.value.guarantor,
                'is_active' : group.value.is_active
            })
            form.remarks = group.value.remarks
            form.reference_number = ''

            await storeRequest({...form})
        }

        return {
            errors,
            group,
            saveGroup,
            form,
            postUpdateRequest
        }
    },
    components: {
        Datepicker
    },
    methods: {
        sweetAlert(e) {
            e.preventDefault();
            this.$swal({
            title: 'Are you sure?',
            text: 'You want to update this Group?',
            type: 'warning',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'Cancel',
            showCloseButton: true,
            showLoaderOnConfirm: true
            }).then((result) => {
            if(result.value) {
                this.$refs.submit.click();
            }
            })
        },
        formatDate(date){
            return moment(date).format('YYYY-MM-DD');
        },
    }
}
</script>