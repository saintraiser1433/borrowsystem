<template>
    <v-card flat>
        <v-row justify="space-between" align="center">
            <v-col cols="auto">
                <TitleBar />
            </v-col>
            <v-col cols="auto">
                <Popup :title="ModalTitle" btnName="Add Department" @toggleModal="openModal" v-model="isActive"
                    :width="480">
                    <template #body>
                        <v-form @submit.prevent="onSubmit">
                            <v-row>
                                <v-col cols="12">
                                    <div class="text-subtitle-1 text-medium-emphasis">Description</div>
                                    <v-text-field density="compact" variant="outlined" v-model="description"
                                        v-bind="descriptionAttrs" :error-messages="errors.description">
                                    </v-text-field>
                                    <v-switch v-model="status" v-bind="statusAttrs" hide-details true-value="1"
                                        false-value="0" color="primary" label="Active"></v-switch>
                                </v-col>

                            </v-row>
                            <v-divider></v-divider>
                            <v-card-actions class="justify-end">
                                <v-btn text @click="closeModal">Close</v-btn>
                                <v-btn type="submit" class="bg-primary">Submit</v-btn>
                            </v-card-actions>
                        </v-form>
                    </template>
                </Popup>
            </v-col>
        </v-row>
        <Suspense>
            <template #default>
                <DepartmentTable :items="items" :headers="headers">
                    <template v-slot:[`item.description`]="{ item }">
                        <span class="text-capitalize">{{ item.description }}</span>
                    </template>
                    <template v-slot:[`item.status`]="{ item }">
                        <div v-if="item.status == 1">
                            <v-badge color="blue-darken-4" content="Active" inline></v-badge>
                        </div>
                        <div v-else>
                            <v-badge color="pink-accent-3" content="Inactive" inline></v-badge>
                        </div>
                    </template>
                    <template v-slot:[`item.actions`]="{ item }">
                        <v-icon class="me-2 bg-yellow-darken-4 rounded-circle" @click="editItem(item)">
                            mdi-pencil
                        </v-icon>
                        <v-icon class="bg-red-darken-2 rounded-circle" @click="deleteItem(item.departmentId)">
                            mdi-delete
                        </v-icon>
                    </template>
                </DepartmentTable>
            </template>
            <template #fallback>
                <p>Loading</p>
            </template>
        </Suspense>
    </v-card>
</template>
    

<script setup>
import TitleBar from '@/components/TitleBar.vue'
import Popup from '@/components/Popup.vue'
import { headerDepartment } from '@/constants/headers'
import { ref, defineAsyncComponent, onMounted, watch } from 'vue'
import { useForm } from 'vee-validate'
import { useModal } from '@/composable/useModal'
import { useAxios } from '@/composable/useAxios'
import { Toaster } from '@/composable/useToast'
import * as yup from 'yup'
import Swal from 'sweetalert2'

//init
const headers = ref(headerDepartment)
const items = ref([]);
const DepartmentTable = defineAsyncComponent({
    loader: () => import('@/components/Table.vue')
})

//composables
const { ModalTitle, openModal, closeModal, updateModal, isUpdate, isActive } = useModal('Department')
const { useToaster } = Toaster();

//forms and validation
const yupSchema = yup.object().shape({
    departmentId: yup.number().notRequired(),
    description: yup.string().required(),
    status: yup.number().notRequired(),
});

const { handleSubmit, setValues, defineField, errors, resetForm } = useForm({
    validationSchema: yupSchema
});

const [description, descriptionAttrs] = defineField('description');
const [status, statusAttrs] = defineField('status');

//methods
const fetchDepartment = async () => {
    const response = await useAxios({
        method: 'GET',
        api: '/department?action=GET'
    });
    if (response.ok) {
        items.value = response.data
    } else {
        useToaster(response.error, 'error');
    }
}
const onSubmit = handleSubmit(async (values) => {
    if (isUpdate.value) {
        onUpdate(values);
    } else {
        onInsert(values);
    }
    closeModal();
});


const onUpdate = async (values) => {
    const response = await useAxios({
        method: 'POST',
        api: '/department?action=PUT',
        data: values,
    });
    if (response.ok) {
        useToaster(response.data.message, 'success');
        fetchDepartment();
    } else {
        useToaster(response.error, 'error');
    }
}

const onInsert = async (values) => {
    const response = await useAxios({
        method: 'POST',
        api: '/department?action=POST',
        data: values,
    });
    if (response.ok) {
        useToaster(response.data.message, 'success');
        fetchDepartment();
        closeModal();
    } else {
        useToaster(response.error, 'error');
    }
}


const deleteItem = (departmentId) => {
    Swal.fire({
        title: "Are you sure?",
        text: "You want to delete this department?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!"
    }).then(async (result) => {
        if (result.isConfirmed) {
            const response = await useAxios({
                method: 'DELETE',
                api: '/department?action=DELETE',
                params: {
                    departmentId: departmentId
                }
            });
            if (response.ok) {
                useToaster(response.data.message, 'success');
                fetchDepartment()
            } else {
                useToaster(response.error, 'error');
            }
        }
    });
}

const editItem = (item) => {
    setValues({
        departmentId: item.departmentId,
        description: item.description,
        status: item.status,
    });
    updateModal();
}


watch([isActive, isUpdate], ([active, update]) => {
    if (active && !update) {
        resetForm({
            values: {
                departmentId:0,
                description: '',
                status: '1'
            }
        });

    }
})
//end
//lifecycle hook
onMounted(() => {
    fetchDepartment();
})

//end
</script>
