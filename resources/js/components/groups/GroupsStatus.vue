<template>

    <div class="row">

        <div v-if="errors">
            <div v-for="(v, k) in errors" :key="k">
                <p v-for="error in v" :key="error" class="text-sm text-red">
                {{ error }}
                </p>
            </div>
        </div>

        <div class="col-md-12">
            <div class="form-group"> 
                <label>Group Name</label>
                <textarea id="group-name" name="group-name" class="form-control" rows="2" v-model="group.name" disabled></textarea>
            </div>
        </div>
        
        <div class="col-md-12">
            <div class="form-group">
                <label>Address</label>
                <textarea id="group-address" name="group-address" class="form-control" rows="2" v-model="group.address" disabled></textarea>
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
                <label>Remarks</label>
                <textarea id="remarks" name="remarks" class="form-control" rows="2" v-model="group.remarks"></textarea>
            </div>
        </div>

        <div class="col-md-12 text-center">
            <button type="submit" class="btn btn-primary" @click="postUpdateRequest(group.uuid)">Submit Update Request</button>
        </div>

    </div>

</template>

<script>
import { reactive, onMounted, watch } from 'vue'
import useGroups from '../../composables/groups'
import useRequests from '../../composables/requests'

export default {
    props: {
        id: {
            required: true,
            type: String
        }
    },
    setup(props) {

        const { errors, group, getGroup, updateGroup } = useGroups()

        const { storeRequest } = useRequests()

        const form = reactive({
            'api_key' : '',
            'uuid' : '',
            'operation' : '',
            'status' : 'pending',
            'data' : '',
            'remarks': ''
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
                // 'name': group.value.name,
                // 'address' : group.value.address,
                // 'group_type' : group.value.group_type,
                // 'owner' : group.value.owner,
                // 'contact' : group.value.contact,
                // 'code' : group.value.code,
                // 'guarantor' : group.value.guarantor,
                'is_active' : group.value.is_active
            })
            form.remarks = group.value.remarks
            await storeRequest({...form})
        }

        return {
            errors,
            group,
            saveGroup,
            form,
            postUpdateRequest
        }
    }
}
</script>