<template>
  <div class="flex items-center">
    <div class="flex-shrink-0 h-8 w-8">
      <img :src="avatar" :alt="user.name" class="h-8 w-8 rounded-full" />
    </div>
    <div class="ml-3">
      <div class="text-sm font-medium text-gray-900">{{ user.name }}</div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
const props = defineProps(['user'])

const avatar = computed(() => {
  
  return `https://api.dicebear.com/5.x/avataaars/svg?seed=${props.user.name}`
})
</script>
