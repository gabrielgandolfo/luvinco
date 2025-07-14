<template>
  <div
      v-if="visible"
      class="fixed top-0 right-0 w-full sm:w-96 h-full bg-white shadow-lg z-50 flex flex-col"
  >
    <div class="flex justify-between items-center p-4 border-b">
      <h2 class="text-xl font-semibold">🛒 Carrinho</h2>
      <button @click="$emit('close')" class="text-gray-500 hover:text-black text-2xl">&times;</button>
    </div>

    <div class="flex-1 overflow-y-auto p-4">
      <div v-if="items.length === 0" class="text-center text-gray-500">
        Carrinho vazio
      </div>

      <div v-else class="space-y-4">
        <div
            v-for="item in items"
            :key="item.product_id"
            class="flex justify-between items-center border-b pb-2"
        >
          <div>
            <h3 class="font-semibold">{{ item.name }}</h3>
            <p class="text-sm text-gray-500">
              {{ item.quantity }}x {{ formatPrice(item.price) }}
            </p>
          </div>
          <button
              @click="remove(item.product_id)"
              class="text-red-600 hover:text-red-800"
          >
            Remover
          </button>
        </div>
      </div>
    </div>

    <div class="p-4 border-t">
      <p class="font-semibold mb-2">Total: {{ formatPrice(totalPrice) }}</p>
      <button
          @click="submitOrder"
          :disabled="loading || items.length === 0"
          class="w-full bg-green-600 text-white py-2 rounded hover:bg-green-700 disabled:opacity-50"
      >
        {{ loading ? 'Enviando...' : 'Finalizar Pedido' }}
      </button>
      <p
          v-if="message"
          :class="success ? 'text-green-600' : 'text-red-600'"
          class="mt-2 text-center"
      >
        {{ message }}
      </p>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed, ref } from 'vue'
import { useCartStore } from '@/store/cart'
import { submitOrder as submitOrderApiRequest } from '@/services/orders'

defineProps<{ visible: boolean }>()
defineEmits(['close'])

const cart = useCartStore()
const items = computed(() => cart.items)
const totalPrice = computed(() => cart.totalPrice)

const loading = ref(false)
const message = ref('')
const success = ref(false)

const formatPrice = (v: number) =>
    v.toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' })

const remove = async (id: string) => {
  await cart.removeFromCart(id)
}

const submitOrder = async () => {
  loading.value = true
  message.value = ''
  try {
    const payload = items.value.map(i => ({
      product_id: i.product_id,
      quantity: i.quantity
    }))
    const res = await submitOrderApiRequest(payload)
    success.value = true
    message.value = res.message || 'Pedido enviado com sucesso!'
    await cart.clearCart()
  } catch (e: any) {
    console.error('Erro ao enviar pedido:', e)
    success.value = false
    message.value = e?.response?.data?.message || 'Erro ao enviar pedido.'
  } finally {
    loading.value = false
  }
}
</script>
