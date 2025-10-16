<?php

require_once __DIR__ . '/../models/Post.php';

class PostController
{
    public static function index(): void
    {
        Response::json(Post::all());
    }

    public static function store(): void
    {
        $data = AuthHelper::getJsonInput();
        if (empty($data['titulo']) || empty($data['conteudo'])) {
            Response::json(['message' => 'Título e conteúdo são obrigatórios.'], 422);
            return;
        }

        $usuario = $GLOBALS['auth_user'] ?? null;
        if (!$usuario) {
            Response::json(['message' => 'Usuário não identificado.'], 401);
            return;
        }

        $id = Post::create([
            'titulo' => $data['titulo'],
            'conteudo' => $data['conteudo'],
            'usuario_id' => (int) $usuario['sub'],
        ]);

        Response::json(['id' => $id], 201);
    }
}
