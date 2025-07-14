<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;

class CartService
{
    public function getItems(int $userId): array
    {
        $cart = Cart::with('items.product')
            ->where('user_id', $userId)
            ->first();

        return $cart?->items->map(function ($item) {
            return [
                'product_id' => $item->product_id,
                'quantity' => $item->quantity,
                'price' => $item->price,
                'name' => $item->product->name ?? null
            ];
        })->toArray() ?? [];
    }

    public function addItem(int $userId, string $productId, float $price, int $quantity): void
    {
        $cart = Cart::firstOrCreate(['user_id' => $userId]);

        $existingItem = $cart->items()->where('product_id', $productId)->first();

        if ($existingItem) {
            $existingItem->quantity += $quantity;
            $existingItem->save();
        } else {
            $cart->items()->create([
                'product_id' => $productId,
                'price' => $price,
                'quantity' => $quantity
            ]);
        }
    }

    public function removeItem(int $userId, string $productId): void
    {
        $cart = Cart::where('user_id', $userId)->first();

        if ($cart) {
            $cart->items()->where('product_id', $productId)->delete();
        }
    }

    public function clearCart(int $userId): void
    {
        $cart = Cart::where('user_id', $userId)->first();

        if ($cart) {
            $cart->items()->delete();
        }
    }
}
