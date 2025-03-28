<template>
    <div class="min-h-screen flex items-center justify-center bg-gray-100">
        <div class="max-w-md w-full p-6 bg-white rounded-lg shadow-md">
            <h2 class="text-2xl font-bold text-center mb-6">Login</h2>
            <form @submit.prevent="submit" class="space-y-4">
                <input v-model="email" type="email" placeholder="Email" required
                    class="w-full px-3 py-2 border rounded-md" />
                <input v-model="password" type="password" placeholder="Password" required
                    class="w-full px-3 py-2 border rounded-md" />
                <button type="submit" class="w-full cursor-pointer bg-blue-500 text-white py-2 rounded-md hover:bg-blue-600">
                    Login
                </button>
            </form>
        </div>
    </div>
</template>

<script setup>
import { onMounted, ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'
import { toast } from 'vue3-toastify'

const router = useRouter()
const authStore = useAuthStore()
const email = ref('')
const password = ref('')

onMounted(() => {
    email.value = 'user@example.com'
    password.value = 'pa$$word'
})

function submit() {
    authStore.login({ email: email.value, password: password.value })
        .then(({ data }) => {
            if(data.success) {
                toast.success(data.message)
                router.push({ name: 'Dashboard' })
                return;
            }
            toast.error("auth failed")
        })
        .catch((error) => {
            console.error(error)
            toast.error(error.response?.data?.message)
        })

}
</script>
