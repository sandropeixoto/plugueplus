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

        $schemaFile = dirname(__DIR__) . '/sql/schema.sql';
        if (!is_file($schemaFile) || !is_readable($schemaFile)) {
            self::$schemaEnsured = true;
            return;
        }

        $sql = file_get_contents($schemaFile);
        if ($sql === false) {
            self::$schemaEnsured = true;
            return;
        }

        foreach (self::splitSqlStatements($sql) as $statement) {
            if ($statement !== '') {
                $connection->exec($statement);
            }
        }

        self::$schemaEnsured = true;
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
