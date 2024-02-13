<template>
        <v-dialog persistent transition="dialog-bottom-transition" :width="width">
        <template v-slot:activator="{ props }">
            <v-btn color="primary" v-bind="props" @click="toggleModal">
                <v-icon left class="mr-2">mdi-account-plus</v-icon>
                {{ btnName }}
            </v-btn>
        </template>
        <v-card>
            <v-toolbar color="primary">
                <v-toolbar-title class="text-uppercase">{{ title }}</v-toolbar-title>
            </v-toolbar>
            <v-container>
                <slot name="body"></slot>
            </v-container>
            <slot name="footer"></slot>
        </v-card>
    </v-dialog>
  
</template>

<script setup>
import { defineProps, defineEmits } from 'vue';

defineProps({
    title: {
        type: String,
        default: 'dsds'
    },
    btnName: {
        type: String,
        default: ''
    },
    width: {
        type: Number,
        default: 1024
    },
})

const emit = defineEmits(['toggleModal'])

function toggleModal() {
    emit('toggleModal')
}

</script>

<style scoped></style>