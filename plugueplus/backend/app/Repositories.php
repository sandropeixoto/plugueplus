<?php

require_once __DIR__ . '/../config/database.php';

class CategoriaRepository
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
        $stmt = $pdo->prepare('SELECT id, nome, icone FROM categorias_servicos WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $categoria = $stmt->fetch();

        return $categoria ?: null;
    }

    public static function create(string $nome, ?string $icone): int
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('INSERT INTO categorias_servicos (nome, icone) VALUES (:nome, :icone)');
        $stmt->execute([
            'nome' => $nome,
            'icone' => $icone !== '' ? $icone : null,
        ]);

        return (int) $pdo->lastInsertId();
    }

    public static function update(int $id, string $nome, ?string $icone): void
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('UPDATE categorias_servicos SET nome = :nome, icone = :icone WHERE id = :id');
        $stmt->execute([
            'nome' => $nome,
            'icone' => $icone !== '' ? $icone : null,
            'id' => $id,
        ]);
    }

    public static function delete(int $id): void
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('DELETE FROM categorias_servicos WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }
}

class ServicoRepository
{
    public static function all(): array
    {
        $pdo = Database::getConnection();
        $sql = 'SELECT s.id, s.nome, s.descricao, s.telefone, s.site, s.endereco, s.latitude, s.longitude, s.categoria_id, s.criado_em, c.nome AS categoria_nome
                FROM servicos s
                LEFT JOIN categorias_servicos c ON c.id = s.categoria_id
                ORDER BY s.nome';
        $stmt = $pdo->query($sql);
        return $stmt->fetchAll();
    }

    public static function find(int $id): ?array
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('SELECT * FROM servicos WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $servico = $stmt->fetch();

        return $servico ?: null;
    }

    public static function create(array $data): int
    {
        $pdo = Database::getConnection();
        $sql = 'INSERT INTO servicos (nome, descricao, telefone, site, endereco, latitude, longitude, categoria_id)
                VALUES (:nome, :descricao, :telefone, :site, :endereco, :latitude, :longitude, :categoria_id)';
        $stmt = $pdo->prepare($sql);
        $stmt->execute(self::normalize($data));

        return (int) $pdo->lastInsertId();
    }

    public static function update(int $id, array $data): void
    {
        $pdo = Database::getConnection();
        $sql = 'UPDATE servicos SET nome = :nome, descricao = :descricao, telefone = :telefone, site = :site, endereco = :endereco,
                latitude = :latitude, longitude = :longitude, categoria_id = :categoria_id WHERE id = :id';
        $params = self::normalize($data);
        $params['id'] = $id;
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
    }

    public static function delete(int $id): void
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('DELETE FROM servicos WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }

    private static function normalize(array $data): array
    {
        return [
            'nome' => $data['nome'],
            'descricao' => $data['descricao'] !== '' ? $data['descricao'] : null,
            'telefone' => $data['telefone'] !== '' ? $data['telefone'] : null,
            'site' => $data['site'] !== '' ? $data['site'] : null,
            'endereco' => $data['endereco'] !== '' ? $data['endereco'] : null,
            'latitude' => $data['latitude'] !== '' ? $data['latitude'] : null,
            'longitude' => $data['longitude'] !== '' ? $data['longitude'] : null,
            'categoria_id' => (int) $data['categoria_id'],
        ];
    }
}

class CarregadorRepository
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
        $stmt = $pdo->prepare('SELECT * FROM carregadores WHERE id = :id');
        $stmt->execute(['id' => $id]);
        $carregador = $stmt->fetch();

        return $carregador ?: null;
    }

    public static function create(array $data): int
    {
        $pdo = Database::getConnection();
        $sql = 'INSERT INTO carregadores (nome, endereco, potencia_kw, tipo_conector, latitude, longitude, status)
                VALUES (:nome, :endereco, :potencia_kw, :tipo_conector, :latitude, :longitude, :status)';
        $stmt = $pdo->prepare($sql);
        $stmt->execute(self::normalize($data));

        return (int) $pdo->lastInsertId();
    }

    public static function update(int $id, array $data): void
    {
        $pdo = Database::getConnection();
        $sql = 'UPDATE carregadores SET nome = :nome, endereco = :endereco, potencia_kw = :potencia_kw,
                tipo_conector = :tipo_conector, latitude = :latitude, longitude = :longitude, status = :status
                WHERE id = :id';
        $params = self::normalize($data);
        $params['id'] = $id;
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
    }

    public static function delete(int $id): void
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare('DELETE FROM carregadores WHERE id = :id');
        $stmt->execute(['id' => $id]);
    }

    private static function normalize(array $data): array
    {
        return [
            'nome' => $data['nome'],
            'endereco' => $data['endereco'] !== '' ? $data['endereco'] : null,
            'potencia_kw' => $data['potencia_kw'] !== '' ? $data['potencia_kw'] : null,
            'tipo_conector' => $data['tipo_conector'] !== '' ? $data['tipo_conector'] : null,
            'latitude' => $data['latitude'] !== '' ? $data['latitude'] : null,
            'longitude' => $data['longitude'] !== '' ? $data['longitude'] : null,
            'status' => $data['status'] !== '' ? $data['status'] : 'ativo',
        ];
    }
}
