<template>
    <v-app>
      <v-navigation-drawer v-model="drawer" rail permanent expand-on-hover>
        <v-list>
          <v-list-group>
            <template #activator="{props}">
              <v-list-item
                v-bind="props"
                prepend-icon="mdi-train"
              >
                Element 1
              </v-list-item>
            </template>
  
            <v-list-item prepend-icon="mdi-tram">Element 1.1</v-list-item>
            <v-list-item prepend-icon="mdi-tram">Element 1.2</v-list-item>
            <v-list-item prepend-icon="mdi-tram">Element 1.3</v-list-item>
          </v-list-group>
  
          <v-list-group>
            <template #activator="{props}">
              <v-list-item
                v-bind="props"
                prepend-icon="mdi-train"
              >
                Element 2
              </v-list-item>
            </template>
  
            <v-list-item prepend-icon="mdi-tram">Element 2.1</v-list-item>
            <v-list-item prepend-icon="mdi-tram">Element 2.2</v-list-item>
            <v-list-item prepend-icon="mdi-tram">Element 2.3</v-list-item>
          </v-list-group>
        </v-list>
      </v-navigation-drawer>
    </v-app>
  </template>
  
  <script setup>
    import { ref } from 'vue'
  
    const drawer = ref(true)
  </script>