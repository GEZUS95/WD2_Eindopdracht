<script setup>
import TicketListItem from "./TicketListItem.vue";
import { onMounted, ref } from 'vue'
import axios from '../../axios-auth'

const tickets = ref([])
const error = ref(null)
const isLoading = ref(false)

async function loadTickets() {
  isLoading.value = true
  try {
    const response = await axios.get('/tickets')
    tickets.value = response.data
  } catch (err) {
    console.error(err)
    error.value = err.message
  }
  finally {
    isLoading.value = false
  }
}

// Load in the products in the mounted hook function, preferably using Axios
onMounted(loadTickets)

function handleDeletedTicket() {
  loadTickets()
}

</script>

<template>
    <section>
      <div class="container">
        <div v-if="error" class="alert alert-danger" role="alert">
          {{ error }}
        </div>
        <h2 class="mt-3 mt-lg-5">Tickets</h2>
        <div v-if="isLoading" class="spinner-border text-primary" role="status">
          <span class="visually-hidden">Loading...</span>
        </div>
          <button type="button" class="btn btn-primary mt-3" @click="$router.push('/createticket');">
              Add ticket
            </button>
        <div class="row mt-3">
          <TicketListItem
            v-for="ticket in tickets"
            :key="ticket.id"
            :ticket="ticket"
            @ticketDeleted="handleDeletedTicket"
          />
        </div>
      </div>
    </section>
  </template>

  <style>
  </style>
