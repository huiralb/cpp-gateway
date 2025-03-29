<template>
  <div>
    <div class="flex justify-center text-center p-6 mb-4">
      <h3 class="text-2xl font-bold">Balance: ${{ balanceFormatted }}</h3>
    </div>
    <div class="flex items-center justify-center">
      <div class="w-full max-w-4xl grid md:grid-cols-2 gap-4 shadow-xl rounded-2xl overflow-hidden">
        <!-- Deposit Column (Light Background) -->
        <div class="bg-white p-6 md:p-10 flex flex-col justify-between">
          <div>
            <h2 class="text-2xl font-bold text-blue-600 mb-6">Deposit</h2>

            <div class="space-y-4">
              <div>
                <label class="block text-gray-700 mb-2">Amount ($)</label>
                <input v-model="depositAmount" type="number"
                  class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                  placeholder="Enter deposit amount" />
              </div>

              <!-- <div>
                        <label class="block text-gray-700 mb-2">Payment Method</label>
                        <select v-model="depositMethod"
                          class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                          <option value="">Select Method</option>
                          <option value="bank">Bank Transfer</option>
                          <option value="credit">Credit Card</option>
                          <option value="crypto">Cryptocurrency</option>
                        </select>
                      </div> -->
            </div>
          </div>

          <button @click="handleDeposit"
            class="w-full mt-6 bg-blue-600 text-white py-3 rounded-lg hover:bg-blue-700 transition duration-300 disabled:opacity-50" :disabled="isDisabledDeposit">
            Deposit Funds
          </button>
        </div>

        <!-- Withdraw Column (Dark Gradient Background) -->
        <div class="bg-gradient-to-br from-gray-800 to-gray-900 text-white p-6 md:p-10 flex flex-col justify-between">
          <div>
            <h2 class="text-2xl font-bold text-white mb-6">Withdraw</h2>

            <div class="space-y-4">
              <div>
                <label class="block text-gray-300 mb-2">Amount ($)</label>
                <input v-model="withdrawAmount" type="number"
                  class="w-full px-4 py-2 bg-gray-700 border-none rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                  placeholder="Enter withdraw amount" />
              </div>

              <!-- <div>
                      <label class="block text-gray-300 mb-2">Withdrawal Method</label>
                      <select v-model="withdrawMethod"
                        class="w-full px-4 py-2 bg-gray-700 border-none rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="" class="text-gray-400">Select Method</option>
                        <option value="bank" class="text-white">Bank Transfer</option>
                        <option value="crypto" class="text-white">Cryptocurrency</option>
                      </select>
                    </div> -->
            </div>
          </div>

          <button @click="handleWithdraw"
            class="w-full mt-6 bg-blue-500 text-white py-3 rounded-lg hover:bg-blue-600 transition duration-300">
            Withdraw Funds
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed, ref, onMounted } from 'vue'
import { toast } from 'vue3-toastify'
import { usePaymentStore } from '../stores/payment'
import { usePocketStore } from '../stores/pocket'

const paymentStore = usePaymentStore()
const pocketStore = usePocketStore()
// Deposit state
const depositAmount = ref('')

// Withdraw state
const withdrawAmount = ref('')
const withdrawMethod = ref('')

// Balance state
const balance = ref(0)
const balanceFormatted = computed(() => pocketStore.balance)

const isDisabledDeposit = computed(() => {
  return depositAmount.value <= 0 || progressDeposit.value
})

const progressDeposit = ref(false)

onMounted(() => {
  // balance.value = pocketStore.balance
  pocketStore.get()
})

// Methods
const handleDeposit = async () => {
  // Basic validation
  if (!depositAmount.value) {
    toast.error('Please fill in all deposit fields')
    return
  }
  if (depositAmount.value <= 0) {
    toast.error('Deposit amount must be greater than 0')
    return
  }
  try {
    progressDeposit.value = true
    const { data } = await paymentStore.send({amount: depositAmount.value, type: 'deposit'})

    if(data.success) {
      toast.success('Deposit successful')
      depositAmount.value = null
      pocketStore.get()
      return
    }
    throw "deposit failed";
  } catch (error) {
    toast.error(error)
  } finally {
    progressDeposit.value = false
  }
}

const handleWithdraw = () => {
  // Basic validation
  if (!withdrawAmount.value || !withdrawMethod.value) {
    alert('Please fill in all withdrawal fields')
    return
  }

  // TODO: Implement actual withdrawal logic
  console.log('Withdraw', {
    amount: withdrawAmount.value,
    method: withdrawMethod.value
  })
}
</script>
