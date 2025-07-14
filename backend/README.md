# Luvinco - Backend API

Este é o backend da Vitrine de Produtos desenvolvido em Laravel para integrar com APIs externas, centralizar dados e fornecer endpoints REST para o frontend Vue.

---

## Tecnologias

- Laravel 12
- PHP 8.3+
- SQLite ou Mysql
- Queue com driver `database`
- Laravel Scribe (documentação de API)
- Tailwind para mensagens/validações no frontend
- PestPHP para testes

---

## Endpoints da API

### 📦 Produtos

#### `GET /api/products`

Retorna os produtos sincronizados localmente do banco de dados.

**Resposta:**

```json
[
  {
    "product_id": "12345",
    "name": "Camisa Social Masculina",
    "description": "Camisa social em algodão, ideal para ocasiões formais.",
    "price": 79.99,
    "category": "Roupas Masculinas",
    "brand": "Zara",
    "stock": 10,
    "image_url": "https://example.com/image1.jpg"
  }
]
```

---

### Carrinho

#### `GET /api/cart`

Lista os itens do carrinho atual da sessão.

#### `POST /api/cart`

Adiciona item ao carrinho.

```json
{
  "product_id": "12345",
  "price": 99.90,
  "quantity": 2
}
```

#### `DELETE /api/cart/{product_id}`

Remove item do carrinho.

#### `POST /api/cart/clear`

Limpa todos os itens do carrinho.

---

### Pedido

#### `POST /api/orders`

Envia um pedido para a API externa e salva localmente.

**Payload:**

```json
{
  "items": [
    {
      "product_id": "12345",
      "quantity": 2
    }
  ]
}
```

**Resposta:**

```json
{
  "success": true,
  "message": "Pedido criado com sucesso",
  "data": {
    "order_id": "uuid",
    "status": "Processando",
    "estimated_delivery": "2025-07-12T22:57:30Z"
  }
}
```

---

## Jobs e Sincronização

### SyncProductsJob

- Sincroniza os produtos 3x por dia da API externa
- Implementa retry automático com backoff
- Persistência local em `products` com `updated_at_api`

### Agendamento

Use o scheduler com cron:

```bash
* * * * * php /path/to/artisan schedule:run >> /dev/null 2>&1
```

---

## ✅ Testes

Usando PestPHP:

```bash
php artisan test
```

Cobertura de:

- Criação de pedidos
- Validações
- Fallbacks de erro da API

---

## Documentação da API

Gerada via Laravel Scribe:

```bash
php artisan scribe:generate
```

Depois acesse: `http://localhost/docs`

---


## Configurações de ambiente

No `.env`:

```env
LUVINCO_API_URL=https://luvinco.proxy.beeceptor.com
LUVINCO_API_KEY=chave_aqui
QUEUE_CONNECTION=database
```
