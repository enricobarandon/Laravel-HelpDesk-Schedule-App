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
                    <input type="text" class="form-control" id="group-site" name="group-site" v-model="group.site" readonly>
                </div>
                
            </div>

            <div class="col-md-4">

                <div class="form-group">
                    <label>Contact</label>
                    <input type="text" class="form-control" id="group-contact" name="group-contact" v-model="group.contact">
                </div>

                <div class="form-group">
                    <label>Guarantor</label>
                    <input type="text" class="form-control" id="group-guarantor" name="group-guarantor" v-model="group.guarantor">
                </div>

            </div>

            <button type="submit" class="btn btn-primary">Submit Update Request</button>

        <hr/>

        <form @submit.prevent="saveGroup">
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

            <button type="submit" class="btn btn-primary">Submit</button>

        </form>

    </div>

</template>

<script>
import { onMounted } from 'vue'
import useGroups from '../../composables/groups'

export default {
    props: {
        id: {
            required: true,
            type: String
        }
    },
    setup(props) {
        const { errors, group, getGroup, updateGroup } = useGroups()

        onMounted(getGroup(props.id))

        const saveGroup = async () => {
            await updateGroup(props.id)
        }

        return {
            errors,
            group,
            saveGroup
        }
    }
}
</script>