import { defineStore } from 'pinia'
import api from '@/services/api'

type CartItem = {
    product_id: string
    name: string
    price: number
    quantity: number
}

export const useCartStore = defineStore('cart', {
    state: () => ({
        items: [] as CartItem[]
    }),

    actions: {
        async fetchCart() {
            const res = await api.get('/cart')
            this.items = res.data.data
        },

        async addItem(product: { product_id: string; name: string; price: number }, quantity = 1) {
            await api.post('/cart', {
                product_id: product.product_id,
                price: product.price,
                quantity
            })

            await this.fetchCart()
        },

        async removeFromCart(product_id: string) {
            await api.delete(`/cart/${product_id}`)
            await this.fetchCart()
        },

        async clearCart() {
            await api.delete('/cart')
            this.items = []
        }
    },

    getters: {
        totalPrice: (state) =>
            state.items.reduce((total, i) => total + i.price * i.quantity, 0),

        totalItems: (state) =>
            state.items.reduce((total, i) => total + i.quantity, 0)
    }
})
