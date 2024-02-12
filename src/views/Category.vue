<template>
    <v-card flat>
        <v-row justify="space-between" align="center">
            <v-col cols="auto">
                <TitleBar />
            </v-col>
            <v-col cols="auto">
                <Popup :title="ModalTitle" btnName="Add Assets" @toggleModal="openModal" v-model="isActive" :width="480">
                    <template #body>
                        <v-form @submit.prevent="onSubmit">
                            <v-row>
                                <v-col cols="12">
                                    <div class="text-subtitle-1 text-medium-emphasis">Description</div>
                                    <v-text-field density="compact" variant="outlined" v-model="description.value.value"
                                        :error-messages="description.errorMessage.value">
                                    </v-text-field>
                                    <v-switch v-model="status.value.value" hide-details true-value="1" false-value="0"
                                        defaults-target="1" color="primary" label="Active"></v-switch>
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
                <CategoryTable :items="items" :headers="headers">
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
                        <v-icon class="bg-red-darken-2 rounded-circle" @click="deleteItem(item.categoryId)">
                            mdi-delete
                        </v-icon>
                    </template>
                </CategoryTable>
            </template>
            <template #fallback>
                <p>Loading</p>
            </template>
        </Suspense>
    </v-card>
</template>
    

<script setup>
//imports
import TitleBar from '@/components/TitleBar.vue'
import Popup from '@/components/Popup.vue'
import { headerCategory } from '@/constants/Inventory/headers'
import { ref, defineAsyncComponent, onMounted } from 'vue'
import { useForm, useField } from 'vee-validate'
import { useModal } from '@/composable/useModal'
import { useAxios } from '@/composable/useAxios'
import { Toaster } from '@/composable/useToast'
import * as yup from 'yup'
import Swal from 'sweetalert2'

//init
const headers = ref(headerCategory)
const items = ref([]);
const CategoryTable = defineAsyncComponent({
    loader: () => import('@/components/Table.vue')
})
//composables
const { ModalTitle, isActive, openModal, closeModal, updateModal, isUpdate } = useModal('Category')
const { useToaster } = Toaster();

//forms and validation
const yupSchema = yup.object().shape({
    categoryId: yup.number().notRequired(),
    description: yup.string().required(),
    status: yup.number().notRequired(),
});

const { handleSubmit, setValues } = useForm({
    validationSchema: yupSchema
});

const description = useField('description');
const status = useField('status');
setValues({
    status: 1
})




//methods
const fetchCategory = async () => {
    const response = await useAxios({
        method: 'GET',
        api: '/category.php?action=GET'
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
        api: '/category?action=PUT',
        data: values,
    });
    if (response.ok) {
        const findIndex = items.value.findIndex((res) => res.categoryId === values.categoryId)
        items.value[findIndex] = { ...values }
        useToaster(response.data.message, 'success');
    } else {
        useToaster(response.error, 'error');
    }
}

const onInsert = async (values) => {
    const response = await useAxios({
        method: 'POST',
        api: '/category?action=POST',
        data: values,
    });
    if (response.ok) {
        useToaster(response.data.message, 'success');
        items.value.push({ ...values })
        closeModal();
    } else {
        useToaster(response.error, 'error');
    }
}

const deleteItem = (categoryId) => {
    Swal.fire({
        title: "Are you sure?",
        text: "You want to delete this category?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!"
    }).then(async (result) => {
        if (result.isConfirmed) {
            const response = await useAxios({
                method: 'DELETE',
                api: '/category?action=DELETE',
                params: {
                    categoryId: categoryId
                }
            });
            if (response.ok) {
                useToaster(response.data.message, 'success');
                items.value = items.value.filter(res => res.categoryId !== categoryId)
            } else {
                useToaster(response.error, 'error');
            }
        }
    });
}

const editItem = (item) => {
    setValues({
        categoryId: item.categoryId,
        description: item.description,
        status: item.status,
    });
    updateModal();
}

//end


//lifecycle hook
onMounted(() => {
    fetchCategory();
})

//end

</script>
