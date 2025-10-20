<?php

require_once __DIR__ . '/../models/Servico.php';

class ServicoController
{
    public static function index(): void
    {
        Response::json(Servico::all());
    }

    public static function show(array $params): void
    {
        $id = (int) ($params['id'] ?? 0);
        if ($id <= 0) {
            Response::json(['message' => 'ID inválido.'], 400);
            return;
        }

        $servico = Servico::find($id);
        if (!$servico) {
            Response::json(['message' => 'Serviço não encontrado.'], 404);
            return;
        }

        Response::json($servico);
    }

    public static function store(): void
    {
        $data = AuthHelper::getJsonInput();
        $nome = trim($data['nome'] ?? '');
        $categoriaId = isset($data['categoria_id']) ? (int) $data['categoria_id'] : 0;

        if ($nome === '' || $categoriaId <= 0) {
            Response::json(['message' => 'Nome e categoria são obrigatórios.'], 422);
            return;
        }

        $id = Servico::create([
            'nome' => $nome,
            'categoria_id' => $categoriaId,
            'descricao' => trim($data['descricao'] ?? '') ?: null,
            'telefone' => trim($data['telefone'] ?? '') ?: null,
            'site' => trim($data['site'] ?? '') ?: null,
            'endereco' => trim($data['endereco'] ?? '') ?: null,
            'latitude' => isset($data['latitude']) && $data['latitude'] !== '' ? (float) $data['latitude'] : null,
            'longitude' => isset($data['longitude']) && $data['longitude'] !== '' ? (float) $data['longitude'] : null,
        ]);
        Response::json(['id' => $id], 201);
    }

    public static function update(array $params): void
    {
        $id = (int) ($params['id'] ?? 0);
        if ($id <= 0) {
            Response::json(['message' => 'ID inválido.'], 400);
            return;
        }

        $servico = Servico::find($id);
        if (!$servico) {
            Response::json(['message' => 'Serviço não encontrado.'], 404);
            return;
        }

        $data = AuthHelper::getJsonInput();
        $nome = trim($data['nome'] ?? $servico['nome']);
        $categoriaId = isset($data['categoria_id']) ? (int) $data['categoria_id'] : (int) $servico['categoria_id'];

        if ($nome === '' || $categoriaId <= 0) {
            Response::json(['message' => 'Nome e categoria são obrigatórios.'], 422);
            return;
        }

        Servico::update($id, [
            'nome' => $nome,
            'categoria_id' => $categoriaId,
            'descricao' => array_key_exists('descricao', $data)
                ? (trim((string) $data['descricao']) ?: null)
                : ($servico['descricao'] ?? null),
            'telefone' => array_key_exists('telefone', $data)
                ? (trim((string) $data['telefone']) ?: null)
                : ($servico['telefone'] ?? null),
            'site' => array_key_exists('site', $data)
                ? (trim((string) $data['site']) ?: null)
                : ($servico['site'] ?? null),
            'endereco' => array_key_exists('endereco', $data)
                ? (trim((string) $data['endereco']) ?: null)
                : ($servico['endereco'] ?? null),
            'latitude' => array_key_exists('latitude', $data)
                ? ($data['latitude'] === '' ? null : (float) $data['latitude'])
                : ($servico['latitude'] ?? null),
            'longitude' => array_key_exists('longitude', $data)
                ? ($data['longitude'] === '' ? null : (float) $data['longitude'])
                : ($servico['longitude'] ?? null),
        ]);

        Response::json(['message' => 'Serviço atualizado com sucesso.']);
    }

    public static function destroy(array $params): void
    {
        $id = (int) ($params['id'] ?? 0);
        if ($id <= 0) {
            Response::json(['message' => 'ID inválido.'], 400);
            return;
        }

        if (!Servico::delete($id)) {
            Response::json(['message' => 'Serviço não encontrado.'], 404);
            return;
        }

        Response::json(['message' => 'Serviço excluído com sucesso.']);
    }
}
