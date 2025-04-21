<template>
  <div class="user-list-item">
    <!-- Add your template content here -->
    <div class="card" style="width: 18rem;">
      <div class="card-body">
        <h5 class="card-title">{{ user.username }}</h5>
        <h6 class="card-subtitle mb-2 text-muted">{{ user.email }}</h6>
        <p class="card-text">{{ user.role }}</p>
        <button class="btn btn-primary" @click="editUser(user.id)">Edit</button>
        <button class="btn btn-danger" @click="deleteUser(user.id)">Delete</button>
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
    user: Object
})

const emit = defineEmits(["userDeleted"])

async function deleteUser(id) {
    // Use axios to delete the product
    try { await axios.delete('/users/' + id)
        .then(() => {
            // Remove the product from the products array
            emit('userDeleted');
        });
    }
    catch (error) { console.error(error); }

};
function editUser(id) {
    // Use the router to navigate to the editproduct route and pass the id
    router.push('/updateUser/' + id);
};


</script>

<style scoped>

</style>
