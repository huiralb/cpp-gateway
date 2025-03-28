<!-- src/views/DashboardView.vue -->
<template>
    <div class="p-6">
      <h1 class="text-3xl font-bold mb-6">Dashboard</h1>
      <p>Welcome to the authenticated dashboard!</p>
      <button
        @click="logout"
        class="mt-4 bg-red-500 text-white px-4 py-2 rounded"
      >
        Logout
      </button>
    </div>
  </template>

  <script setup>
  import { useRouter } from 'vue-router'

  const router = useRouter()

  const logout = () => {
    // Remove authentication token
    localStorage.removeItem('token')

    // Redirect to login page
    router.push('/login')
  }
  </script>
