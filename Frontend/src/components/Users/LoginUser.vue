<template>
  <div class="login-user">
    <h1>Login Ticketinator</h1>
    <form @submit.prevent="handleLogin">
      <div class="form-group">
        <label for="username">username:</label>
        <input
          type="username"
          id="username"
          v-model="username"
          placeholder="Enter your username"
          required
        />
      </div>
      <div class="form-group">
        <label for="password">Password:</label>
        <input
          type="password"
          id="password"
          v-model="password"
          placeholder="Enter your password"
          required
        />
      </div>
      <button type="submit">Login</button>
    </form>
  </div>
</template>
<script setup>
import { ref } from "vue";
import axios from "../../axios-auth";
import router from "@/router";

const username = ref("");
const password = ref("");

async function handleLogin() {
  try {
        const response = await axios.post('users/login', {
            username: username.value,
            password: password.value
        });
        // Handle successful login (e.g., redirect to another page)
        localStorage.setItem('token', response.data.token);
        localStorage.setItem('userEmail', response.data.email);
        localStorage.setItem('userName', response.data.username);
        localStorage.setItem('userRole', response.data.role);
        localStorage.setItem('loggedin', true);

        axios.defaults.headers.common['Authorization'] = "Bearer " + response.data.token;
        router.push('/tickets').then(() => {
            window.location.reload();
        });
    } catch (error) {
        console.error('Login failed:', error);
        // Handle login failure (e.g., show error message)
    }
}
</script>

<style scoped>
.login {
  max-width: 400px;
  margin: 0 auto;
  padding: 20px;
  border: 1px solid #ccc;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.form-group {
  margin-bottom: 15px;
}

label {
  display: block;
  margin-bottom: 5px;
  font-weight: bold;
}

input {
  width: 100%;
  padding: 8px;
  box-sizing: border-box;
}

button {
  width: 100%;
  padding: 10px;
  background-color: #007bff;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

button:hover {
  background-color: #0056b3;
}
</style>
