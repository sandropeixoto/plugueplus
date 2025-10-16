<?php

require_once __DIR__ . '/../models/User.php';

class AuthController
{
    public static function register(): void
    {
        $data = AuthHelper::getJsonInput();

        if (empty($data['nome']) || empty($data['email']) || empty($data['senha'])) {
            Response::json(['message' => 'Nome, email e senha são obrigatórios.'], 422);
            return;
        }

        if (User::findByEmail($data['email'])) {
            Response::json(['message' => 'Email já cadastrado.'], 409);
            return;
        }

        $id = User::create($data);
        $user = User::findById($id);
        $token = AuthHelper::createToken(['id' => $user['id'], 'email' => $user['email']]);

        Response::json(['user' => $user, 'token' => $token], 201);
    }

    public static function login(): void
    {
        $data = AuthHelper::getJsonInput();
        if (empty($data['email']) || empty($data['senha'])) {
            Response::json(['message' => 'Email e senha são obrigatórios.'], 422);
            return;
        }

        $user = User::findByEmail($data['email']);
        if (!$user || !password_verify($data['senha'], $user['senha_hash'])) {
            Response::json(['message' => 'Credenciais inválidas.'], 401);
            return;
        }

        $token = AuthHelper::createToken(['id' => $user['id'], 'email' => $user['email']]);
        unset($user['senha_hash']);

        Response::json(['user' => $user, 'token' => $token]);
    }

    public static function profile(): void
    {
        $payload = $GLOBALS['auth_user'] ?? null;
        if (!$payload) {
            Response::json(['message' => 'Unauthorized'], 401);
            return;
        }

        $user = User::findById((int) $payload['sub']);
        if (!$user) {
            Response::json(['message' => 'Usuário não encontrado.'], 404);
            return;
        }

        Response::json($user);
    }
}
