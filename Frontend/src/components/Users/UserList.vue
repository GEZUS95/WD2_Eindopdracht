<script setup>
import UserListItem from "./UserListItem.vue";
import { onMounted, ref } from 'vue'
import axios from '../../axios-auth'

const users = ref([])
const error = ref(null)
const isLoading = ref(false)

async function loadUsers() {
  isLoading.value = true
  try {
    const response = await axios.get('/users')
    users.value = response.data
  } catch (err) {
    console.error(err)
    error.value = err.message
  }
  finally {
    isLoading.value = false
  }
}

// Load in the products in the mounted hook function, preferably using Axios
onMounted(loadUsers)

function handleDeletedUser() {
  loadUsers()
}

</script>

<template>
    <section>
      <div class="container">
        <div v-if="error" class="alert alert-danger" role="alert">
          {{ error }}
        </div>
        <h2 class="mt-3 mt-lg-5">Users</h2>
        <div v-if="isLoading" class="spinner-border text-primary" role="status">
          <span class="visually-hidden">Loading...</span>
        </div>
          <button type="button" class="btn btn-primary mt-3" @click="$router.push('/createuser');">
              Add user
            </button>
        <div class="row mt-3">
          <UserListItem
            v-for="user in users"
            :key="user.id"
            :user="user"
            @userDeleted="handleDeletedUser"
          />
        </div>
      </div>
    </section>
  </template>

  <style>
  </style>
