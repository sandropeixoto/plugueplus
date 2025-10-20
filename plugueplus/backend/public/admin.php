<?php
require_once __DIR__ . '/../config/bootstrap.php';
require_once __DIR__ . '/../app/Repositories.php';

session_start();

$section = $_GET['section'] ?? 'categorias';
$validSections = ['categorias', 'servicos', 'carregadores'];
if (!in_array($section, $validSections, true)) {
    $section = 'categorias';
}

if (!isset($_SESSION['flash'])) {
    $_SESSION['flash'] = [];
}

function add_flash(string $type, string $message): void
{
    $_SESSION['flash'][] = ['type' => $type, 'message' => $message];
}

function consume_flashes(): array
{
    $messages = $_SESSION['flash'] ?? [];
    $_SESSION['flash'] = [];
    return $messages;
}

function redirect_with_section(string $section): void
{
    header('Location: admin.php?section=' . urlencode($section));
    exit;
}

try {
    Database::getConnection();
} catch (Throwable $exception) {
    add_flash('danger', $exception->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    try {
        switch ($action) {
            case 'create_categoria':
                $nome = trim($_POST['nome'] ?? '');
                $icone = trim($_POST['icone'] ?? '');
                if ($nome === '') {
                    throw new InvalidArgumentException('Informe um nome para a categoria.');
                }
                CategoriaRepository::create($nome, $icone);
                add_flash('success', 'Categoria criada com sucesso.');
                break;

            case 'update_categoria':
                $id = (int) ($_POST['id'] ?? 0);
                $nome = trim($_POST['nome'] ?? '');
                $icone = trim($_POST['icone'] ?? '');
                if ($id <= 0 || $nome === '') {
                    throw new InvalidArgumentException('Dados insuficientes para atualizar a categoria.');
                }
                CategoriaRepository::update($id, $nome, $icone);
                add_flash('success', 'Categoria atualizada com sucesso.');
                break;

            case 'delete_categoria':
                $id = (int) ($_POST['id'] ?? 0);
                if ($id <= 0) {
                    throw new InvalidArgumentException('Categoria inválida para exclusão.');
                }
                CategoriaRepository::delete($id);
                add_flash('success', 'Categoria removida.');
                break;

            case 'create_servico':
                $data = array_map('trim', $_POST);
                if (($data['nome'] ?? '') === '') {
                    throw new InvalidArgumentException('Informe um nome para o serviço.');
                }
                if ((int) ($data['categoria_id'] ?? 0) <= 0) {
                    throw new InvalidArgumentException('Selecione uma categoria.');
                }
                ServicoRepository::create($data);
                add_flash('success', 'Serviço cadastrado com sucesso.');
                break;

            case 'update_servico':
                $id = (int) ($_POST['id'] ?? 0);
                $data = array_map('trim', $_POST);
                if ($id <= 0 || ($data['nome'] ?? '') === '') {
                    throw new InvalidArgumentException('Dados insuficientes para atualizar o serviço.');
                }
                if ((int) ($data['categoria_id'] ?? 0) <= 0) {
                    throw new InvalidArgumentException('Selecione uma categoria.');
                }
                ServicoRepository::update($id, $data);
                add_flash('success', 'Serviço atualizado com sucesso.');
                break;

            case 'delete_servico':
                $id = (int) ($_POST['id'] ?? 0);
                if ($id <= 0) {
                    throw new InvalidArgumentException('Serviço inválido para exclusão.');
                }
                ServicoRepository::delete($id);
                add_flash('success', 'Serviço removido.');
                break;

            case 'create_carregador':
                $data = array_map('trim', $_POST);
                if (($data['nome'] ?? '') === '') {
                    throw new InvalidArgumentException('Informe um nome para o carregador.');
                }
                CarregadorRepository::create($data);
                add_flash('success', 'Carregador cadastrado com sucesso.');
                break;

            case 'update_carregador':
                $id = (int) ($_POST['id'] ?? 0);
                $data = array_map('trim', $_POST);
                if ($id <= 0 || ($data['nome'] ?? '') === '') {
                    throw new InvalidArgumentException('Dados insuficientes para atualizar o carregador.');
                }
                CarregadorRepository::update($id, $data);
                add_flash('success', 'Carregador atualizado com sucesso.');
                break;

            case 'delete_carregador':
                $id = (int) ($_POST['id'] ?? 0);
                if ($id <= 0) {
                    throw new InvalidArgumentException('Carregador inválido para exclusão.');
                }
                CarregadorRepository::delete($id);
                add_flash('success', 'Carregador removido.');
                break;
        }
    } catch (Throwable $exception) {
        add_flash('danger', $exception->getMessage());
    }

    redirect_with_section($section);
}

$categorias = CategoriaRepository::all();
$servicos = ServicoRepository::all();
$carregadores = CarregadorRepository::all();

$editingId = isset($_GET['edit']) ? (int) $_GET['edit'] : null;
$editingCategoria = $section === 'categorias' && $editingId ? CategoriaRepository::find($editingId) : null;
$editingServico = $section === 'servicos' && $editingId ? ServicoRepository::find($editingId) : null;
$editingCarregador = $section === 'carregadores' && $editingId ? CarregadorRepository::find($editingId) : null;

$messages = consume_flashes();

function section_label(string $section): string
{
    return match ($section) {
        'servicos' => 'Serviços',
        'carregadores' => 'Carregadores',
        default => 'Categorias',
    };
}

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<<<<<<< HEAD
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Painel administrativo PluguePlus</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        :root {
            color-scheme: only light;
        }
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(180deg, #f4f6ff 0%, #ffffff 40%);
            min-height: 100vh;
            color: #0f172a;
        }
        .navbar-brand {
            font-weight: 700;
            letter-spacing: 0.02em;
        }
        .content-wrapper {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2rem 1rem 4rem;
        }
        .card {
            border: none;
            box-shadow: 0 20px 45px -20px rgba(15, 23, 42, 0.25);
            border-radius: 1rem;
        }
        .table thead {
            background-color: #0d6efd;
            color: #fff;
        }
        .table thead th {
            border: none;
        }
        .table tbody tr {
            vertical-align: middle;
        }
        .form-control,
        .form-select {
            color: #0f172a;
            background-color: #ffffff;
            border-color: rgba(15, 23, 42, 0.15);
        }
        .form-control::placeholder {
            color: #6b7280;
            opacity: 1;
        }
        .form-control:focus,
        .form-select:focus {
            color: #0f172a;
            background-color: #ffffff;
            border-color: rgba(37, 99, 235, 0.45);
            box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.15);
        }
        .nav-pills .nav-link {
            font-weight: 600;
            border-radius: 999px;
            padding: 0.5rem 1.25rem;
        }
        .nav-pills .nav-link.active {
            background: linear-gradient(135deg, #0d6efd 0%, #6610f2 100%);
            box-shadow: 0 10px 30px -15px rgba(13, 110, 253, 0.75);
        }
        .btn-soft-primary {
            color: #0d6efd;
            background-color: rgba(13, 110, 253, 0.1);
            border-color: transparent;
        }
        .btn-soft-primary:hover {
            color: #0b5ed7;
            background-color: rgba(13, 110, 253, 0.2);
            border-color: transparent;
        }
        @media (max-width: 992px) {
            .table-responsive {
                box-shadow: inset 0 -1px 0 rgba(15, 23, 42, 0.05);
=======
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Administrativo - Plugue+</title>
    <style>
        :root {
            color-scheme: light dark;
            --brand: #1b4965;
            --brand-soft: #dbe8f1;
            --brand-dark: #0f2f44;
            --bg: #f4f6fb;
            --bg-card: #ffffff;
            --bg-card-dark: #142033;
            --border: #d0d7e2;
            --text: #0f172a;
            --text-muted: #475569;
            --success: #0f9d58;
            --danger: #e53935;
            --warning: #f4b400;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .topbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1.25rem 2rem;
            background: linear-gradient(135deg, var(--brand), var(--brand-dark));
            color: #fff;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .brand {
            font-size: 1.25rem;
            font-weight: 600;
            letter-spacing: 0.02em;
        }

        .token-field {
            display: flex;
            flex-direction: column;
            gap: 0.4rem;
            min-width: 220px;
        }

        .token-field label {
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.08em;
        }

        .token-field input {
            padding: 0.55rem 0.75rem;
            border-radius: 6px;
            border: 1px solid rgba(255, 255, 255, 0.5);
            background: rgba(255, 255, 255, 0.92);
            color: var(--brand-dark);
        }

        .token-field input::placeholder {
            color: rgba(15, 47, 68, 0.65);
        }

        .shell {
            flex: 1;
            display: grid;
            grid-template-columns: 280px 1fr;
            gap: 0;
            min-height: 0;
        }

        .sidebar {
            background: #fff;
            border-right: 1px solid var(--border);
            padding: 2rem 1.75rem;
            display: flex;
            flex-direction: column;
            gap: 2.5rem;
        }

        .menu {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .menu button {
            appearance: none;
            border: none;
            border-radius: 10px;
            padding: 0.85rem 1rem;
            font-size: 0.95rem;
            text-align: left;
            background: transparent;
            cursor: pointer;
            color: var(--text-muted);
            transition: all 0.2s ease;
        }

        .menu button:hover,
        .menu button:focus-visible {
            background: var(--brand-soft);
            color: var(--brand-dark);
            outline: none;
        }

        .menu button.active {
            background: var(--brand);
            color: #fff;
            box-shadow: 0 6px 18px rgba(27, 73, 101, 0.25);
        }

        .menu button span {
            display: block;
            font-size: 0.78rem;
            opacity: 0.7;
            margin-top: 0.2rem;
        }

        .sidebar .hint {
            font-size: 0.85rem;
            line-height: 1.5;
            color: var(--text-muted);
        }

        .content {
            padding: 2rem 2.5rem;
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            min-width: 0;
        }

        .panel-head {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .panel-head h1 {
            margin: 0;
            font-size: 1.6rem;
            color: var(--brand-dark);
        }

        .panel-head p {
            margin: 0.35rem 0 0;
            color: var(--text-muted);
            max-width: 600px;
        }

        .btn {
            appearance: none;
            border: none;
            border-radius: 999px;
            padding: 0.75rem 1.6rem;
            font-size: 0.95rem;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.15s ease, box-shadow 0.15s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-primary {
            background: var(--brand);
            color: #fff;
            box-shadow: 0 8px 20px rgba(27, 73, 101, 0.28);
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 10px 24px rgba(27, 73, 101, 0.32);
        }

        .btn-ghost {
            background: rgba(15, 23, 42, 0.04);
            color: var(--text-muted);
        }

        .btn-ghost:hover {
            background: rgba(15, 23, 42, 0.08);
            color: var(--brand-dark);
        }

        .btn-danger {
            background: rgba(229, 57, 53, 0.12);
            color: var(--danger);
        }

        .grid {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 380px;
            gap: 1.5rem;
            align-items: stretch;
        }

        .card {
            background: var(--bg-card);
            border-radius: 18px;
            padding: 1.5rem;
            box-shadow: 0 12px 30px rgba(15, 23, 42, 0.05);
            border: 1px solid rgba(15, 23, 42, 0.05);
            display: flex;
            flex-direction: column;
            min-height: 0;
        }

        .card h2 {
            margin: 0 0 1rem;
            font-size: 1.1rem;
            color: var(--brand-dark);
        }

        .list-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .table-wrapper {
            overflow: auto;
            border-radius: 12px;
            border: 1px solid rgba(15, 23, 42, 0.08);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 560px;
        }

        thead {
            background: rgba(15, 23, 42, 0.04);
        }

        th, td {
            padding: 0.85rem 1rem;
            text-align: left;
            font-size: 0.92rem;
            border-bottom: 1px solid rgba(148, 163, 184, 0.25);
            vertical-align: middle;
        }

        th {
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.08em;
            color: var(--text-muted);
        }

        tbody tr:hover {
            background: rgba(27, 73, 101, 0.05);
        }

        td.actions {
            width: 140px;
        }

        td.actions .btn {
            font-size: 0.85rem;
            padding: 0.45rem 0.9rem;
            border-radius: 14px;
        }

        .empty-state {
            margin: 1.25rem 0 0;
            color: var(--text-muted);
            text-align: center;
        }

        .loading {
            color: var(--text-muted);
            font-size: 0.95rem;
            display: flex;
            gap: 0.6rem;
            align-items: center;
        }

        .loading::before {
            content: '';
            width: 1rem;
            height: 1rem;
            border-radius: 50%;
            border: 2px solid var(--brand);
            border-top-color: transparent;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        .field {
            display: flex;
            flex-direction: column;
            gap: 0.45rem;
        }

        .field label {
            font-weight: 600;
            font-size: 0.9rem;
        }

        .field input,
        .field textarea,
        .field select {
            padding: 0.65rem 0.75rem;
            border-radius: 10px;
            border: 1px solid rgba(15, 23, 42, 0.18);
            background: rgba(255, 255, 255, 0.9);
            font-size: 0.95rem;
            color: var(--brand-dark);
            transition: border-color 0.15s ease, box-shadow 0.15s ease;
        }

        .field input::placeholder,
        .field textarea::placeholder {
            color: rgba(15, 47, 68, 0.6);
        }

        .field input:focus,
        .field textarea:focus,
        .field select:focus {
            outline: none;
            border-color: var(--brand);
            box-shadow: 0 0 0 3px rgba(27, 73, 101, 0.18);
        }

        .field textarea {
            min-height: 120px;
            resize: vertical;
        }

        .field small {
            color: var(--text-muted);
            font-size: 0.75rem;
        }

        .form-actions {
            display: flex;
            gap: 0.75rem;
            justify-content: flex-end;
            margin-top: 0.5rem;
        }

        .feedback {
            border-radius: 12px;
            padding: 0.85rem 1rem;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }

        .feedback.success {
            background: rgba(15, 157, 88, 0.12);
            color: var(--success);
        }

        .feedback.error {
            background: rgba(229, 57, 53, 0.12);
            color: var(--danger);
        }

        .feedback.info {
            background: rgba(27, 73, 101, 0.1);
            color: var(--brand-dark);
        }

        @media (max-width: 1200px) {
            .grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 960px) {
            .shell {
                grid-template-columns: 1fr;
            }

            .sidebar {
                flex-direction: row;
                padding: 1.5rem 1.25rem;
                gap: 1.5rem;
                overflow-x: auto;
            }

            .menu {
                flex-direction: row;
            }

            .menu button {
                flex: 1;
                text-align: center;
            }

            .sidebar .hint {
                display: none;
            }

            .content {
                padding: 1.5rem;
            }
        }

        @media (max-width: 640px) {
            .topbar {
                padding: 1.1rem 1.4rem;
            }

            .panel-head h1 {
                font-size: 1.35rem;
            }

            table {
                min-width: 480px;
            }

            .panel-head {
                flex-direction: column;
                align-items: stretch;
            }

            .btn {
                justify-content: center;
>>>>>>> main
            }
        }
    </style>
</head>
<body>
<<<<<<< HEAD
<nav class="navbar navbar-expand-lg bg-white shadow-sm">
    <div class="container-fluid px-4">
        <span class="navbar-brand text-primary">PluguePlus</span>
        <span class="text-muted">Painel de gestão</span>
    </div>
</nav>

<div class="content-wrapper">
    <div class="d-flex flex-column flex-lg-row align-items-lg-center justify-content-lg-between gap-3 mb-4">
        <div>
            <h1 class="h3 mb-0 text-primary fw-bold">Gerencie o ecossistema PluguePlus</h1>
            <p class="text-muted mb-0">Cadastre e organize categorias, serviços e carregadores em um único painel responsivo.</p>
        </div>
        <ul class="nav nav-pills bg-white p-2 rounded-pill shadow-sm flex-nowrap overflow-auto">
            <li class="nav-item">
                <a class="nav-link <?= $section === 'categorias' ? 'active' : '' ?>" href="?section=categorias">Categorias</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $section === 'servicos' ? 'active' : '' ?>" href="?section=servicos">Serviços</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $section === 'carregadores' ? 'active' : '' ?>" href="?section=carregadores">Carregadores</a>
            </li>
        </ul>
    </div>

    <?php foreach ($messages as $message): ?>
        <div class="alert alert-<?= htmlspecialchars($message['type']) ?> alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($message['message']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button>
        </div>
    <?php endforeach; ?>

    <?php if ($section === 'categorias'): ?>
        <?php include __DIR__ . '/partials/categorias.php'; ?>
    <?php elseif ($section === 'servicos'): ?>
        <?php include __DIR__ . '/partials/servicos.php'; ?>
    <?php else: ?>
        <?php include __DIR__ . '/partials/carregadores.php'; ?>
    <?php endif; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
=======
    <header class="topbar">
        <div class="brand">Plugue+ · Painel administrativo</div>
        <div class="token-field">
            <label for="token">Token JWT para operações protegidas</label>
            <input type="text" id="token" placeholder="Cole aqui o token gerado no login">
        </div>
    </header>

    <div class="shell">
        <aside class="sidebar">
            <nav class="menu" aria-label="Sessões do painel">
                <button type="button" data-resource="categorias" class="active">Categorias<span>Segmentos de serviços</span></button>
                <button type="button" data-resource="servicos">Serviços<span>Empresas e contatos</span></button>
                <button type="button" data-resource="carregadores">Carregadores<span>Pontos de energia</span></button>
            </nav>
            <p class="hint">Crie, edite ou remova registros utilizando a API pública. As alterações são refletidas imediatamente no aplicativo.</p>
        </aside>

        <main class="content">
            <section class="panel-head">
                <div>
                    <h1 id="resource-title">Categorias de serviço</h1>
                    <p id="resource-description">Agrupe os serviços por segmento e defina os ícones utilizados na experiência mobile.</p>
                </div>
                <button type="button" class="btn btn-primary" id="new-record">Nova categoria</button>
            </section>

            <div id="feedback" class="feedback info" hidden></div>

            <section class="grid">
                <section class="card" id="list-card">
                    <div class="list-header">
                        <h2>Registros</h2>
                        <button type="button" class="btn btn-ghost" id="refresh-list">Atualizar</button>
                    </div>
                    <div class="loading" id="loading-indicator" hidden>Carregando dados...</div>
                    <div class="table-wrapper" id="table-wrapper" hidden>
                        <table id="data-table">
                            <thead></thead>
                            <tbody></tbody>
                        </table>
                    </div>
                    <p class="empty-state" id="empty-state" hidden>Nenhum registro cadastrado até o momento.</p>
                </section>

                <section class="card" id="form-card" hidden>
                    <h2 id="form-title">Cadastrar categoria</h2>
                    <form id="resource-form">
                        <!-- Campos gerados dinamicamente -->
                        <div class="form-actions">
                            <button type="button" class="btn btn-ghost" id="cancel-form">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Salvar alterações</button>
                        </div>
                    </form>
                </section>
            </section>
        </main>
    </div>

    <script>
        const apiBase = '../';
        const state = {
            token: '',
            currentResource: 'categorias',
            mode: 'create',
            editingId: null,
            data: {},
            reference: {},
            renderedFields: [],
            emptyMessage: null,
        };

        const resources = {
            categorias: {
                title: 'Categorias de serviço',
                description: 'Agrupe os serviços por segmento e defina os ícones utilizados na experiência mobile.',
                endpoint: 'categorias',
                singular: 'Categoria',
                newButtonLabel: 'Nova categoria',
                formTitle: {
                    create: 'Cadastrar categoria',
                    edit: 'Editar categoria',
                },
                columns: [
                    { key: 'id', label: '#', align: 'center', width: '64px' },
                    { key: 'nome', label: 'Nome' },
                    { key: 'icone', label: 'Ícone' },
                    { key: 'criado_em', label: 'Criado em', formatter: formatDate },
                ],
                formFields: [
                    { name: 'nome', label: 'Nome', type: 'text', required: true, placeholder: 'Ex.: Reparo elétrico' },
                    { name: 'icone', label: 'Ícone (opcional)', type: 'text', placeholder: 'Ex.: mdi-tools' },
                ],
            },
            servicos: {
                title: 'Serviços parceiros',
                description: 'Gerencie os serviços cadastrados e mantenha os contatos sempre atualizados.',
                endpoint: 'servicos',
                singular: 'Serviço',
                newButtonLabel: 'Novo serviço',
                formTitle: {
                    create: 'Cadastrar serviço',
                    edit: 'Editar serviço',
                },
                requires: ['categorias'],
                columns: [
                    { key: 'id', label: '#', align: 'center', width: '64px' },
                    { key: 'nome', label: 'Nome' },
                    { key: 'categoria', label: 'Categoria' },
                    { key: 'telefone', label: 'Telefone' },
                    { key: 'site', label: 'Site' },
                    { key: 'criado_em', label: 'Criado em', formatter: formatDate },
                ],
                formFields: [
                    { name: 'nome', label: 'Nome', type: 'text', required: true },
                    { name: 'categoria_id', label: 'Categoria', type: 'select', required: true, optionsSource: 'categorias', placeholder: 'Selecione uma categoria', parse: (value) => value ? Number(value) : null },
                    { name: 'descricao', label: 'Descrição', type: 'textarea' },
                    { name: 'telefone', label: 'Telefone', type: 'text' },
                    { name: 'site', label: 'Site', type: 'url', placeholder: 'https://...' },
                    { name: 'endereco', label: 'Endereço', type: 'text' },
                    { name: 'latitude', label: 'Latitude', type: 'number', step: '0.000001', parse: parseNumber },
                    { name: 'longitude', label: 'Longitude', type: 'number', step: '0.000001', parse: parseNumber },
                ],
            },
            carregadores: {
                title: 'Pontos de carregamento',
                description: 'Cadastre e acompanhe os carregadores disponíveis para os clientes.',
                endpoint: 'carregadores',
                singular: 'Carregador',
                newButtonLabel: 'Novo carregador',
                formTitle: {
                    create: 'Cadastrar carregador',
                    edit: 'Editar carregador',
                },
                columns: [
                    { key: 'id', label: '#', align: 'center', width: '64px' },
                    { key: 'nome', label: 'Nome' },
                    { key: 'endereco', label: 'Endereço' },
                    { key: 'potencia_kw', label: 'Potência', formatter: (value) => value ? `${value} kW` : '—' },
                    { key: 'tipo_conector', label: 'Conector' },
                    { key: 'status', label: 'Status', formatter: (value) => value || '—' },
                    { key: 'criado_em', label: 'Criado em', formatter: formatDate },
                ],
                formFields: [
                    { name: 'nome', label: 'Nome', type: 'text', required: true },
                    { name: 'endereco', label: 'Endereço', type: 'text' },
                    { name: 'potencia_kw', label: 'Potência (kW)', type: 'number', step: '0.1', parse: parseNumber },
                    { name: 'tipo_conector', label: 'Tipo de conector', type: 'text' },
                    { name: 'latitude', label: 'Latitude', type: 'number', step: '0.000001', parse: parseNumber },
                    { name: 'longitude', label: 'Longitude', type: 'number', step: '0.000001', parse: parseNumber },
                    { name: 'status', label: 'Status', type: 'text', defaultValue: 'ativo' },
                ],
            },
        };

        const elements = {
            title: document.getElementById('resource-title'),
            description: document.getElementById('resource-description'),
            newButton: document.getElementById('new-record'),
            refreshButton: document.getElementById('refresh-list'),
            feedback: document.getElementById('feedback'),
            tableHead: document.querySelector('#data-table thead'),
            tableBody: document.querySelector('#data-table tbody'),
            tableWrapper: document.getElementById('table-wrapper'),
            emptyState: document.getElementById('empty-state'),
            loading: document.getElementById('loading-indicator'),
            formCard: document.getElementById('form-card'),
            formTitle: document.getElementById('form-title'),
            form: document.getElementById('resource-form'),
            cancelForm: document.getElementById('cancel-form'),
            sidebarButtons: document.querySelectorAll('[data-resource]'),
            tokenInput: document.getElementById('token'),
        };

        elements.tokenInput.addEventListener('input', (event) => {
            state.token = event.target.value.trim();
        });

        elements.sidebarButtons.forEach((button) => {
            button.addEventListener('click', () => {
                const resource = button.dataset.resource;
                setResource(resource).catch((error) => showFeedback(error.message || 'Não foi possível trocar de sessão.', 'error'));
            });
        });

        elements.newButton.addEventListener('click', () => {
            openForm('create').catch((error) => showFeedback(error.message || 'Não foi possível abrir o formulário.', 'error'));
        });

        elements.cancelForm.addEventListener('click', () => {
            closeForm();
        });

        elements.refreshButton.addEventListener('click', () => {
            loadResourceData(state.currentResource);
        });

        elements.form.addEventListener('submit', async (event) => {
            event.preventDefault();
            const config = resources[state.currentResource];
            const payload = buildPayload();

            try {
                if (state.mode === 'edit' && state.editingId) {
                    await apiFetch(`${config.endpoint}/${state.editingId}`, {
                        method: 'PUT',
                        body: JSON.stringify(payload),
                    });
                    showFeedback(`${config.singular} atualizado com sucesso.`, 'success');
                } else {
                    await apiFetch(config.endpoint, {
                        method: 'POST',
                        body: JSON.stringify(payload),
                    });
                    showFeedback(`${config.singular} cadastrado com sucesso.`, 'success');
                }

                closeForm();
                await loadResourceData(state.currentResource);
                if (state.currentResource === 'categorias') {
                    state.reference.categorias = state.data.categorias;
                }
            } catch (error) {
                showFeedback(error.message || 'Não foi possível salvar os dados.', 'error');
            }
        });

        function buildPayload() {
            const payload = {};
            state.renderedFields.forEach((field) => {
                const control = elements.form.querySelector(`[name="${field.name}"]`);
                if (!control) {
                    return;
                }

                let value = control.value;
                if (field.type !== 'select' && field.type !== 'textarea') {
                    value = value.trim();
                } else if (field.type === 'textarea') {
                    value = value.trim();
                }

                if (field.parse) {
                    value = field.parse(value);
                } else if (field.type === 'number') {
                    value = value === '' ? null : Number(value);
                } else if (value === '') {
                    value = null;
                }

                payload[field.name] = value;
            });
            return payload;
        }

        function parseNumber(value) {
            if (value === '' || value === null || typeof value === 'undefined') {
                return null;
            }
            const number = Number(value);
            return Number.isNaN(number) ? null : number;
        }

        function formatDate(value) {
            if (!value) {
                return '—';
            }
            try {
                const date = new Date(value);
                return date.toLocaleString('pt-BR', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit',
                });
            } catch (_) {
                return value;
            }
        }

        async function setResource(resource) {
            state.currentResource = resource;
            state.mode = 'create';
            state.editingId = null;
            closeForm(true);

            elements.sidebarButtons.forEach((button) => {
                button.classList.toggle('active', button.dataset.resource === resource);
            });

            const config = resources[resource];
            elements.title.textContent = config.title;
            elements.description.textContent = config.description;
            elements.newButton.textContent = config.newButtonLabel;
            elements.formTitle.textContent = config.formTitle.create;

            await loadResourceData(resource);
        }

        async function loadResourceData(resource) {
            setLoading(true);
            const config = resources[resource];
            try {
                const data = await apiFetch(config.endpoint) || [];
                state.data[resource] = Array.isArray(data) ? data : [];
                if (resource === 'categorias') {
                    state.reference.categorias = state.data[resource];
                }
            } catch (error) {
                state.data[resource] = [];
                state.emptyMessage = error.message || 'Não foi possível carregar os dados.';
                showFeedback(error.message || 'Não foi possível carregar os dados.', 'error');
            }

            renderTable(resource);
            setLoading(false);
        }

        function renderTable(resource) {
            const config = resources[resource];
            const rows = state.data[resource] || [];

            elements.tableHead.innerHTML = '';
            const headRow = document.createElement('tr');
            config.columns.forEach((column) => {
                const th = document.createElement('th');
                th.textContent = column.label;
                if (column.align) {
                    th.style.textAlign = column.align;
                }
                if (column.width) {
                    th.style.width = column.width;
                }
                headRow.appendChild(th);
            });
            const actionsTh = document.createElement('th');
            actionsTh.textContent = 'Ações';
            actionsTh.style.textAlign = 'center';
            headRow.appendChild(actionsTh);
            elements.tableHead.appendChild(headRow);

            elements.tableBody.innerHTML = '';

            rows.forEach((item) => {
                const tr = document.createElement('tr');
                config.columns.forEach((column) => {
                    const td = document.createElement('td');
                    if (column.align) {
                        td.style.textAlign = column.align;
                    }
                    let value = item[column.key];
                    if (column.formatter) {
                        value = column.formatter(value, item);
                    }
                    if (value === null || value === undefined || value === '') {
                        value = '—';
                    }
                    td.textContent = value;
                    tr.appendChild(td);
                });

                const actions = document.createElement('td');
                actions.className = 'actions';
                actions.style.textAlign = 'center';

                const editButton = document.createElement('button');
                editButton.type = 'button';
                editButton.className = 'btn btn-ghost';
                editButton.textContent = 'Editar';
                editButton.addEventListener('click', () => {
                    openForm('edit', item).catch((error) => showFeedback(error.message || 'Não foi possível abrir o registro.', 'error'));
                });

                const deleteButton = document.createElement('button');
                deleteButton.type = 'button';
                deleteButton.className = 'btn btn-danger';
                deleteButton.textContent = 'Excluir';
                deleteButton.addEventListener('click', () => {
                    deleteRecord(item.id);
                });

                actions.append(editButton, deleteButton);
                tr.appendChild(actions);
                elements.tableBody.appendChild(tr);
            });

            const hasRows = rows.length > 0;
            elements.tableWrapper.hidden = !hasRows;
            elements.emptyState.hidden = hasRows;
            if (!hasRows) {
                elements.emptyState.textContent = state.emptyMessage || 'Nenhum registro cadastrado até o momento.';
                state.emptyMessage = null;
            }
        }

        async function deleteRecord(id) {
            const config = resources[state.currentResource];
            const confirmed = window.confirm(`Deseja realmente excluir este ${config.singular.toLowerCase()}?`);
            if (!confirmed) {
                return;
            }

            try {
                await apiFetch(`${config.endpoint}/${id}`, { method: 'DELETE' });
                showFeedback(`${config.singular} removido com sucesso.`, 'success');
                await loadResourceData(state.currentResource);
                if (state.currentResource === 'categorias') {
                    state.reference.categorias = state.data.categorias;
                }
            } catch (error) {
                showFeedback(error.message || 'Não foi possível excluir o registro.', 'error');
            }
        }

        async function openForm(mode, item = null) {
            const config = resources[state.currentResource];
            try {
                await ensureReferences(state.currentResource);
            } catch (error) {
                throw error;
            }

            state.mode = mode;
            state.editingId = item?.id ?? null;

            elements.formTitle.textContent = mode === 'edit' ? config.formTitle.edit : config.formTitle.create;
            elements.formCard.hidden = false;

            await renderFormFields(config, item);
            const firstField = elements.form.querySelector('input, select, textarea');
            if (firstField) {
                firstField.focus();
            }
        }

        function closeForm(skipReset = false) {
            state.mode = 'create';
            state.editingId = null;
            state.renderedFields = [];
            elements.formCard.hidden = true;
            if (!skipReset) {
                elements.form.reset();
            }
        }

        async function renderFormFields(config, item) {
            state.renderedFields = [];
            const fieldsContainer = Array.from(elements.form.children).filter((child) => !child.classList.contains('form-actions'));
            fieldsContainer.forEach((child) => child.remove());

            for (const field of config.formFields) {
                const resolved = { ...field };
                if (field.optionsSource) {
                    resolved.options = await resolveOptions(field.optionsSource);
                }

                const wrapper = document.createElement('div');
                wrapper.className = 'field';

                const label = document.createElement('label');
                const fieldId = `${field.name}-${Math.random().toString(36).slice(2, 7)}`;
                label.setAttribute('for', fieldId);
                label.textContent = field.label + (field.required ? ' *' : '');

                let control;
                if (resolved.type === 'textarea') {
                    control = document.createElement('textarea');
                } else if (resolved.type === 'select') {
                    control = document.createElement('select');
                    if (resolved.placeholder) {
                        const placeholder = document.createElement('option');
                        placeholder.value = '';
                        placeholder.textContent = resolved.placeholder;
                        control.appendChild(placeholder);
                    }
                    (resolved.options || []).forEach((option) => {
                        const optionElement = document.createElement('option');
                        optionElement.value = option.value;
                        optionElement.textContent = option.label;
                        control.appendChild(optionElement);
                    });
                } else {
                    control = document.createElement('input');
                    control.type = resolved.type || 'text';
                }

                control.id = fieldId;
                control.name = resolved.name;
                control.required = Boolean(resolved.required);
                if (resolved.placeholder && resolved.type !== 'select') {
                    control.placeholder = resolved.placeholder;
                }
                if (resolved.step) {
                    control.step = resolved.step;
                }

                const value = item ? item[resolved.name] : resolved.defaultValue;
                if (value !== undefined && value !== null) {
                    control.value = String(value);
                } else if (state.mode === 'create' && resolved.type === 'select') {
                    control.value = '';
                } else if (resolved.defaultValue !== undefined && resolved.defaultValue !== null) {
                    control.value = String(resolved.defaultValue);
                }

                wrapper.append(label, control);
                if (resolved.hint) {
                    const hint = document.createElement('small');
                    hint.textContent = resolved.hint;
                    wrapper.appendChild(hint);
                }

                elements.form.insertBefore(wrapper, elements.form.querySelector('.form-actions'));
                state.renderedFields.push(resolved);
            }
        }

        async function ensureReferences(resource) {
            const config = resources[resource];
            const requirements = config.requires || [];
            for (const requirement of requirements) {
                if (!state.reference[requirement]) {
                    const data = await apiFetch(requirement);
                    state.reference[requirement] = Array.isArray(data) ? data : [];
                }
            }
        }

        async function resolveOptions(source) {
            if (source === 'categorias') {
                if (!state.reference.categorias) {
                    const data = await apiFetch('categorias');
                    state.reference.categorias = Array.isArray(data) ? data : [];
                }
                return state.reference.categorias.map((categoria) => ({
                    value: String(categoria.id),
                    label: categoria.nome,
                }));
            }
            return [];
        }

        function setLoading(isLoading) {
            elements.loading.hidden = !isLoading;
            if (isLoading) {
                elements.tableWrapper.hidden = true;
                elements.emptyState.hidden = true;
            }
        }

        function showFeedback(message, type = 'info') {
            elements.feedback.textContent = message;
            elements.feedback.className = `feedback ${type}`;
            elements.feedback.hidden = false;
            clearTimeout(showFeedback.timeoutId);
            showFeedback.timeoutId = setTimeout(() => {
                elements.feedback.hidden = true;
            }, type === 'error' ? 7000 : 3500);
        }

        function apiFetch(path, options = {}) {
            const method = (options.method || 'GET').toUpperCase();
            const headers = new Headers(options.headers || {});

            if (state.token) {
                headers.set('Authorization', `Bearer ${state.token}`);
            }

            if (options.body && !headers.has('Content-Type')) {
                headers.set('Content-Type', 'application/json');
            }

            return fetch(apiBase + path, {
                ...options,
                method,
                headers,
            }).then(async (response) => {
                const text = await response.text();
                let data = null;
                if (text) {
                    try {
                        data = JSON.parse(text);
                    } catch (_) {
                        data = text;
                    }
                }

                if (!response.ok) {
                    const message = (data && data.message) ? data.message : `Erro ${response.status}`;
                    const error = new Error(message);
                    error.status = response.status;
                    error.data = data;
                    throw error;
                }

                return data;
            }).catch((error) => {
                if (error instanceof Error) {
                    throw error;
                }
                throw new Error('Falha na comunicação com o servidor.');
            });
        }

        setResource('categorias').catch((error) => showFeedback(error.message || 'Não foi possível carregar a primeira sessão.', 'error'));
    </script>
>>>>>>> main
</body>
</html>
