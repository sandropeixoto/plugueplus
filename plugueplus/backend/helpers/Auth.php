<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthHelper
{
    public static function createToken(array $user): string
    {
        $payload = [
            'iss' => 'plugueplus-api',
            'aud' => 'plugueplus-frontend',
            'iat' => time(),
            'nbf' => time(),
            'exp' => time() + 60 * 60 * 24,
            'sub' => $user['id'],
            'email' => $user['email'],
        ];

        return JWT::encode($payload, env('JWT_SECRET', 'changeme'), 'HS256');
    }

    public static function validateToken(string $token): ?array
    {
        try {
            $decoded = JWT::decode($token, new Key(env('JWT_SECRET', 'changeme'), 'HS256'));
            return (array) $decoded;
        } catch (Throwable $e) {
            return null;
        }
    }

    public static function getJsonInput(): array
    {
        $input = file_get_contents('php://input');
        $decoded = json_decode($input, true);
        return is_array($decoded) ? $decoded : [];
    }
}
