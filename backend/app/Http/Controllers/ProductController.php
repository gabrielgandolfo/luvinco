<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\LuvincoApiService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\BaseController;
use App\Models\Product;

class ProductController extends BaseController
{
    /**
     * @group Produtos
     *
     * Listar produtos disponíveis
     *
     * Retorna a lista de produtos armazenada localmente no banco de dados.
     * A sincronização com a API externa ocorre automaticamente via job.
     *
     * @response 200 scenario="Lista de produtos" {
     *  "success": true,
     *  "message": "Produtos listados com sucesso",
     *  "data": [
     *      {
     *          "id": 1,
     *          "product_id": "12345",
     *          "name": "Camisa Social Masculina",
     *          "description": "Camisa social em algodão, ideal para ocasiões formais.",
     *          "price": 79.99,
     *          "category": "Roupas Masculinas",
     *          "brand": "Zara",
     *          "stock": 10,
     *          "image_url": "https://example.com/image1.jpg",
     *          "created_at": "2025-07-13T14:05:10.000000Z",
     *          "updated_at": "2025-07-13T14:05:10.000000Z"
     *      }
     *  ]
     * }
     *
     * @response 500 scenario="Erro no servidor" {
     *  "success": false,
     *  "message": "Erro ao buscar produtos"
     * }
     */
    public function index()
    {
        try {
            $products = Product::orderBy('name')->get();
            return $this->successResponse($products, 'Produtos listados com sucesso');
        } catch (\Throwable $e) {
            Log::error('Erro ao buscar produtos no banco', ['error' => $e->getMessage()]);
            return $this->errorResponse('Erro ao buscar produtos');
        }
    }
}
