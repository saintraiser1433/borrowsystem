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
                                <v-col cols="12" sm="12" md="4">
                                    <div class="d-flex flex-column justify-center mb-6">
                                        <div class="text-h5 font-weight-bold text-center text-dark">IMAGE PHOTO</div>
                                        <v-divider class="mb-2"></v-divider>
                                        <v-img :width="300" :aspect-ratio="1" cover class="img-border mx-auto mb-2"
                                            :src="filePreview"></v-img>
                                        <v-file-input class="d-none" ref="uploader"
                                            accept="image/png, image/jpeg, image/bmp" @change="uploadFile">
                                        </v-file-input>
                                        <div class="text-center">
                                            <v-btn prepend-icon="mdi-check-circle" flat class="bg-primary"
                                                @click="clickFile">
                                                <template v-slot:prepend>
                                                    <v-icon>mdi-account-circle</v-icon>
                                                </template>
                                                Upload Image
                                            </v-btn>
                                        </div>
                                    </div>

                                </v-col>
                                <v-col cols="12" sm="12" md="8">
                                    <v-row>
                                        <v-col cols="12" sm="12" md="6">
                                            <div class="text-subtitle-1 text-medium-emphasis">Item Tag #</div>
                                            <v-text-field density="compact" variant="outlined"
                                                v-model="assetTag.value.value"
                                                :error-messages="assetTag.errorMessage.value">
                                            </v-text-field>
                                        </v-col>
                                        <v-col cols="12" sm="12" md="6">
                                            <div class="text-subtitle-1 text-medium-emphasis">Item Name</div>
                                            <v-text-field density="compact" variant="outlined"
                                                v-model="itemName.value.value"
                                                :error-messages="itemName.errorMessage.value">
                                            </v-text-field>
                                        </v-col>
                                        <v-col cols="12" sm="12" md="6">
                                            <div class="text-subtitle-1 text-medium-emphasis">Category</div>
                                            <v-text-field density="compact" variant="outlined"
                                                v-model="categoryId.value.value"
                                                :error-messages="categoryId.errorMessage.value">
                                            </v-text-field>
                                        </v-col>
                                        <v-col cols="12" sm="12" md="6">
                                            <div class="text-subtitle-1 text-medium-emphasis">Model</div>
                                            <v-text-field density="compact" variant="outlined"
                                                v-model="itemModel.value.value"
                                                :error-messages="itemModel.errorMessage.value">
                                            </v-text-field>
                                        </v-col>
                                        <v-col cols="12" sm="12" md="6">
                                            <div class="text-subtitle-1 text-medium-emphasis">Condition</div>
                                            <v-select :items="conditionItem" density="compact" variant="outlined"
                                                v-model="itemCondition.value.value"
                                                :error-messages="itemCondition.errorMessage.value">
                                            </v-select>
                                        </v-col>

                                        <v-col cols="12" sm="12" md="6">
                                            <div class="text-subtitle-1 text-medium-emphasis">Status</div>
                                            <v-select :items="statusItem" density="compact" variant="outlined"
                                                item-title="description" item-value="id"
                                                v-model="statusDescription.value.value"
                                                :error-messages="statusDescription.errorMessage.value">
                                            </v-select>
                                        </v-col>
                                        <v-col cols="12" sm="12" md="12">
                                            <div class="text-subtitle-1 text-medium-emphasis">Description</div>
                                            <v-textarea density="compact" variant="outlined"
                                                v-model="description.value.value"
                                                :error-messages="description.errorMessage.value"></v-textarea>
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
                    <template v-slot:[`item.img_path`]="{ item }">

                        <div v-if="item.img_path === null">
                            <v-img :width="36" :aspect-ratio="1" cover class="mx-auto" src="images/no-image.jpg"></v-img>
                        </div>
                        <div v-else>
                            <v-img :width="36" :aspect-ratio="1" cover class="mx-auto"
                                :src="`http://localhost/borrowing-api/inventory/${item.img_path}`"></v-img>
                        </div>
                    </template>
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
import { condition, status } from '@/constants/selection'
import { tableHeaders } from '@/constants/headers'
import { ref, defineAsyncComponent, onMounted } from 'vue'
import { useForm, useField } from 'vee-validate'
import { useFileUploader } from '@/composable/useFileUploader'
import { useModal } from '@/composable/useModal'
import { useAxios } from '@/composable/useAxios'
import { Toaster } from '@/composable/useToast'
import * as yup from 'yup'
import Swal from 'sweetalert2'
import { development } from '@/constants/server'

