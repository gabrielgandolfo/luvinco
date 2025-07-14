import api from './api'

export type Product = {
    product_id: string
    name: string
    brand: string
    price: number
    image_url: string
    category: string
    stock: number
}

export async function getProducts(): Promise<Product[]> {
    const res = await api.get('/products')
    return res.data.data ?? res.data
}
