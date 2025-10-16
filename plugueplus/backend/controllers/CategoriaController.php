<?php

require_once __DIR__ . '/../models/CategoriaServico.php';

class CategoriaController
{
    public static function index(): void
    {
        Response::json(CategoriaServico::all());
    }

    public static function store(): void
    {
        $data = AuthHelper::getJsonInput();
        if (empty($data['nome'])) {
            Response::json(['message' => 'Nome é obrigatório.'], 422);
            return;
        }

        $id = CategoriaServico::create($data);
        Response::json(['id' => $id], 201);
    }
}
