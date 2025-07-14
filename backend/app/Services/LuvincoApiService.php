<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Config;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Carbon\Carbon;

class LuvincoApiService
{
    private string $baseUrl;
    private array $headers;

    public function __construct()
    {
        $this->baseUrl = Config::get('services.luvinco.url');
        $this->headers = [
            'Authorization' => Config::get('services.luvinco.key'),
        ];
    }

    public function getProducts()
    {
        Log::info('Requisição para buscar produtos da Luvinco API');

        return Http::withHeaders($this->headers)
            ->get("{$this->baseUrl}/products")
            ->throw()
            ->json();
    }

    public function postOrder(array $items): array
    {
        $payload = ['items' => $items];

        $response = Http::withHeaders($this->headers)
            ->post("{$this->baseUrl}/orders", $payload);

        if (!$response->successful()) {
            throw new \Exception("Erro ao criar pedido externo");
        }

        $data = $response->json();

        DB::beginTransaction();

        try {
            $order = Order::create([
                'external_id'        => $data['order_id'],
                'status'             => $data['status'],
                'message'            => $data['message'] ?? null,
                'estimated_delivery' => Carbon::parse($data['estimated_delivery'])->format('Y-m-d H:i:s'),
            ]);

            foreach ($items as $item) {
                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $item['product_id'],
                    'quantity'   => $item['quantity'],
                    'price'      => Product::where('product_id', $item['product_id'])->value('price') ?? 0,
                ]);
            }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('Erro ao persistir pedido no banco.', ['error' => $e->getMessage()]);
            throw $e;
        }

        return $data;
    }
}
