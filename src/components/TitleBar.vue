<template>
    <v-list class="w-auto">
      <v-list-item>
        <template #prepend>
            <v-icon>mdi-human-child</v-icon>
        </template>
        <v-list-item-title>
          <h3 class="text-grey-darken-1">{{ $route.meta.title }}</h3>
        </v-list-item-title>
        <v-list-item-subtitle class="text-capitalize">
          {{ $route.meta.subtitle }}
        </v-list-item-subtitle>
        
      </v-list-item>
    </v-list>
</template>

<script setup>

</script>

<style scoped></style>