<template>
  <v-app>
    
    <Navbar />
    <div class="bg-grey-lighten-4 h-100">
      <v-main class="my-4 mx-4">
        <v-card class="mx-auto w-100 h-100" elevation="3" scrollable>
          <v-card-text>
            <router-view />
          </v-card-text>
        </v-card>
      </v-main>
    </div>

  </v-app>
</template>

<script setup>
import Navbar from './components/Navbar.vue';
</script>
<style>
body{
 font-family: "Poppins",sans-serif;
}
</style>
