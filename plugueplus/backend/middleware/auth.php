<?php

class AuthMiddleware
{
    public static function check(): bool
    {
        if (function_exists('getallheaders')) {
            $headers = getallheaders();
        } else {
            $headers = [];
            foreach ($_SERVER as $key => $value) {
                if (strpos($key, 'HTTP_') === 0) {
                    $name = str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($key, 5)))));
                    $headers[$name] = $value;
                }
            }
        }
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
