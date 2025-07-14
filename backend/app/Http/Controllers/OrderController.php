<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\LuvincoApiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

/**
 * @group Pedidos
 *
 * APIs relacionadas à criação de pedidos.
 */
class OrderController extends BaseController
{
    /**
     * Criar novo pedido
     *
     * Envia os itens selecionados para a API externa e salva o pedido localmente.
     *
     * @bodyParam items array required Lista de produtos do pedido. Exemplo: [{"product_id": "12345", "quantity": 2}]
     * @bodyParam items[].product_id string required ID do produto. Exemplo: 12345
     * @bodyParam items[].quantity integer required Quantidade do produto. Exemplo: 2
     *
     * @response 201 {
     *   "success": true,
     *   "message": "Pedido criado com sucesso",
     *   "data": {
     *     "order_id": "18338427-6f92-496f-9d4c-583049ac2f73",
     *     "status": "Processando",
     *     "message": "Pedido criado com sucesso.",
     *     "estimated_delivery": "2025-07-12T22:57:30Z"
     *   }
     * }
     *
     * @response 500 {
     *   "success": false,
     *   "message": "Falha ao conectar ao servidor, tente novamente."
     * }
     */
    public function store(Request $request, LuvincoApiService $api)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        try {
            $response = $api->postOrder($request->items);
            return $this->successResponse($response, 'Pedido criado com sucesso', 201);
        } catch (\Throwable $e) {
            Log::error('Erro ao enviar pedido', ['error' => $e->getMessage()]);
            return $this->errorResponse('Falha ao conectar ao servidor, tente novamente.');
        }
    }
}
