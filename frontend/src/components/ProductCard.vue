<template>
  <div class="bg-white rounded-lg shadow-md p-4 hover:shadow-lg transition-shadow">
    <div class="aspect-square bg-gray-100 rounded-md mb-4 flex items-center justify-center overflow-hidden">
      <img
          v-if="product.image_url"
          :src="product.image_url"
          :alt="product.name"
          class="w-full h-full object-cover"
          @error="onImageError"
      >
      <div v-else class="text-gray-400 text-4xl">📦</div>
    </div>

    <div class="space-y-2">
      <h3 class="font-semibold text-lg line-clamp-2">{{ product.name }}</h3>
      <p class="text-sm text-gray-600">{{ product.brand }}</p>
      <p class="text-xs text-gray-500 capitalize">{{ product.category }}</p>

      <div class="flex items-center justify-between">
        <p class="text-xl font-bold text-blue-600">
          {{ formatPrice(product.price) }}
        </p>
        <p class="text-sm text-gray-500">
          {{ product.stock }} em estoque
        </p>
      </div>

      <button
          @click="handleAddToCart"
          :disabled="loading || product.stock === 0"
          class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
      >
        <span v-if="loading">Adicionando...</span>
        <span v-else-if="product.stock === 0">Fora de Estoque</span>
        <span v-else>Adicionar ao Carrinho</span>
      </button>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import type { Product } from '@/services/products'

interface Props {
  product: Product
}

const props = defineProps<Props>()
const emit = defineEmits<{
  add: [product: Product]
}>()

const loading = ref(false)
const imageError = ref(false)

const formatPrice = (value: number) => {
  return value.toLocaleString('pt-BR', {
    style: 'currency',
    currency: 'BRL'
  })
}

const handleAddToCart = async () => {
  if (loading.value || props.product.stock === 0) return

  loading.value = true
  try {
    await emit('add', props.product)
    // Pequeno delay para feedback visual
    setTimeout(() => {
      loading.value = false
    }, 500)
  } catch (error) {
    console.error('Erro ao adicionar produto:', error)
    loading.value = false
  }
}

const onImageError = () => {
  imageError.value = true
}
</script>

<style scoped>
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}
</style>