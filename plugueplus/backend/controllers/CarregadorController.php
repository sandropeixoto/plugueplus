<?php

require_once __DIR__ . '/../models/Carregador.php';

class CarregadorController
{
    public static function index(): void
    {
        Response::json(Carregador::all());
    }

    public static function show(array $params): void
    {
        $id = (int) ($params['id'] ?? 0);
        if ($id <= 0) {
            Response::json(['message' => 'ID inválido.'], 400);
            return;
        }

        $carregador = Carregador::find($id);
        if (!$carregador) {
            Response::json(['message' => 'Carregador não encontrado.'], 404);
            return;
        }

        Response::json($carregador);
    }

    public static function store(): void
    {
        $data = AuthHelper::getJsonInput();
        $nome = trim($data['nome'] ?? '');

        if ($nome === '') {
            Response::json(['message' => 'Nome é obrigatório.'], 422);
            return;
        }

        $id = Carregador::create([
            'nome' => $nome,
            'endereco' => trim($data['endereco'] ?? '') ?: null,
            'potencia_kw' => isset($data['potencia_kw']) && $data['potencia_kw'] !== '' ? (float) $data['potencia_kw'] : null,
            'tipo_conector' => trim($data['tipo_conector'] ?? '') ?: null,
            'latitude' => isset($data['latitude']) && $data['latitude'] !== '' ? (float) $data['latitude'] : null,
            'longitude' => isset($data['longitude']) && $data['longitude'] !== '' ? (float) $data['longitude'] : null,
            'status' => trim($data['status'] ?? '') ?: 'ativo',
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

        $carregador = Carregador::find($id);
        if (!$carregador) {
            Response::json(['message' => 'Carregador não encontrado.'], 404);
            return;
        }

        $data = AuthHelper::getJsonInput();
        $nome = trim($data['nome'] ?? $carregador['nome']);

        if ($nome === '') {
            Response::json(['message' => 'Nome é obrigatório.'], 422);
            return;
        }

        Carregador::update($id, [
            'nome' => $nome,
            'endereco' => array_key_exists('endereco', $data)
                ? (trim((string) $data['endereco']) ?: null)
                : ($carregador['endereco'] ?? null),
            'potencia_kw' => array_key_exists('potencia_kw', $data)
                ? ($data['potencia_kw'] === '' ? null : (float) $data['potencia_kw'])
                : ($carregador['potencia_kw'] ?? null),
            'tipo_conector' => array_key_exists('tipo_conector', $data)
                ? (trim((string) $data['tipo_conector']) ?: null)
                : ($carregador['tipo_conector'] ?? null),
            'latitude' => array_key_exists('latitude', $data)
                ? ($data['latitude'] === '' ? null : (float) $data['latitude'])
                : ($carregador['latitude'] ?? null),
            'longitude' => array_key_exists('longitude', $data)
                ? ($data['longitude'] === '' ? null : (float) $data['longitude'])
                : ($carregador['longitude'] ?? null),
            'status' => array_key_exists('status', $data)
                ? (trim((string) $data['status']) ?: null)
                : ($carregador['status'] ?? null),
        ]);

        Response::json(['message' => 'Carregador atualizado com sucesso.']);
    }

    public static function destroy(array $params): void
    {
        $id = (int) ($params['id'] ?? 0);
        if ($id <= 0) {
            Response::json(['message' => 'ID inválido.'], 400);
            return;
        }

        if (!Carregador::delete($id)) {
            Response::json(['message' => 'Carregador não encontrado.'], 404);
            return;
        }

        Response::json(['message' => 'Carregador excluído com sucesso.']);
    }
}
