<?php

class Carregador
{
    public static function all(): array
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->query('SELECT id, nome, endereco, potencia_kw, tipo_conector, latitude, longitude, status, criado_em FROM carregadores ORDER BY nome');
        return $stmt->fetchAll();
    }

    public static function find(int $id): ?array
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('SELECT id, nome, endereco, potencia_kw, tipo_conector, latitude, longitude, status, criado_em FROM carregadores WHERE id = :id');
        $stmt->execute([':id' => $id]);
        $result = $stmt->fetch();
        return $result === false ? null : $result;
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

    public static function update(int $id, array $data): void
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('UPDATE carregadores SET nome = :nome, endereco = :endereco, potencia_kw = :potencia_kw, tipo_conector = :tipo_conector, latitude = :latitude, longitude = :longitude, status = :status WHERE id = :id');
        $stmt->execute([
            ':nome' => $data['nome'],
            ':endereco' => $data['endereco'] ?? null,
            ':potencia_kw' => $data['potencia_kw'] ?? null,
            ':tipo_conector' => $data['tipo_conector'] ?? null,
            ':latitude' => $data['latitude'] ?? null,
            ':longitude' => $data['longitude'] ?? null,
            ':status' => $data['status'] ?? null,
            ':id' => $id,
        ]);
    }

    public static function delete(int $id): bool
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('DELETE FROM carregadores WHERE id = :id');
        $stmt->execute([':id' => $id]);
        return $stmt->rowCount() > 0;
    }
}
