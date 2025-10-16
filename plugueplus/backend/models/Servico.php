<?php

class Servico
{
    public static function all(): array
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->query('SELECT s.id, s.nome, s.descricao, s.telefone, s.site, s.endereco, s.latitude, s.longitude, c.nome AS categoria FROM servicos s JOIN categorias_servicos c ON c.id = s.categoria_id ORDER BY s.nome');
        return $stmt->fetchAll();
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
}
