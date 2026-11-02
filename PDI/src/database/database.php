<?php

declare(strict_types=1);

function getDatabaseConfig(): array
{
    return [
        'host' => $_ENV['DB_HOST'] ?? '127.0.0.1',
        'port' => $_ENV['DB_PORT'] ?? '3306',
        'dbname' => $_ENV['DB_NAME'] ?? 'Emprendi',
        'user' => $_ENV['DB_USER'] ?? 'root',
        'pass' => $_ENV['DB_PASSWORD'] ?? ($_ENV['DB_PASS'] ?? ''),
        'charset' => $_ENV['DB_CHARSET'] ?? 'utf8mb4',
    ];
}

function createPdoConnection(?string $databaseName = null): PDO
{
    $config = getDatabaseConfig();
    $host = $config['host'];
    $port = $config['port'];
    $user = $config['user'];
    $pass = $config['pass'];
    $charset = $config['charset'];

    $dsn = $databaseName === null
        ? sprintf('mysql:host=%s;port=%s;charset=%s', $host, $port, $charset)
        : sprintf('mysql:host=%s;port=%s;dbname=%s;charset=%s', $host, $port, $databaseName, $charset);

    try {
        return new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
    } catch (PDOException $exception) {
        $context = $databaseName === null ? 'MySQL' : $databaseName;

        throw new RuntimeException(
            sprintf(
                'No se pudo conectar a %s en %s:%s. %s',
                $context,
                $host,
                $port,
                $exception->getMessage(),
            ),
            0,
            $exception,
        );
    }
}

function getDB(): PDO
{
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $pdo = createPdoConnection(getDatabaseConfig()['dbname']);

    return $pdo;
}

function getDBWithoutDatabase(): PDO
{
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $pdo = createPdoConnection();

    return $pdo;
}

class Database
{
    public function getConnection(): PDO
    {
        return getDB();
    }

    public function getConnectionWithoutDatabase(): PDO
    {
        return getDBWithoutDatabase();
    }

    /**
     * @param callable(PDO): mixed $transaction
     * @return mixed
     */
    public function runTransaction(callable $transaction)
    {
        $connection = $this->getConnection();

        if ($connection->inTransaction()) {
            throw new RuntimeException('No se pueden anidar transacciones.');
        }

        try {
            $connection->beginTransaction();
            $result = $transaction($connection);
            $connection->commit();

            return $result;
        } catch (Throwable $exception) {
            if ($connection->inTransaction()) {
                $connection->rollBack();
            }

            throw $exception;
        }
    }
}
