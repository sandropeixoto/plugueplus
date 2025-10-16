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
2. Instale dependências:
   ```bash
   cd plugueplus/backend
   composer install
   ```
3. Execute o script `sql/schema.sql` no seu banco MySQL.
4. Inicie o servidor embutido do PHP:
   ```bash
   php -S 127.0.0.1:9000 -t public
   ```

## Configuração Frontend
```bash
cd plugueplus/frontend
npm install
npm run dev
```

O frontend ficará disponível em `http://127.0.0.1:5173` e consumirá a API no endereço `http://127.0.0.1:9000`.

## Autenticação
As rotas de criação exigem token JWT obtido via `/auth/login` ou `/auth/register`. O painel administrativo básico pode ser acessado em `http://127.0.0.1:9000/admin.php`.
