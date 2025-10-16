<?php

require_once __DIR__ . '/../models/Comentario.php';

class ComentarioController
{
    public static function index(): void
    {
        Response::json(Comentario::all());
    }

    public static function store(): void
    {
        $data = AuthHelper::getJsonInput();
        if (empty($data['conteudo']) || empty($data['post_id'])) {
            Response::json(['message' => 'Conteúdo e post são obrigatórios.'], 422);
            return;
        }

        $usuario = $GLOBALS['auth_user'] ?? null;
        if (!$usuario) {
            Response::json(['message' => 'Usuário não identificado.'], 401);
            return;
        }

        $id = Comentario::create([
            'conteudo' => $data['conteudo'],
            'post_id' => (int) $data['post_id'],
            'usuario_id' => (int) $usuario['sub'],
        ]);

        Response::json(['id' => $id], 201);
    }
}
