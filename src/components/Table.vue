<template>
    <v-card-title class="d-flex align-center pe-2">
        <v-text-field v-model="search" prepend-inner-icon="mdi-magnify" density="compact" label="Search" single-line flat
            hide-details variant="solo-filled"></v-text-field>
    </v-card-title>

    <v-divider></v-divider>

    <v-data-table v-model:search="search" height="500" :items="items" :headers="headers">
        <template v-for="(_, slot) of $slots" v-slot:[slot]="scope">
            <slot :name="slot" v-bind="scope" />
        </template>
    </v-data-table>
</template>


<script setup>
import { ref, defineProps } from 'vue'
defineProps({
    items: Object,
    headers: Object
})

const search = ref('');
</script>

<style scoped></style>