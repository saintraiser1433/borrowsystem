<template>
    <v-navigation-drawer app color="blue-grey-darken-3" :width="300">
        <v-list-item prepend-avatar="/images/polomolok.png">
            <v-list-item-title class="text-grey-lighten-3">
                <span class="text-teal-darken-1 font-weight-bold fontSmall">Norala National</span>
                <span class="fontSmall"> High School</span>
            </v-list-item-title>
            <v-list-item-subtitle class="text-grey-lighten-5 fontSmallSubtitle">
                Advance Borrowing System V1.0
            </v-list-item-subtitle>
        </v-list-item>
        <v-divider></v-divider>
        <v-list>
            <v-list-item class="compact font-weight-bold" link v-for="i in item" :key="i.title" :to="i.path" >
                <template #prepend>
                    <v-icon color="text-grey">{{ i.icon }}</v-icon>
                </template>
                <v-list-item-title>{{ i.title }}</v-list-item-title>
            </v-list-item>
        </v-list>
    </v-navigation-drawer>
</template>

<script setup>
import {ref} from 'vue';
const item = ref([
                {
                    icon: 'mdi-monitor-dashboard',
                    title: 'Dashboard',
                    path: '/'
                },
                {
                    icon: 'mdi-account-multiple',
                    title: 'Student',
                    path: '/student'
                },
                {
                    icon: 'mdi-package-variant',
                    title: 'Category',
                    path: '/category'
                },
                {
                    icon: 'mdi-package-variant',
                    title: 'Inventory',
                    path: '/inventory'
                },
                {
                    icon: 'mdi-keyboard-return',
                    title: 'Returned',
                    path: '/returned'
                },
                {
                    icon: 'mdi-source-branch',
                    title: 'Transactions',
                    path: '/transaction'
                },
                {
                    icon: 'mdi-file-chart',
                    title: 'Reports',
                    path: '/report'
                },


                // {
                //     icon: 'mdi-target-account',
                //     title: 'Blotter',
                //     subtitle:'Sample 4',
                //     path: '/blotter'
                    
                // },
                // {
                //     icon: 'mdi-note',
                //     title: 'Clearance',
                //     subtitle:'Sample 5',
                //     path: '/clearance'
                // },
                // {
                //     icon: 'mdi-archive-arrow-down',
                //     title: 'Archive',
                //     subtitle:'Sample 6',
                //     path: '/archive'
                // },
                // {
                //     icon: 'mdi-cogs',
                //     title: 'Settings',
                //     subtitle:'Sample 7',
                //     path: '/settings'
                // },
                // {
                //     icon: 'mdi-account-details-outline',
                //     title: 'Account Settings',
                //     subtitle:'Sample 8',
                //     path: '/accsettings'
                // },
                // {
                //     icon: 'mdi-database-arrow-down',
                //     title: 'Back-up Database',
                //     subtitle:'Sample 9',
                //     path: '/backupdb'
                // }
            ])


</script>

<style scoped>
    .fontSmall{
        font-size:14px;
    }
    .fontSmallSubtitle{
        font-size:12px;
    }
</style>