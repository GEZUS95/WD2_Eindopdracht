<template>
    <div>
        <h2>Login</h2>
        <form @submit.prevent="login">
            <div>
                <label for="username">Username:</label>
                <input type="text" v-model="username" id="username" required />
            </div>
            <div>
                <label for="password">Password:</label>
                <input type="password" v-model="password" id="password" required />
            </div>
            <button type="submit">Login</button>
        </form>
    </div>
</template>

<script setup>
import axios from '../axios-auth';

import { defineComponent, ref } from 'vue';

const username = ref('');
const password = ref('');

const login = async () => {
    try {
        const response = await axios.post('users/login', {
            username: username.value,
            password: password.value
        });
        console.log(response.data);
        // Handle successful login (e.g., redirect to another page)

        localStorage.setItem('token', response.data);
        axios.defaults.headers.common['Authorization'] = "Bearer " + response.data;

    } catch (error) {
        console.error('Login failed:', error);
        // Handle login failure (e.g., show error message)
    }
};

defineComponent({
    setup() {
        return {
            username,
            password,
            login
        };
    }
});
</script>

<style scoped>
/* Add your styles here */
</style>
