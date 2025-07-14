<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CartService;

class CartController extends BaseController
{
    protected CartService $cart;

    public function __construct(CartService $cart)
    {
        $this->cart = $cart;
    }

    /**
     * @group Carrinho
     *
     * Listar itens do carrinho
     *
     * Retorna todos os itens do carrinho do usuário atual (simulado como user_id: 1).
     *
     * @response 200 {
     *   "success": true,
     *   "message": "Carrinho recuperado com sucesso",
     *   "data": [
     *     {
     *       "product_id": "12345",
     *       "quantity": 2,
     *       "price": 79.99
     *     }
     *   ]
     * }
     */
    public function index()
    {
        $userId = 1;

        $cartItems = $this->cart->getItems($userId);

        return response()->json([
            'success' => true,
            'message' => count($cartItems) ? 'Carrinho recuperado com sucesso' : 'Carrinho vazio',
            'data' => $cartItems,
            'session' => "user:$userId"
        ]);
    }

    /**
     * @group Carrinho
     *
     * Adicionar item ao carrinho
     *
     * Adiciona um produto ao carrinho. Se o produto já estiver presente, incrementa a quantidade.
     *
     * @bodyParam product_id string required O ID do produto. Example: "12345"
     * @bodyParam price number required Preço do produto. Example: 79.99
     * @bodyParam quantity integer Quantidade (padrão: 1). Example: 2
     *
     * @response 200 {
     *   "success": true,
     *   "message": "Item adicionado ao carrinho",
     *   "data": []
     * }
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|string',
            'price' => 'required|numeric',
            'quantity' => 'sometimes|integer|min:1',
        ]);

        $userId = 1;

        $this->cart->addItem(
            $userId,
            $validated['product_id'],
            $validated['price'],
            $validated['quantity'] ?? 1
        );

        return $this->successResponse([], 'Item adicionado ao carrinho');
    }

    /**
     * @group Carrinho
     *
     * Remover item do carrinho
     *
     * Remove um produto do carrinho pelo seu product_id.
     *
     * @urlParam productId string required O ID do produto a ser removido. Example: "12345"
     *
     * @response 200 {
     *   "success": true,
     *   "message": "Item removido do carrinho",
     *   "data": []
     * }
     */
    public function destroy(string $productId)
    {
        $userId = 1;

        $this->cart->removeItem($userId, $productId);

        return $this->successResponse([], 'Item removido do carrinho');
    }

    /**
     * @group Carrinho
     *
     * Limpar carrinho
     *
     * Remove todos os itens do carrinho atual.
     *
     * @response 200 {
     *   "success": true,
     *   "message": "Carrinho limpo com sucesso",
     *   "data": []
     * }
     */
    public function clear()
    {
        $userId = 1;

        $this->cart->clearCart($userId);

        return $this->successResponse([], 'Carrinho limpo com sucesso');
    }
}
