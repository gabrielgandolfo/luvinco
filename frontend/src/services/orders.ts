import api from './api'

export interface OrderItem {
    product_id: string
    quantity: number
}

export interface OrderResponse {
    message: string
    order_id?: string
    total?: number
}

export const submitOrder = async (items: OrderItem[]): Promise<OrderResponse> => {
    const response = await api.post('/orders', {
        items: items
    })
    return response.data
}