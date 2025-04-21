<script setup>
import 'bootstrap/dist/css/bootstrap.min.css';
import { RouterView } from 'vue-router'
import Navigation from './components/Navigation.vue'
</script>

<template>
  <Navigation />
  <RouterView />
</template>

<style scoped></style>
