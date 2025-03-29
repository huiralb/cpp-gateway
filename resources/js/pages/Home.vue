<!-- src/views/DashboardView.vue -->
<template>
  <div class="py-4 px-6 flex justify-center items-center mx-auto ">
    <h1 class="text-3xl font-bold">CPP Payment Gateway</h1>

    <button @click="logout" class="ml-auto bg-red-500 text-white px-4 py-2 rounded">
      Logout
    </button>
  </div>

  <div class="min-h-screen bg-gray-100 p-4">
    <template v-if="auth.user.role == 'admin'">
      <History />
    </template>

    <template v-if="auth.user.role == 'user'">
      <Payment />
    </template>
  </div>

</template>

<script setup>
import { useRouter } from 'vue-router'
import Payment from '../components/Payment.vue'
import { useAuthStore } from '../stores/auth'
import History from '../components/History.vue'

const router = useRouter()
const auth = useAuthStore()

console.log('auth', auth.user)

const logout = () => {
  // Remove authentication token
  localStorage.removeItem('token')

  // Redirect to login page
  router.push('/login')
}
</script>
