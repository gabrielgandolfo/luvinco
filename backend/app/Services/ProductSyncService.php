<?php
namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Carbon;

class ProductSyncService
{
    protected string $baseUrl;
    protected string $authKey;

    public function __construct()
    {
        $this->baseUrl = Config::get('services.luvinco.url');
        $this->authKey = Config::get('services.luvinco.key');
    }

    public function sync(): bool
    {
        Log::info('Iniciando sincronização com Luvinco API');
        try {
            $response = Http::withHeaders([
                'Authorization' => $this->authKey,
            ])->get("{$this->baseUrl}/products");

            if (!$response->successful()) {
                Log::warning('Falha ao buscar produtos da API Luvinco.', ['status' => $response->status()]);
                return false;
            }

            $products = $response->json();

            foreach ($products as $p) {
                Product::updateOrCreate(
                    ['product_id' => $p['product_id']],
                    [
                        'name' => $p['name'],
                        'description' => $p['description'] ?? null,
                        'price' => $p['price'],
                        'category' => $p['category'],
                        'brand' => $p['brand'],
                        'stock' => $p['stock'],
                        'image_url' => $p['image_url'],
                        'updated_at_api' => Carbon::now(),
                    ]
                );
            }

            Log::info('Produtos sincronizados com sucesso.');
            return true;
        } catch (\Throwable $e) {
            Log::error('Erro ao sincronizar produtos: ' . $e->getMessage());
            return false;
        }
    }
}
