<?php

class Post
{
    public static function all(): array
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->query('SELECT p.id, p.titulo, p.conteudo, p.criado_em, u.nome AS autor FROM posts p JOIN usuarios u ON u.id = p.usuario_id ORDER BY p.criado_em DESC');
        return $stmt->fetchAll();
    }

    public static function create(array $data): int
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('INSERT INTO posts (titulo, conteudo, usuario_id, criado_em) VALUES (:titulo, :conteudo, :usuario_id, NOW())');
        $stmt->execute([
            ':titulo' => $data['titulo'],
            ':conteudo' => $data['conteudo'],
            ':usuario_id' => $data['usuario_id'],
        ]);
        return (int) $pdo->lastInsertId();
    }
}
