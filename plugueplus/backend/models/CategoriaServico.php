<?php

class CategoriaServico
{
    public static function all(): array
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->query('SELECT id, nome, icone, criado_em FROM categorias_servicos ORDER BY nome');
        return $stmt->fetchAll();
    }

    public static function find(int $id): ?array
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('SELECT id, nome, icone, criado_em FROM categorias_servicos WHERE id = :id');
        $stmt->execute([':id' => $id]);
        $result = $stmt->fetch();
        return $result === false ? null : $result;
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

    public static function update(int $id, array $data): void
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('UPDATE categorias_servicos SET nome = :nome, icone = :icone WHERE id = :id');
        $stmt->execute([
            ':nome' => $data['nome'],
            ':icone' => $data['icone'] ?? null,
            ':id' => $id,
        ]);
    }

    public static function delete(int $id): bool
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('DELETE FROM categorias_servicos WHERE id = :id');
        $stmt->execute([':id' => $id]);
        return $stmt->rowCount() > 0;
    }
}
