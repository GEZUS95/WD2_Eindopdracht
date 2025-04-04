<script setup>
</script>

<template>
    <nav class="navbar navbar-expand-md navbar-dark bg-dark mb-4">
        <div class="container-fluid">
            <ul class="navbar-nav me-auto mb-2 mb-md-0">
                <li class="nav-item">
                    <router-link to="/" class="nav-link" active-class="active">Home</router-link>
                </li>
                <li class="nav-item">
                    <router-link to="/tickets" class="nav-link" active-class="active">Tickets</router-link>
                </li>
                <li class="login">
                    <router-link to="/login" class="nav-link" active-class="active">Login</router-link>
                </li>
            </ul>
        </div>
    </nav>
</template>

<style>
</style>
