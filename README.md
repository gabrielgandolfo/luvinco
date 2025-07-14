# LuvinCo – Desafio

Repositório contém uma aplicação fullstack.

---

## Tecnologias

- **Frontend**: Vue 3 + Vite + TypeScript
- **Backend**: Laravel 12 (PHP 8.2+)
- **Banco de Dados**: MySQL 8
- **Ambiente**: Docker + Docker Compose

---

## Excutando localmente

### 1. Clonar o repositório
```bash
git clone https://github.com/gabrielgandolfo/luvinco.git
cd luvinco
```

### 2. Copiar variáveis de ambiente
```bash
cp backend/.env.example backend/.env
```

### 3. Subir os containers com Docker
```bash
docker-compose up --build
```

> **Importante**: Certifique-se de que a porta **3306** (MySQL), **8000** (Laravel) e **5173** (Vite) estejam livres no seu host.

### 4. Rodar as migrations do Laravel
Em outro terminal:
```bash
docker exec -it luvinco_backend_1 php artisan migrate --force
docker exec -it luvinco_backend_1 php artisan products:sync
docker exec -it luvinco_backend_1 php artisan queue:work
```

---

## Endpoints Principais

- **Frontend**: [`http://localhost:5173`](http://localhost:5173)
- **Backend API**: [`http://localhost:8000/api`](http://localhost:8000/api)

---

---

## Entrega
 
Data: 13/07/2025

---