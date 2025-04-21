<template>
  <div class="create-ticket">
    <h1>Create Ticket</h1>
    <div v-if="error" class="alert alert-danger" role="alert">
      {{ error }}
    </div>
    <form @submit.prevent="submitTicket">
      <div>
        <label for="title">Title:</label>
        <input type="text" id="title" v-model="form.title" required />
      </div>
      <div>
        <label for="description">Description:</label>
        <textarea id="description" v-model="form.description" required></textarea>
      </div>
      <div>
        <label for="status">Status:</label>
        <select id="status" v-model="form.status" required>
          <option value="Open">Open</option>
          <option value="Closed">Closed</option>
          <option value="Unassigned">Unassigned</option>
          <option value="Reopened">Reopened</option>
        </select>
      </div>
      <div>
        <label for="priority">Priority:</label>
        <select id="priority" v-model="form.priority" required>
          <option value="Low">Low</option>
          <option value="Medium">Medium</option>
          <option value="High">High</option>
          <option value="Urgent">Urgent</option>
        </select>
      </div>
      <button type="submit">Create Ticket</button>
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
  title: '',
  description: '',
  status: 'Unassigned',
  priority: 'Low',
});


async function submitTicket() {
  console.log('Ticket created:', form);
  // Add logic to send the ticket to the backend or store it
  await axios.post('/tickets', form)
    .then(() => {
      console.log('Ticket created successfully');
      // Optionally, redirect to the ticket list or show a success message
    })
    .catch((error) => {
      console.error('Error creating ticket:', error);
      error.value = 'Error creating ticket: ' + error.message;
    });
    router.push('/tickets'); // Redirect to tickets list
};
</script>

<style scoped>
.create-ticket {
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
