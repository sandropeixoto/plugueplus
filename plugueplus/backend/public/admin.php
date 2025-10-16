<?php
require_once __DIR__ . '/../config/bootstrap.php';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Painel Administrativo - Plugue+</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 2rem; background: #f7f7f9; }
        h1 { color: #1b4965; }
        section { background: #ffffff; padding: 1.5rem; border-radius: 8px; margin-bottom: 1.5rem; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
        label { display: block; margin-top: .5rem; font-weight: bold; }
        input, textarea { width: 100%; padding: .5rem; margin-top: .25rem; border: 1px solid #cbd5e1; border-radius: 4px; }
        button { margin-top: 1rem; padding: .6rem 1.2rem; background: #1b4965; color: #fff; border: none; border-radius: 4px; cursor: pointer; }
        pre { background: #0f172a; color: #f8fafc; padding: 1rem; border-radius: 6px; overflow-x: auto; }
    </style>
</head>
<body>
    <h1>Painel Administrativo Plugue+</h1>
    <p>Utilize os formulários abaixo para cadastrar rapidamente dados iniciais utilizando a API pública. Informe um token JWT válido gerado pelo login.</p>

    <label for="token">Token JWT</label>
    <input type="text" id="token" placeholder="Cole o token de acesso">

    <section>
        <h2>Nova Categoria de Serviço</h2>
        <label>Nome</label>
        <input type="text" id="cat-nome">
        <label>Ícone (opcional)</label>
        <input type="text" id="cat-icone" placeholder="ex: mdi-tools">
        <button onclick="submitCategoria()">Cadastrar categoria</button>
    </section>

    <section>
        <h2>Novo Serviço</h2>
        <label>Nome</label>
        <input type="text" id="serv-nome">
        <label>Categoria ID</label>
        <input type="number" id="serv-categoria">
        <label>Descrição</label>
        <textarea id="serv-descricao"></textarea>
        <label>Telefone</label>
        <input type="text" id="serv-telefone">
        <label>Site</label>
        <input type="text" id="serv-site">
        <label>Endereço</label>
        <input type="text" id="serv-endereco">
        <button onclick="submitServico()">Cadastrar serviço</button>
    </section>

    <section>
        <h2>Novo Carregador</h2>
        <label>Nome</label>
        <input type="text" id="carr-nome">
        <label>Endereço</label>
        <input type="text" id="carr-endereco">
        <label>Potência (kW)</label>
        <input type="number" id="carr-potencia" step="0.1">
        <label>Tipo de Conector</label>
        <input type="text" id="carr-conector">
        <label>Status</label>
        <input type="text" id="carr-status" placeholder="ativo">
        <button onclick="submitCarregador()">Cadastrar carregador</button>
    </section>

    <section>
        <h2>Logs da API</h2>
        <pre id="log">Aguardando ações...</pre>
    </section>

    <script>
        const apiBase = '../';

        async function apiRequest(path, payload) {
            const token = document.getElementById('token').value.trim();
            const response = await fetch(apiBase + path, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    ...(token ? { 'Authorization': 'Bearer ' + token } : {})
                },
                body: JSON.stringify(payload)
            });
            const data = await response.json().catch(() => ({}));
            document.getElementById('log').textContent = JSON.stringify({ status: response.status, data }, null, 2);
        }

        function submitCategoria() {
            apiRequest('categorias', {
                nome: document.getElementById('cat-nome').value,
                icone: document.getElementById('cat-icone').value
            });
        }

        function submitServico() {
            apiRequest('servicos', {
                nome: document.getElementById('serv-nome').value,
                categoria_id: Number(document.getElementById('serv-categoria').value),
                descricao: document.getElementById('serv-descricao').value,
                telefone: document.getElementById('serv-telefone').value,
                site: document.getElementById('serv-site').value,
                endereco: document.getElementById('serv-endereco').value
            });
        }

        function submitCarregador() {
            apiRequest('carregadores', {
                nome: document.getElementById('carr-nome').value,
                endereco: document.getElementById('carr-endereco').value,
                potencia_kw: Number(document.getElementById('carr-potencia').value),
                tipo_conector: document.getElementById('carr-conector').value,
                status: document.getElementById('carr-status').value || 'ativo'
            });
        }
    </script>
</body>
</html>
