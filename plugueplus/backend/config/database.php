<?php

class Database
{
    private static ?PDO $connection = null;
    private static bool $schemaEnsured = false;

    public static function getConnection(): PDO
    {
        if (self::$connection === null) {
            $host = env('DB_HOST', '127.0.0.1');
            $port = (int) env('DB_PORT', 3306);
            $dbName = env('DB_NAME', 'plugueplus');
            $user = env('DB_USER', 'root');
            $password = env('DB_PASS', '');

            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];

            $dsn = sprintf(
                'mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4',
                $host,
                $port,
                $dbName
            );

            try {
                self::$connection = new PDO($dsn, $user, $password, $options);
            } catch (PDOException $exception) {
                if ((int) $exception->getCode() === 1049) {
                    $serverDsn = sprintf('mysql:host=%s;port=%s;charset=utf8mb4', $host, $port);

                    try {
                        $serverConnection = new PDO($serverDsn, $user, $password, $options);
                        $serverConnection->exec(
                            sprintf(
                                'CREATE DATABASE IF NOT EXISTS `%s` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci',
                                str_replace('`', '``', $dbName)
                            )
                        );
                        $serverConnection = null;

                        self::$connection = new PDO($dsn, $user, $password, $options);
                    } catch (PDOException $innerException) {
                        throw self::connectionError($innerException);
                    }
                } else {
                    throw self::connectionError($exception);
                }
            }

            self::ensureSchema(self::$connection);
        }

        return self::$connection;
    }

    private static function ensureSchema(PDO $connection): void
    {
        if (self::$schemaEnsured) {
            return;
        }

        self::runSchemaFile($connection);
        self::runSafeUpgrades($connection);

        self::$schemaEnsured = true;
    }

    private static function runSchemaFile(PDO $connection): void
    {
        $schemaFile = dirname(__DIR__) . '/sql/schema.sql';
        if (!is_file($schemaFile) || !is_readable($schemaFile)) {
            return;
        }

        $sql = file_get_contents($schemaFile);
        if ($sql === false) {
            return;
        }

        foreach (self::splitSqlStatements($sql) as $statement) {
            if ($statement !== '') {
                $connection->exec($statement);
            }
        }
    }

    private static function runSafeUpgrades(PDO $connection): void
    {
        // Categorias: garante o campo de ícone para bases mais antigas.
        self::ensureColumn(
            $connection,
            'categorias_servicos',
            'icone',
            'ADD COLUMN `icone` VARCHAR(80) DEFAULT NULL AFTER `nome`'
        );

        // Serviços: normaliza colunas opcionais para cadastro completo.
        self::ensureColumn(
            $connection,
            'servicos',
            'descricao',
            'ADD COLUMN `descricao` TEXT NULL AFTER `nome`'
        );
        self::ensureColumn(
            $connection,
            'servicos',
            'telefone',
            'ADD COLUMN `telefone` VARCHAR(40) NULL AFTER `descricao`'
        );
        self::ensureColumn(
            $connection,
            'servicos',
            'site',
            'ADD COLUMN `site` VARCHAR(160) NULL AFTER `telefone`'
        );
        self::ensureColumn(
            $connection,
            'servicos',
            'endereco',
            'ADD COLUMN `endereco` VARCHAR(255) NULL AFTER `site`'
        );
        self::ensureColumn(
            $connection,
            'servicos',
            'latitude',
            'ADD COLUMN `latitude` DECIMAL(10,7) NULL AFTER `endereco`'
        );
        self::ensureColumn(
            $connection,
            'servicos',
            'longitude',
            'ADD COLUMN `longitude` DECIMAL(10,7) NULL AFTER `latitude`'
        );
        self::ensureColumn(
            $connection,
            'servicos',
            'categoria_id',
            'ADD COLUMN `categoria_id` INT UNSIGNED NOT NULL AFTER `longitude`'
        );

        // Carregadores: garante metadados utilizados nos formulários atuais.
        self::ensureColumn(
            $connection,
            'carregadores',
            'potencia_kw',
            'ADD COLUMN `potencia_kw` DECIMAL(6,2) NULL AFTER `endereco`'
        );
        self::ensureColumn(
            $connection,
            'carregadores',
            'tipo_conector',
            'ADD COLUMN `tipo_conector` VARCHAR(120) NULL AFTER `potencia_kw`'
        );
        self::ensureColumn(
            $connection,
            'carregadores',
            'latitude',
            'ADD COLUMN `latitude` DECIMAL(10,7) NULL AFTER `tipo_conector`'
        );
        self::ensureColumn(
            $connection,
            'carregadores',
            'longitude',
            'ADD COLUMN `longitude` DECIMAL(10,7) NULL AFTER `latitude`'
        );
        self::ensureColumn(
            $connection,
            'carregadores',
            'status',
            "ADD COLUMN `status` VARCHAR(40) DEFAULT 'ativo' AFTER `longitude`"
        );
    }

    private static function ensureColumn(PDO $connection, string $table, string $column, string $definition): void
    {
        $tableSafe = str_replace('`', '``', $table);
        $columnSafe = str_replace('`', '``', $column);

        $statement = $connection->prepare("SHOW COLUMNS FROM `{$tableSafe}` LIKE :column");
        $statement->execute(['column' => $columnSafe]);

        if ($statement->fetch() === false) {
            $connection->exec("ALTER TABLE `{$tableSafe}` {$definition}");
        }
    }

    /**
     * Split SQL script into executable statements without breaking string literals.
     */
    private static function splitSqlStatements(string $sql): array
    {
        $statements = [];
        $buffer = '';
        $inString = false;
        $stringDelimiter = '';
        $length = strlen($sql);

        for ($i = 0; $i < $length; $i++) {
            $char = $sql[$i];

            if ($inString) {
                if ($char === $stringDelimiter) {
                    $slashes = 0;
                    $j = $i - 1;
                    while ($j >= 0 && $sql[$j] === '\\') {
                        $slashes++;
                        $j--;
                    }
                    if ($slashes % 2 === 0) {
                        $inString = false;
                        $stringDelimiter = '';
                    }
                }

                $buffer .= $char;
                continue;
            }

            if ($char === '\'' || $char === '"') {
                $inString = true;
                $stringDelimiter = $char;
                $buffer .= $char;
                continue;
            }

            if ($char === ';') {
                $statement = trim($buffer);
                if ($statement !== '') {
                    $statements[] = $statement;
                }
                $buffer = '';
                continue;
            }

            $buffer .= $char;
        }

        $statement = trim($buffer);
        if ($statement !== '') {
            $statements[] = $statement;
        }

        return $statements;
    }

    private static function connectionError(PDOException $exception): RuntimeException
    {
        $message = 'Não foi possível conectar ao banco de dados. Verifique as credenciais e tente novamente.';
        if (env('APP_ENV', 'production') !== 'production') {
            $message .= ' Detalhes: ' . $exception->getMessage();
        }

        error_log('[Database] ' . $exception->getMessage());

        return new RuntimeException($message, (int) $exception->getCode(), $exception);
    }
}
