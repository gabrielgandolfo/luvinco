# Etapa 1 – build da aplicação
FROM node:20 AS builder

WORKDIR /app

COPY package*.json ./
RUN npm install

COPY . .
RUN npm run build

# Etapa 2 – servir os arquivos estáticos com Nginx
FROM nginx:alpine

COPY --from=builder /app/dist /usr/share/nginx/html

# Copia config customizado (opcional)
COPY nginx.conf /etc/nginx/conf.d/default.conf

EXPOSE 80

CMD ["nginx", "-g", "daemon off;"]
