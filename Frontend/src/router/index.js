import { createRouter, createWebHistory } from 'vue-router'
import TicketList from '@/components/Tickets/TicketList.vue'
import Login from '@/components/Users/LoginUser.vue'
import Home from '@/components/Home.vue'
import EditTicket from '@/components/Tickets/EditTicket.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    { path: '/', name: 'home', component: Home },
    { path: '/login', component: Login },
    { path: '/tickets', name: 'tickets', component: TicketList },
    { path: '/editticket/:id', component: EditTicket, props: true  },
  ],
})

export default router
