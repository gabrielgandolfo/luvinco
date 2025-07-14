<template>
  <div class="min-h-screen bg-gray-100 p-6">
    <h1 class="text-3xl font-bold mb-6 text-center">🛍️ Loja de Produtos</h1>

    <!-- Loading -->
    <div v-if="loading" class="flex justify-center items-center py-12">
      <span class="text-lg text-gray-600">Carregando produtos...</span>
    </div>

    <!-- Erro -->
    <div v-else-if="error" class="text-center text-red-600 py-6">
      <p class="mb-2">⚠️ {{ error }}</p>
      <button
          @click="loadProducts"
          class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
      >
        Tentar Novamente
      </button>
    </div>

    <!-- Conteúdo -->
    <div v-else>
      <!-- Filtros -->
      <div class="flex flex-col md:flex-row items-center justify-between gap-4 mb-6">
        <input
            v-model="search"
            type="text"
            placeholder="Pesquisar produtos..."
            class="w-full md:w-1/3 px-4 py-2 border rounded-md"
        />
        <select v-model="selectedCategory" class="px-4 py-2 border rounded-md w-full md:w-1/4">
          <option value="">Todas as categorias</option>
          <option v-for="category in categories" :key="category" :value="category">
            {{ category }}
          </option>
        </select>
        <select v-model="selectedBrand" class="px-4 py-2 border rounded-md w-full md:w-1/4">
          <option value="">Todas as marcas</option>
          <option v-for="brand in brands" :key="brand" :value="brand">
            {{ brand }}
          </option>
        </select>
      </div>

      <!-- Vitrine -->
      <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        <ProductCard
            v-for="product in filteredProducts"
            :key="product.product_id"
            :product="product"
            @add="addToCart"
        />
      </div>
    </div>

    <!-- Rodapé / Carrinho -->
    <div
        class="fixed bottom-0 left-0 w-full bg-white border-t px-6 py-4 flex flex-col sm:flex-row justify-between items-center shadow-md"
    >
      <p class="text-lg font-semibold">
        🛒 {{ cart.totalItems }} item(s) no carrinho
      </p>
      <div class="flex gap-3 mt-3 sm:mt-0">
        <button
            class="bg-gray-200 text-gray-800 px-4 py-2 rounded hover:bg-gray-300"
            @click="showCart = true"
        >
          Ver Carrinho
        </button>
        <button
            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
            @click="showCart = true"
        >
          Finalizar Pedido
        </button>
      </div>
    </div>

    <!-- Drawer do carrinho -->
    <CartModal :visible="showCart" @close="showCart = false" />
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import ProductCard from '@/components/ProductCard.vue'
import CartModal from '@/components/CartModal.vue'
import { getProducts } from '@/services/products'
import { useCartStore } from '@/store/cart'

type Product = {
  product_id: string
  name: string
  brand: string
  price: number
  image_url: string
  category: string
  stock: number
}

const products = ref<Product[]>([])
const search = ref('')
const selectedCategory = ref('')
const selectedBrand = ref('')
const loading = ref(false)
const error = ref('')
const showCart = ref(false)

const cart = useCartStore()

const loadProducts = async () => {
  loading.value = true
  error.value = ''
  try {
    const res = await getProducts()
    products.value = res
  } catch (err: any) {
    error.value =
        err?.response?.data?.message || 'Erro ao buscar produtos. Verifique a conexão com o servidor.'
  } finally {
    loading.value = false
  }
}

const addToCart = async (product: Product) => {
  await cart.addItem({
    product_id: product.product_id,
    name: product.name,
    price: product.price
  })
  // console.log('Cart após adicionar:', cart.items) // <-- Habilite para debugar
}

const categories = computed(() =>
    Array.from(new Set(products.value.map(p => p.category)))
)

const brands = computed(() =>
    Array.from(new Set(products.value.map(p => p.brand)))
)

const filteredProducts = computed(() => {
  return products.value.filter(p => {
    const matchName = p.name.toLowerCase().includes(search.value.toLowerCase())
    const matchCategory = selectedCategory.value
        ? p.category === selectedCategory.value
        : true
    const matchBrand = selectedBrand.value ? p.brand === selectedBrand.value : true

    return matchName && matchCategory && matchBrand
  })
})

onMounted(async () => {
  await loadProducts()
  await cart.fetchCart()
})
</script>
