<?php

class Comentario
{
    public static function all(): array
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->query('SELECT c.id, c.conteudo, c.criado_em, p.id AS post_id, u.nome AS autor FROM comentarios c JOIN posts p ON p.id = c.post_id JOIN usuarios u ON u.id = c.usuario_id ORDER BY c.criado_em ASC');
        return $stmt->fetchAll();
    }

    public static function create(array $data): int
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('INSERT INTO comentarios (conteudo, post_id, usuario_id, criado_em) VALUES (:conteudo, :post_id, :usuario_id, NOW())');
        $stmt->execute([
            ':conteudo' => $data['conteudo'],
            ':post_id' => $data['post_id'],
            ':usuario_id' => $data['usuario_id'],
        ]);
        return (int) $pdo->lastInsertId();
    }
}
