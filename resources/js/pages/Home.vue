<!-- src/views/DashboardView.vue -->
<template>
  <div class="py-4 px-6 flex justify-center items-center mx-auto ">
    <h1 class="text-3xl font-bold">CPP Payment Gateway</h1>

    <button @click="logout" class="ml-auto bg-red-500 text-white px-4 py-2 rounded">
      Logout
    </button>
  </div>

  <div class="min-h-screen bg-gray-100 p-4">
      <Payment />
  </div>

</template>

<script setup>
import { useRouter } from 'vue-router'
import Payment from '../components/Payment.vue'

const router = useRouter()

const logout = () => {
  // Remove authentication token
  localStorage.removeItem('token')

  // Redirect to login page
  router.push('/login')
}
</script>
