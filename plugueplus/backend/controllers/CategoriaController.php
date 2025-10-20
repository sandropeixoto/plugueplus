<?php

require_once __DIR__ . '/../models/CategoriaServico.php';

class CategoriaController
{
    public static function index(): void
    {
        Response::json(CategoriaServico::all());
    }

    public static function show(array $params): void
    {
        $id = (int) ($params['id'] ?? 0);
        if ($id <= 0) {
            Response::json(['message' => 'ID inválido.'], 400);
            return;
        }

        $categoria = CategoriaServico::find($id);
        if (!$categoria) {
            Response::json(['message' => 'Categoria não encontrada.'], 404);
            return;
        }

        Response::json($categoria);
    }

    public static function store(): void
    {
        $data = AuthHelper::getJsonInput();
        $nome = trim($data['nome'] ?? '');

        if ($nome === '') {
            Response::json(['message' => 'Nome é obrigatório.'], 422);
            return;
        }

        $id = CategoriaServico::create([
            'nome' => $nome,
            'icone' => trim($data['icone'] ?? '') ?: null,
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

        $categoria = CategoriaServico::find($id);
        if (!$categoria) {
            Response::json(['message' => 'Categoria não encontrada.'], 404);
            return;
        }

        $data = AuthHelper::getJsonInput();
        $nome = trim($data['nome'] ?? $categoria['nome']);
        if ($nome === '') {
            Response::json(['message' => 'Nome é obrigatório.'], 422);
            return;
        }

        CategoriaServico::update($id, [
            'nome' => $nome,
            'icone' => array_key_exists('icone', $data)
                ? (trim((string) $data['icone']) ?: null)
                : ($categoria['icone'] ?? null),
        ]);

        Response::json(['message' => 'Categoria atualizada com sucesso.']);
    }

    public static function destroy(array $params): void
    {
        $id = (int) ($params['id'] ?? 0);
        if ($id <= 0) {
            Response::json(['message' => 'ID inválido.'], 400);
            return;
        }

        if (!CategoriaServico::delete($id)) {
            Response::json(['message' => 'Categoria não encontrada.'], 404);
            return;
        }

        Response::json(['message' => 'Categoria excluída com sucesso.']);
    }
}
