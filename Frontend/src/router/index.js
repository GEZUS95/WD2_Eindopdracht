import { createRouter, createWebHistory } from 'vue-router'
import TicketList from '@/components/Tickets/TicketList.vue'
import Login from '@/components/Users/LoginUser.vue'
import Home from '@/components/Home.vue'
import EditTicket from '@/components/Tickets/EditTicket.vue'
import CreateTicket from '@/components/Tickets/CreateTicket.vue'
import UserList from '@/components/Users/UserList.vue'
import CreateUser from '@/components/Users/CreateUser.vue'
import ViewTicket from '@/components/Tickets/ViewTicket.vue'
import UpdateUser from '@/components/Users/UpdateUser.vue'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    { path: '/', name: 'home', component: Home },
    { path: '/login', component: Login },
    { path: '/tickets', name: 'tickets', component: TicketList },
    { path: '/editticket/:id', component: EditTicket, props: true  },
    { path: '/createticket', component: CreateTicket },
    { path: '/tickets/:id', component: ViewTicket },

    { path: '/users', component: UserList},
    { path: '/createuser', component: CreateUser},
    { path: '/updateuser/:id', component: UpdateUser, props: true },

    {path: '/:pathMatch(.*)*', redirect: '/'}, // Redirect to home for any unmatched routes
  ],
})

export default router
