<template>
  <div class="edit-ticket">
    <div v-if="error" class="alert alert-danger" role="alert">
          {{ error }}
    </div>
    <div v-if="isLoading" class="spinner-border text-primary" role="status">
      <span class="visually-hidden">Loading...</span>
    </div>
    <h1>Edit Ticket</h1>
    <form @submit.prevent="handleSubmit">
      <div>
        <label for="title">Title:</label>
        <input id="title" v-model="form.title" type="text" disabled />
      </div>
      <div>
        <label for="description">Description:</label>
        <textarea id="description" v-model="form.description" disabled></textarea>
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
      <button type="submit">Save</button>
    </form>
  </div>
</template>

<script setup>
import { reactive, onMounted, ref } from 'vue';
import { useRouter } from 'vue-router';
import axios from '../../axios-auth';

const router = useRouter();
const error = ref(null)
const isLoading = ref(false)

const form = reactive({
  title: '',
  description: '',
  status: 'Open',
  priority: 'Low',
});

async function handleSubmit() {
  try {
    const ticketId = router.currentRoute.value.params.id; // Get the ticket ID from the URL
    await axios.put(`/tickets/${ticketId}`, {
      status: form.status,
      priority: form.priority,
    });
    console.log('Ticket updated successfully');
    router.push('/tickets'); // Redirect to tickets list
  } catch (error) {
    console.error('Error updating ticket:', error);
    error.value = 'Error updating ticket: ' + error.message;
  }
}

async function loadTicket(){
  const ticketId = router.currentRoute.value.params.id; // Get the ticket ID from the URL
  isLoading.value = true
  try {
    const response = await axios.get(`/tickets/${ticketId}`);
    const tick = response.data;
    form.title = tick.title;
    form.description = tick.description;
    form.status = tick.status;
    form.priority = tick.priority;
  } catch (error) {
    console.error('Error fetching ticket:', error);
    error.value = 'Error fetching ticket: ' + error.message;
  } finally {
    isLoading.value = false
  }
}

onMounted(loadTicket);
</script>

<style scoped>
.edit-ticket {
  max-width: 600px;
  margin: 0 auto;
}

form div {
  margin-bottom: 1rem;
}

label {
  display: block;
  font-weight: bold;
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
  background-color: #007bff;
  color: white;
  border: none;
  cursor: pointer;
}

button:hover {
  background-color: #0056b3;
}

#title,
#description {
  background-color: #f8f9fa;
  border: 1px solid #ced4da;
  border-radius: 0.25rem;
}
</style>
