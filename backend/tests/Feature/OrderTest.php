<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Product;
use Illuminate\Support\Facades\Http;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(TestCase::class, RefreshDatabase::class);

// Setup inicial (produto em banco)
beforeEach(function () {
    Product::factory()->create([
        'product_id' => '373839',
        'name' => 'Tênis Esportivo Masculino',
        'price' => 149.99,
        'stock' => 10,
    ]);
});

test('pedido é criado com sucesso', function () {
    Http::fake([
        '*/orders' => Http::response([
            'order_id' => 'abc-123',
            'status' => 'Processando',
            'message' => 'Pedido criado com sucesso.',
            'estimated_delivery' => now()->addDays(2)->toISOString(),
        ], 201),
    ]);

    $response = $this->postJson('/api/orders', [
        'items' => [
            ['product_id' => '373839', 'quantity' => 2],
        ],
    ]);

    $response->assertStatus(201)
        ->assertJson([
            'success' => true,
            'message' => 'Pedido criado com sucesso',
        ]);

    expect(\App\Models\Order::count())->toBe(1);
    expect(\App\Models\OrderItem::count())->toBe(1);
});

test('falha se campos obrigatórios estiverem ausentes', function () {
    $response = $this->postJson('/api/orders', []);

    $response->assertStatus(422)
        ->assertJsonValidationErrors(['items']);
});

test('falha se API externa retornar erro', function () {
    Http::fake([
        '*/orders' => Http::response(null, 500),
    ]);

    $response = $this->postJson('/api/orders', [
        'items' => [
            ['product_id' => '373839', 'quantity' => 1],
        ],
    ]);

    $response->assertStatus(500)
        ->assertJson([
            'success' => false,
            'message' => 'Falha ao conectar ao servidor, tente novamente.',
        ]);
});
