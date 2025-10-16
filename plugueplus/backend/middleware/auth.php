<?php

class AuthMiddleware
{
    public static function check(): bool
    {
        $headers = getallheaders();
        $authorization = $headers['Authorization'] ?? $headers['authorization'] ?? '';

        if (!str_starts_with($authorization, 'Bearer ')) {
            Response::json(['message' => 'Unauthorized'], 401);
            return false;
        }

        $token = trim(substr($authorization, 7));
        $payload = AuthHelper::validateToken($token);
        if (!$payload) {
            Response::json(['message' => 'Invalid token'], 401);
            return false;
        }

        $GLOBALS['auth_user'] = $payload;
        return true;
    }
}
