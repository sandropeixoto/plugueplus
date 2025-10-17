<?php

class Servico
{
    public static function all(): array
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->query('SELECT s.id, s.nome, s.descricao, s.telefone, s.site, s.endereco, s.latitude, s.longitude, s.categoria_id, c.nome AS categoria_nome, c.nome AS categoria, s.criado_em FROM servicos s JOIN categorias_servicos c ON c.id = s.categoria_id ORDER BY s.nome');
        return $stmt->fetchAll();
    }

    public static function find(int $id): ?array
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('SELECT s.id, s.nome, s.descricao, s.telefone, s.site, s.endereco, s.latitude, s.longitude, s.categoria_id, c.nome AS categoria_nome, c.nome AS categoria, s.criado_em FROM servicos s JOIN categorias_servicos c ON c.id = s.categoria_id WHERE s.id = :id');
        $stmt->execute([':id' => $id]);
        $result = $stmt->fetch();
        return $result === false ? null : $result;
    }

    public static function create(array $data): int
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('INSERT INTO servicos (nome, descricao, telefone, site, endereco, latitude, longitude, categoria_id, criado_em) VALUES (:nome, :descricao, :telefone, :site, :endereco, :latitude, :longitude, :categoria_id, NOW())');
        $stmt->execute([
            ':nome' => $data['nome'],
            ':descricao' => $data['descricao'] ?? null,
            ':telefone' => $data['telefone'] ?? null,
            ':site' => $data['site'] ?? null,
            ':endereco' => $data['endereco'] ?? null,
            ':latitude' => $data['latitude'] ?? null,
            ':longitude' => $data['longitude'] ?? null,
            ':categoria_id' => $data['categoria_id'],
        ]);
        return (int) $pdo->lastInsertId();
    }

    public static function update(int $id, array $data): void
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('UPDATE servicos SET nome = :nome, descricao = :descricao, telefone = :telefone, site = :site, endereco = :endereco, latitude = :latitude, longitude = :longitude, categoria_id = :categoria_id WHERE id = :id');
        $stmt->execute([
            ':nome' => $data['nome'],
            ':descricao' => $data['descricao'] ?? null,
            ':telefone' => $data['telefone'] ?? null,
            ':site' => $data['site'] ?? null,
            ':endereco' => $data['endereco'] ?? null,
            ':latitude' => $data['latitude'] ?? null,
            ':longitude' => $data['longitude'] ?? null,
            ':categoria_id' => $data['categoria_id'],
            ':id' => $id,
        ]);
    }

    public static function delete(int $id): bool
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('DELETE FROM servicos WHERE id = :id');
        $stmt->execute([':id' => $id]);
        return $stmt->rowCount() > 0;
    }
}
