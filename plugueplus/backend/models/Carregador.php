<?php

class Carregador
{
    public static function all(): array
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->query('SELECT id, nome, endereco, potencia_kw, tipo_conector, latitude, longitude, status, criado_em FROM carregadores ORDER BY nome');
        return $stmt->fetchAll();
    }

    public static function create(array $data): int
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('INSERT INTO carregadores (nome, endereco, potencia_kw, tipo_conector, latitude, longitude, status, criado_em) VALUES (:nome, :endereco, :potencia_kw, :tipo_conector, :latitude, :longitude, :status, NOW())');
        $stmt->execute([
            ':nome' => $data['nome'],
            ':endereco' => $data['endereco'] ?? null,
            ':potencia_kw' => $data['potencia_kw'] ?? null,
            ':tipo_conector' => $data['tipo_conector'] ?? null,
            ':latitude' => $data['latitude'] ?? null,
            ':longitude' => $data['longitude'] ?? null,
            ':status' => $data['status'] ?? 'ativo',
        ]);
        return (int) $pdo->lastInsertId();
    }
}
