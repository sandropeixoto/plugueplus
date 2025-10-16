<?php

class CategoriaServico
{
    public static function all(): array
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->query('SELECT id, nome, icone, criado_em FROM categorias_servicos ORDER BY nome');
        return $stmt->fetchAll();
    }

    public static function create(array $data): int
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('INSERT INTO categorias_servicos (nome, icone, criado_em) VALUES (:nome, :icone, NOW())');
        $stmt->execute([
            ':nome' => $data['nome'],
            ':icone' => $data['icone'] ?? null,
        ]);
        return (int) $pdo->lastInsertId();
    }
}
