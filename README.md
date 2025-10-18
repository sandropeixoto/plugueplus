# Plugue+

MVP colaborativo para donos e entusiastas de veículos elétricos com mapa de carregadores, guia de serviços e feed comunitário.

## Estrutura
```
plugueplus/
├── backend/
│   ├── app/
│   ├── config/
│   ├── public/
│   └── sql/
└── .github/workflows/
```

## Pré-requisitos
- PHP 8.3+
- MySQL 8+

## Configuração Backend
1. Copie o arquivo `.env.example` para `.env` na pasta `plugueplus/backend` e ajuste as variáveis.
   - Caso esteja utilizando as credenciais fornecidas para produção, basta colá-las no novo arquivo.
2. Instale dependências (opcional, apenas se utilizar recursos adicionais via Composer):
   ```bash
   cd plugueplus/backend
   composer install
   ```
3. O backend criará automaticamente o banco (quando o usuário possuir permissão) e aplicará o schema presente em `sql/schema.sql` na primeira conexão.
4. Inicie o servidor embutido do PHP servindo o diretório `public/`:
   ```bash
   cd plugueplus/backend
   php -S 127.0.0.1:9000 -t public
   ```
5. Acesse `http://127.0.0.1:9000/admin.php` para gerenciar categorias, serviços e carregadores diretamente pelo painel PHP responsivo construído com Bootstrap. Todas as operações utilizam PDO e formulários tradicionais, sem dependência de APIs externas.
