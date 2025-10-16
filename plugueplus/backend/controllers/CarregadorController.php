<?php

require_once __DIR__ . '/../models/Carregador.php';

class CarregadorController
{
    public static function index(): void
    {
        Response::json(Carregador::all());
    }

    public static function store(): void
    {
        $data = AuthHelper::getJsonInput();
        if (empty($data['nome'])) {
            Response::json(['message' => 'Nome é obrigatório.'], 422);
            return;
        }

        $id = Carregador::create($data);
        Response::json(['id' => $id], 201);
    }
}
