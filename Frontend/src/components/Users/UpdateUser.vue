<template>
  <div class="update-user">
    <h1>Update User</h1>
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
        <label for="role">Role:</label>
        <select id="role" v-model="form.role" required>
          <option value="User">User</option>
          <option value="Admin">Admin</option>
          <option value="Support_Agent">Support Agent</option>
        </select>
      </div>
      <button type="submit" class="btn btn-success">Update Ussr</button>
    </form>
  </div>
</template>

<script setup>
import { onMounted, ref } from 'vue';
import { reactive } from 'vue';
import axios from '../../axios-auth';
import { useRouter } from 'vue-router';

const router = useRouter();
const error = ref(null);
const form = reactive({
  username: '',
  email: '',
  birthday: '',
  role: 'User',
});


async function submitUser() {
  const userId = router.currentRoute.value.params.id; // Get the user ID from the URL
  console.log('User updated:', form);
  await axios.put(`/users/${userId}`, form)
    .then(() => {
      console.log('User created successfully');
      router.push('/users'); // Redirect to tickets list
    })
    .catch((error) => {
      console.error('Error updateing user:', error);
      error.value = 'Error updateing user: ' + error.message;
    });

};

async function getUser() {
  const userId = router.currentRoute.value.params.id; // Get the user ID from the URL
  try {
    const response = await axios.get(`/users/${userId}`);
    form.username = response.data.username;
    form.email = response.data.email;
    form.birthday = response.data.birthday;
    form.role = response.data.role;
  } catch (error) {
    console.error('Error fetching user:', error);
    error.value = 'Error fetching user: ' + error.message;
  }
}

onMounted(() => {
  getUser();
});
</script>

<style scoped>
.update-user {
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
