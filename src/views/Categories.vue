<template>
    <v-card flat>
        <v-row justify="space-between" align="center">
            <v-col cols="auto">
                <TitleBar />
            </v-col>
            <v-col cols="auto">
                <Popup :title="ModalTitle" btnName="Add Assets" @toggleModal="openModal" v-model="isActive">
                    <template #body>
                        <v-form @submit.prevent="onSubmit">
                            <v-row>
                                <v-col cols="12">
                                    <v-row>
                                        <v-col cols="12" sm="12" md="6">
                                            <div class="text-subtitle-1 text-medium-emphasis">Category Description</div>
                                            <v-text-field density="compact" variant="outlined"
                                                v-model="description.value.value"
                                                :error-messages="description.errorMessage.value">
                                            </v-text-field>
                                        </v-col>
                                    </v-row>
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
                <InventoryTable :items="items" :headers="headers">
                    <template v-slot:[`item.actions`]="{ item }">
                        <v-icon class="me-2 bg-yellow-darken-4 rounded-circle" @click="editItem(item)">
                            mdi-pencil
                        </v-icon>
                        <v-icon class="bg-red-darken-2 rounded-circle" @click="deleteItem(item.asset_tag)">
                            mdi-delete
                        </v-icon>
                    </template>
                </InventoryTable>
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
import { tableHeaders } from '@/constants/Inventory/headers'
import { ref, defineAsyncComponent, onMounted } from 'vue'
import { useForm, useField } from 'vee-validate'
import { useModal } from '@/composable/useModal'
import { useAxios } from '@/composable/useAxios'
import { Toaster } from '@/composable/useToast'
import * as yup from 'yup'
import Swal from 'sweetalert2'
import { development } from '@/constants/server'

//init
const headers = ref(tableHeaders)
const uploader = ref(null);
const items = ref([]);
const InventoryTable = defineAsyncComponent({
    loader: () => import('@/components/Table.vue')
})
//composables
const { ModalTitle, isActive, openModal, closeModal, updateModal, isUpdate } = useModal('Categories')
const { useToaster } = Toaster();

//forms and validation
const yupSchema = yup.object().shape({
    description: yup.string().required(),
});

const { handleSubmit, setValues } = useForm({
    validationSchema: yupSchema
});
const description = useField('description');
//end
//methods

const fetchCategories = async () => {
    const response = await useAxios({
        method: 'GET',
        api: '/categories/?action=GET'
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
        api: '/categories/?action=PUT',
        data: values
    });
    if (response.ok) {
        const findIndex = items.value.findIndex((res) => res.category_id === values.category_id)
        items.value[findIndex] = { ...values }
        useToaster(response.data.message, 'success');
    } else {
        useToaster(response.error, 'error');
    }
}

const onInsert = async (values) => {
    const response = await useAxios({
        method: 'POST',
        api: '/categories/?action=POST',
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


const deleteAsset = async (categoryId) => {
    const response = await useAxios({
        method: 'DELETE',
        api: '/categories/?action=DELETE',
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



const deleteItem = (categoryId) => {
    Swal.fire({
        title: "Are you sure?",
        text: "You want to delete this asset?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!"
    }).then((result) => {
        if (result.isConfirmed) {
            deleteAsset(categoryId);
        }
    });
}

const editItem = (item) => {
    setValues({
        asset_tag: item.asset_tag,
        description: item.description,
        
    });
    updateModal();
}

//end


//lifecycle hook
onMounted(() => {
    fetchInventory();

})

//end

</script>

<style scoped>
.img-border {
    border: 3px solid #373737;
}
</style>