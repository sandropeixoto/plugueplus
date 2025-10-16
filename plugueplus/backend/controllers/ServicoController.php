<?php

require_once __DIR__ . '/../models/Servico.php';

class ServicoController
{
    public static function index(): void
    {
        Response::json(Servico::all());
    }

    public static function store(): void
    {
        $data = AuthHelper::getJsonInput();
        if (empty($data['nome']) || empty($data['categoria_id'])) {
            Response::json(['message' => 'Nome e categoria são obrigatórios.'], 422);
            return;
        }

        $id = Servico::create($data);
        Response::json(['id' => $id], 201);
    }
}
