<script setup>
import router from '@/router';
import { computed } from 'vue';

const userRole = computed(() => typeof localStorage !== 'undefined' ? localStorage.getItem('userRole') : null);
const token = computed(() => typeof localStorage !== 'undefined' ? localStorage.getItem('token') : null);
const userName = computed(() => typeof localStorage !== 'undefined' ? localStorage.getItem('userName') : null);

function logout() {
    localStorage.clear();
    router.push('/login').then(() => {
        window.location.reload();
    });
}
</script>

<template>
    <nav class="navbar navbar-expand-md navbar-dark bg-dark mb-4">
        <div class="container-fluid">
            <ul class="navbar-nav me-auto mb-2 mb-md-0">
                <li class="nav-item">
                    <router-link to="/" class="nav-link" active-class="active">Home</router-link>
                </li>
                <li class="nav-item" v-if="token">
                    <router-link to="/createticket" class="nav-link" active-class="active">Create Ticket</router-link>
                </li>
                <li class="nav-item" v-if="userRole === 'Admin'">
                    <router-link to="/users" class="nav-link" active-class="active">Users</router-link>
                </li>
                <li class="nav-item" v-if="userRole === 'Admin'">
                    <router-link to="/createuser" class="nav-link" active-class="active">Create User</router-link>
                </li>
                <li class="nav-item" v-if="userRole === 'Admin'">
                    <router-link to="/tickets" class="nav-link" active-class="active">Tickets</router-link>
                </li>
                  <li class="logout" v-if="token">
                        <a href="#" class="nav-link" @click.prevent="logout">{{ userName }}</a>
                  </li>
                  <li class="nav-item" v-if="!token">
                      <router-link to="/login" class="nav-link" active-class="active">Login</router-link>
                  </li>
            </ul>
        </div>
    </nav>
</template>

<style>
</style>
