<template>
  <div class="view-ticket">
    <div v-if="error" class="alert alert-danger" role="alert">
          {{ error }}
    </div>
    <div v-if="isLoading" class="spinner-border text-primary" role="status">
      <span class="visually-hidden">Loading ticket...</span>
    </div>
    <h1>Ticket</h1>
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
        <select id="status" v-model="form.status" required disabled>
          <option value="Open">Open</option>
          <option value="Closed">Closed</option>
          <option value="Unassigned">Unassigned</option>
          <option value="Reopened">Reopened</option>
        </select>
      </div>
      <div>
        <label for="priority">Priority:</label>
        <select id="priority" v-model="form.priority" required disabled>
          <option value="Low">Low</option>
          <option value="Medium">Medium</option>
          <option value="High">High</option>
          <option value="Urgent">Urgent</option>
        </select>
      </div>
    </form>
  </div>
  <div class="messages">
    <h2>Messages</h2>
    <div v-if="isLoadingMessages" class="spinner-border text-primary" role="status">
      <span class="visually-hidden">Loading messages...</span>
    </div>
    <form @submit.prevent="handleSubmit">
      <div>
        <label for="message">Message:</label>
        <input id="message" v-model="formMessage.message" type="text" required />
      </div>
      <button type="submit">Send Message</button>
    </form>
    <ul>
      <li v-for="message in messages" :key="message.id" class="card mb-3 border-primary message">
        <div class="card-body">
          <p class="card-text">{{ message.message }}</p>
          <p class="card-text">
        <small class="text-muted">By: {{ message.user.username }} on {{ message.created_at }}</small>
          </p>
        </div>
      </li>
    </ul>
    </div>
</template>

<script setup>
import { reactive, onMounted, ref } from 'vue';
import { useRouter } from 'vue-router';
import axios from '../../axios-auth';

const router = useRouter();
const ticket = ref(null);
const messages = ref([]);
const error = ref(null);
const isLoading = ref(false);
const isLoadingMessages = ref(false);

const form = reactive({
  title: '',
  description: '',
  status: 'Open',
  priority: 'Low',
});

const formMessage = reactive({
  message: '',
});

async function handleSubmit() {
  try {
    const ticketId = router.currentRoute.value.params.id; // Get the ticket ID from the URL
    await axios.post(`/tickets/${ticketId}/reply`, {
     message: formMessage.message,
    });
    console.log('Message successfully sent');
    loadMessages(); // Reload messages after sending a new one
    formMessage.message = ''; // Clear the message input
  } catch (error) {
    console.error('Error sending message:', error);
    error.value = 'Error sending message: ' + error.message;
  }
}

async function loadTicket(){
  isLoading.value = true
  try {
    const ticketId = router.currentRoute.value.params.id; // Get the ticket ID from the URL
    const response = await axios.get(`/tickets/${ticketId}`);
    ticket.value = response.data;

    form.title = ticket.value.title;
    form.description = ticket.value.description;
    form.status = ticket.value.status;
    form.priority = ticket.value.priority;

  } catch (error) {
    console.error('Error fetching ticket:', error);
    error.value = 'Error fetching ticket: ' + error.message;
  } finally {
    isLoading.value = false
  }
}

async function loadMessages() {
  isLoadingMessages.value = true
  try {
    const ticketId = router.currentRoute.value.params.id; // Get the ticket ID from the URL
    const response = await axios.get(`/tickets/${ticketId}/messages`);
    messages.value = response.data;
  } catch (error) {
    console.error('Error fetching messages:', error);
    error.value = 'Error fetching messages: ' + error.message;
  } finally {
    isLoadingMessages.value = false
  }
}

onMounted(() => {
  loadTicket();
  loadMessages();
});

</script>

<style scoped>
.edit-ticket {
  max-width: 600px;
  margin: 0 auto;
}

.message {
  margin: 10px;
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
/*
#title,
#description {
  background-color: #f8f9fa;
  border: 1px solid #ced4da;
  border-radius: 0.25rem;
} */
</style>
