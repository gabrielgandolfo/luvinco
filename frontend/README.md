# LuvinCo – Frontend

Este projeto é a interface frontend para a loja da LuvinCo

---

## Tecnologias

- [Vue 3](https://vuejs.org/)
- [Pinia](https://pinia.vuejs.org/)
- [Axios](https://axios-http.com/)
- [Tailwind CSS](https://tailwindcss.com/)
- [Vite](https://vitejs.dev/)

---

## Como rodar o projeto

### 1. Clone o repositório:

```bash
git clone https://github.com/seu-usuario/luvinco-store-frontend.git
cd luvinco-store-frontend
```

### 2. Instale as dependências:

```bash
npm install
# ou
yarn
```

### 3. Crie o arquivo `.env` com a URL do backend:

```env
VITE_API_URL=http://localhost:8000/api
VITE_BACKEND_URL=http://localhost:8000
```

---

## Funcionalidades

- Filtro por nome, marca e categoria
- Carrinho com exibição dinâmica
- Envio de pedido com feedback
- Tratamento de erros de rede e falhas no servidor (ex: erro 500)