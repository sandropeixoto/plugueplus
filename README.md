# Plugue+

MVP colaborativo para donos e entusiastas de veículos elétricos com mapa de carregadores, guia de serviços e feed comunitário.

## Estrutura
```
plugueplus/
├── backend/
│   ├── api/
│   ├── config/
│   ├── controllers/
│   ├── helpers/
│   ├── middleware/
│   ├── models/
│   ├── public/
│   └── sql/
├── frontend/
│   ├── public/
│   └── src/
└── .github/workflows/
```

## Pré-requisitos
- PHP 8.3+
- MySQL 8+
- Node.js 20+

## Configuração Backend
1. Copie o arquivo `.env.example` para `.env` na pasta `plugueplus/backend` e ajuste as variáveis.
   - Caso esteja utilizando as credenciais fornecidas para produção, basta colá-las no novo arquivo.
2. Instale dependências:
   ```bash
   cd plugueplus/backend
   composer install
   ```
3. O backend criará automaticamente o banco (quando o usuário possuir permissão) e aplicará o schema presente em `sql/schema.sql` na primeira conexão.
4. Inicie o servidor embutido do PHP utilizando o roteador dedicado (necessário para que todas as requisições cheguem ao `index.php`):
   ```bash
   php -S 127.0.0.1:9000 backend/server.php
   ```
   > Caso esteja hospedando em Apache ou Nginx, utilize `public/` como raiz e mantenha a reescrita de URLs apontando para `index.php` (o arquivo `.htaccess` incluso já realiza essa configuração para Apache).

## Configuração Frontend
```bash
cd plugueplus/frontend
npm install
npm run dev
```

O frontend ficará disponível em `http://127.0.0.1:5173` e consumirá a API no endereço `http://127.0.0.1:9000`. Para ambientes distintos, defina a variável `VITE_API_URL` no `.env` do frontend.

## Autenticação
As rotas de criação exigem token JWT obtido via `/auth/login` ou `/auth/register`. O painel administrativo básico pode ser acessado em `http://127.0.0.1:9000/admin.php`.
