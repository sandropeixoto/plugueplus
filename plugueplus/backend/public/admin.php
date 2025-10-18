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
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Painel administrativo PluguePlus</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(180deg, #f4f6ff 0%, #ffffff 40%);
            min-height: 100vh;
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
        .form-control, .form-select {
            color: #111827;
        }
        .form-control::placeholder {
            color: #6b7280;
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
            }
        }
    </style>
</head>
<body>
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
</body>
</html>
