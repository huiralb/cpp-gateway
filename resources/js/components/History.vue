<template>
  <div class="p-6 bg-white rounded-lg shadow">
    <h2 class="text-xl font-bold text-gray-800 mb-6">Transaction History</h2>
    <!-- Search and Filter -->
    <div class="flex flex-col md:flex-row gap-4 mb-6">
      <div class="relative flex-grow">
        <input
          v-model="searchTerm"
          type="text"
          class="w-full pl-10 pr-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
          placeholder="Search Order ID..."
          @keyup.enter="searchTransactions()"
        />
        <div class="absolute left-3 top-2.5 text-gray-400">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
          </svg>
        </div>
      </div>

      <select
        v-model="statusFilter"
        class="px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
        @change="filterTransactions()"
      >
        <option value="all">All Statuses</option>
        <option value="pending">Pending</option>
        <option value="success">Success</option>
        <option value="failed">Failed</option>
      </select>
    </div>

    <Summary v-if="payment.summary" :items="payment.summary"></Summary>

    <!-- Table -->
    <div class="overflow-x-auto">
      <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
          <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Timestamp</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
          </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
          <template v-if="payment.items && payment.items.total > 0">
            <tr v-for="transaction in payment.items.data" :key="transaction.order_id" class="hover:bg-gray-50">
              <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ transaction.order_id }}</td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ formatCurrency(transaction.amount) }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span :class="getTypeClasses(transaction.type)">
                  {{ transaction.type }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap">
                <span :class="getStatusClasses(transaction.status)">
                  {{ transaction.status }}
                </span>
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                {{ formatDate(transaction.created_at) }}
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                <UserAvatar :user="transaction.user" />
              </td>
              <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                <button @click="viewTransaction(transaction)" class="text-blue-600 hover:text-blue-800 mr-3">
                  View
                </button>
                <button v-if="transaction.status === 'pending'" @click="cancelTransaction(transaction)" class="text-red-600 hover:text-red-800">
                  Cancel
                </button>
              </td>
            </tr>
          </template>
          <template v-else>
            <tr>
              <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                No transactions found.
              </td>
            </tr>
          </template>

        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <div v-if="payment.items" class="flex justify-between items-center mt-6">
      <div class="text-sm text-gray-700">
        Showing <span class="font-medium">{{ payment.items.from }} </span>
        to <span class="font-medium">{{ payment.items.to }}</span>
        of <span class="font-medium">{{ payment.items.total }}</span>
        transactions
      </div>
      <div class="flex space-x-2">
        <button @click="previousPage" :disabled="payment.items.current_page === 1" class="px-3 py-1 border rounded-md bg-white text-gray-700 hover:bg-gray-50 disabled:opacity-50">Previous</button>
        <!-- <button class="px-3 py-1 border rounded-md bg-blue-600 text-white hover:bg-blue-700">1</button>
        <button class="px-3 py-1 border rounded-md bg-white text-gray-700 hover:bg-gray-50">2</button>
        <button class="px-3 py-1 border rounded-md bg-white text-gray-700 hover:bg-gray-50">3</button> -->
        <button @click="nextPage" :disabled="payment.items.current_page === payment.items.last_page" class="px-3 py-1 border rounded-md bg-white text-gray-700 hover:bg-gray-50 disabled:opacity-50">Next</button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive } from 'vue'
import UserAvatar from './column/UserAvatar.vue';
import Summary from './Summary.vue';
import { usePaymentStore } from '../stores/payment';
import { onMounted } from 'vue';
import { formatDate, formatCurrency, getStatusClasses, getTypeClasses } from '../utils/formatter';

const payment = usePaymentStore()
const params = reactive({
  page: 1,
  search: '',
  status: 'all'
})
const page = ref(1)

const searchTerm = ref('')
const statusFilter = ref('all')

onMounted(() => {
  payment.get( params )
})

const nextPage = () => {
  if(params.page === payment.items?.last_page) return
  params.page++
  payment.get( params )
}

const previousPage = () => {
  if(params.page === 1) return
  params.page--
  payment.get( params )
}

const searchTransactions = () => {
  params.search = searchTerm.value
  payment.get( params )
}

const filterTransactions = () => {
  params.status = statusFilter.value
  payment.get( params )
}

// Action methods
const viewTransaction = (transaction) => {
  console.log('View transaction', transaction.order_id)
  // Implement view logic
}

const cancelTransaction = (transaction) => {
  console.log('Cancel transaction', transaction.order_id)
  // Implement cancel logic
}
</script>
