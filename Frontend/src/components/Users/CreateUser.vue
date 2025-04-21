<template>
  <div class="create-user">
    <h1>Create User</h1>
    <div v-if="error" class="alert alert-danger" role="alert">
      {{ error }}
    </div>
    <form @submit.prevent="submitUser">
      <div>
        <label for="name">Username:</label>
        <input type="text" id="name" v-model="form.username" required />
      </div>
      <div>
        <label for="email">Email:</label>
        <input type="email" id="email" v-model="form.email" required />
      </div>
      <div>
        <label for="birthday">Birthday:</label>
        <input type="date" id="birthday" v-model="form.birthday" required />
      </div>
      <div>
        <label for="password">Password:</label>
        <input type="password" id="password" v-model="form.password" required />
      </div>
      <div>
        <label for="role">Role:</label>
        <select id="role" v-model="form.role" required>
          <option value="User">User</option>
          <option value="Admin">Admin</option>
          <option value="Support_Agent">Support Agent</option>
        </select>
      </div>
      <button type="submit" class="btn btn-success">Create Ussr</button>
    </form>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { reactive } from 'vue';
import axios from '../../axios-auth';
import { useRouter } from 'vue-router';

const router = useRouter();
const error = ref(null);
const form = reactive({
  username: '',
  email: '',
  birthday: '',
  password: '',
  role: 'User',
});


async function submitUser() {
  console.log('User created:', form);
  // Add logic to send the ticket to the backend or store it
  await axios.post('/users', form)
    .then(() => {
      console.log('User created successfully');
      console.log(form);
      router.push('/users'); // Redirect to tickets list
    })
    .catch((error) => {
      console.error('Error creating user:', error);
      error.value = 'Error creating user: ' + error.message;
    });

};
</script>

<style scoped>
.create-user {
  max-width: 400px;
  margin: 0 auto;
}

form div {
  margin-bottom: 1rem;
}

label {
  display: block;
  margin-bottom: 0.5rem;
}

input,
textarea,
select {
  width: 100%;
  padding: 0.5rem;
  font-size: 1rem;
}

button {
  padding: 0.5rem 1rem;
  font-size: 1rem;
  cursor: pointer;
}
</style>
