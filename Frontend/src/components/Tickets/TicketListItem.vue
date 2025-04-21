<template>
  <div class="ticket-list-item">
    <!-- Add your template content here -->
    <div class="card" style="width: 18rem;" >
      <div class="card-body">
        <h5 class="card-title">{{ ticket.title }}</h5>
        <h6 class="card-subtitle mb-2 text-muted">{{ ticket.category }}</h6>
        <p class="card-text">{{ ticket.description }}</p>
        <button class="btn btn-secondary" @click="viewTicket(ticket.id)">View</button>
        <button class="btn btn-primary" @click="editTicket(ticket.id)">Edit</button>
        <button class="btn btn-danger" @click="deleteTicket(ticket.id)">Delete</button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { defineProps, defineEmits } from 'vue'
import axios from '../../axios-auth'
import { useRouter } from 'vue-router'

const router = useRouter()

defineProps({
    ticket: Object
})

const emit = defineEmits(["ticketDeleted"])

async function deleteTicket(id) {
    // Use axios to delete the product
    try { await axios.delete('/tickets/' + id)
        .then(() => {
            // Remove the product from the products array
            emit('ticketDeleted');
        });
    }
    catch (error) { console.error(error); }

};
function editTicket(id) {
    // Use the router to navigate to the editproduct route and pass the id
    router.push('/editticket/' + id);
};

function viewTicket(id) {
    // Use the router to navigate to the editproduct route and pass the id
    router.push('/tickets/' + id);
};

</script>

<style scoped>
</style>
