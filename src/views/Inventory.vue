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
                <InventoryTable :items="items" :headers="header">
                    <!-- <template v-slot:[`item.fullname`]="{ item }">
                        {{ item.lname }} {{ item.fname }} {{ item.mname[0] }}
                    </template> -->
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
import TitleBar from '@/components/TitleBar.vue'
import Popup from '@/components/Popup.vue'
import { condition, status } from '@/constants/Inventory/selection'
import { tableHeaders } from '@/constants/Inventory/headers'
import { ref, defineAsyncComponent } from 'vue'
import { useForm, useField } from 'vee-validate'
import { useFileUploader } from '@/composable/useFileUploader'
import { useModal } from '@/composable/useModal'
import { api } from '@/axios/axios'
import * as yup from 'yup'

const conditionItem = ref(condition)
const statusItem = ref(status)
const header = ref(tableHeaders)
const uploader = ref(null);

const InventoryTable = defineAsyncComponent({
    loader: () => import('@/components/Table.vue')
})

const { ModalTitle, isActive, openModal, closeModal } = useModal('Assets')
const { filePreview, filePath, uploadFile } = useFileUploader(isActive)
const yupSchema = yup.object({
    asset_tag: yup.number().required(),
    item_name: yup.string().required(),
    category_id: yup.number().required(),
    item_model: yup.string().required(),
    item_condition: yup.string().required(),
    status: yup.number().required(),
    description: yup.string().required(),
    img_path: yup.string()
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
setValues('img_path', filePath.value)

const clickFile = () => {
    uploader.value.click();
}

const onSubmit = handleSubmit(async (values) => {
   
    const response = await api({
        method:'POST',
        api:'inventory',
        data: values
    })
    if (!response.ok) {
        alert(response.error.data.messages.error); // Corrected alert function call
        return false;
    }
});







</script>

<style scoped>
.img-border {
    border: 3px solid #373737;
}
</style>