import { watchEffect } from 'vue'
//init
const conditionItem = ref(condition)
const statusItem = ref(status)
const headers = ref(tableHeaders)
const uploader = ref(null);
const items = ref([]);
const InventoryTable = defineAsyncComponent({
    loader: () => import('@/components/Table.vue')
})
//composables
const { ModalTitle, isActive, openModal, closeModal, updateModal, isUpdate } = useModal('Assets')
const { filePreview, filePath, uploadFile, generateFileName } = useFileUploader(isActive)
const { useToaster } = Toaster();

//forms and validation
const yupSchema = yup.object().shape({
    asset_tag: yup.number().required(),
    item_name: yup.string().required(),
    category_id: yup.number().required(),
    item_model: yup.string().required(),
    item_condition: yup.string().required(),
    status: yup.number().required(),
    description: yup.string().required(),
    img_path: yup.mixed().notRequired()
});

const { handleSubmit, setValues } = useForm({
    validationSchema: yupSchema
});

const assetTag = useField('asset_tag');
const itemName = useField('item_name');
const categoryId = useField('category_id');
const itemModel = useField('item_model');
const itemCondition = useField('item_condition');
const statusDescription = useField('status');
const description = useField('description');



//end


//methods
const clickFile = () => {
    uploader.value.click();
}
const fetchInventory = async () => {
    const response = await useAxios({
        method: 'GET',
        api: '/inventory/?action=GET'
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
    const formData = new FormData();
    formData.append('data', JSON.stringify(values));
    formData.append('files', filePath.value);
    const response = await useAxios({
        method: 'POST',
        api: '/inventory/?action=PUT',
        data: formData,
        header: {
            'Content-Type': 'multipart/form-data'
        },
    });
    if (response.ok) {
        const findIndex = items.value.findIndex((res) => res.asset_tag === values.asset_tag)
        items.value[findIndex] = { ...values }
        useToaster(response.data.message, 'success');
    } else {
        useToaster(response.error, 'error');
    }
}

const onInsert = async (values) => {
    const formData = new FormData();
    formData.append('data', JSON.stringify(values));
    formData.append('files', filePath.value);
    const response = await useAxios({
        method: 'POST',
        api: '/inventory/?action=POST',
        data: formData,
        header: {
            'Content-Type': 'multipart/form-data'
        },
    });
    if (response.ok) {
        useToaster(response.data.message, 'success');
        items.value.push({ ...values })
        closeModal();
    } else {
        useToaster(response.error, 'error');
    }
}


const deleteAsset = async (asset_tag) => {
    const response = await useAxios({
        method: 'DELETE',
        api: '/inventory/?action=DELETE',
        params: {
            asset_tag: asset_tag
        }
    });
    if (response.ok) {
        useToaster(response.data.message, 'success');
        items.value = items.value.filter(res => res.asset_tag !== asset_tag)
    } else {
        useToaster(response.error, 'error');
    }
}



const deleteItem = (asset_tag) => {
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
            deleteAsset(asset_tag);
        }
    });
}

const editItem = (item) => {
    setValues({
        asset_tag: item.asset_tag,
        item_name: item.item_name,
        category_id: item.category_id,
        item_model: item.item_model,
        item_condition: item.item_condition,
        status: item.status,
        description: item.description,
        img_path: item.img_path
    });
    if(item.img_path === null){
        filePreview.value = 'images/no-image.jpg'
    }else{
        filePreview.value = `${development.baseUrl}/inventory/${item.img_path}`
    }
    updateModal();
}

watchEffect(() => {
    setValues({
        img_path: generateFileName.value,
    });
});

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