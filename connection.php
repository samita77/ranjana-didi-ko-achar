<?php
/**
 * Lightweight database connector that works with MariaDB/MySQL (via PDO MySQL)
 * and SQLite without requiring Composer. It exposes a mysqli-like surface so
 * the rest of the codebase can stay unchanged.
 */

if (!defined('MYSQLI_ASSOC')) {
    define('MYSQLI_ASSOC', 1);
}

class PDOMysqliResult
{
    private $rows = [];
    private $index = 0;
    public $num_rows = 0;

    public function __construct(PDOStatement $statement)
    {
        $this->rows = $statement->fetchAll(PDO::FETCH_ASSOC);
        $this->num_rows = count($this->rows);
    }

    public function fetch_assoc()
    {
        return $this->rows[$this->index++] ?? null;
    }

    public function fetch_all($mode = MYSQLI_ASSOC)
    {
        return $this->rows;
    }
}

class PDOMysqliStatement
{
    private $stmt;
    private $pdo;
    private $types = '';
    private $bound = [];
    public $insert_id = null;

    public function __construct(PDOStatement $stmt, PDO $pdo)
    {
        $this->stmt = $stmt;
        $this->pdo = $pdo;
    }

    public function bind_param($types, &...$vars)
    {
        $this->types = (string) $types;
        $this->bound = &$vars;
    }

    private function castValue($value, $type)
    {
        switch ($type) {
            case 'i':
                return (int) $value;
            case 'd':
                return (float) $value;
            default:
                return $value;
        }
    }

    public function execute()
    {
        $params = [];
        foreach ($this->bound as $idx => &$value) {
            $type = $this->types[$idx] ?? 's';
            $params[] = $this->castValue($value, $type);
        }

        $this->stmt->execute($params);
        $this->insert_id = $this->pdo->lastInsertId();

        return true;
    }

    public function get_result()
    {
        return new PDOMysqliResult($this->stmt);
    }

    public function close()
    {
        $this->stmt = null;
    }
}

class PDOMysqliConnection
{
    private $pdo;
    public $driver = 'mysql';
    public $error = '';

    public function __construct($driver, $dsn, $user = null, $password = null, array $options = [])
    {
        $this->driver = $driver;
        $defaultOptions = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ];

        try {
            $this->pdo = new PDO($dsn, $user, $password, $options + $defaultOptions);
        } catch (Throwable $e) {
            $this->error = $e->getMessage();
            throw $e;
        }
    }

    public function prepare($sql)
    {
        return new PDOMysqliStatement($this->pdo->prepare($sql), $this->pdo);
    }

    public function query($sql)
    {
        $statement = $this->pdo->query($sql);
        return new PDOMysqliResult($statement);
    }

    public function real_escape_string($value)
    {
        $quoted = $this->pdo->quote($value);
        return substr($quoted, 1, -1);
    }

    public function __get($name)
    {
        if ($name === 'insert_id') {
            return $this->pdo->lastInsertId();
        }

        return null;
    }
}

function build_database_connection()
{
    $driver = strtolower(getenv('DB_DRIVER') ?: 'mysql');
    $driver = $driver === 'mariadb' ? 'mysql' : $driver;

    if ($driver === 'sqlite') {
        $path = getenv('SQLITE_PATH') ?: __DIR__ . '/storage/database.sqlite';
        $directory = dirname($path);
        if (!is_dir($directory)) {
            mkdir($directory, 0775, true);
        }

        $dsn = "sqlite:$path";
        return new PDOMysqliConnection('sqlite', $dsn);
    }

    $host = getenv('DB_HOST') ?: 'localhost';
    $port = getenv('DB_PORT') ?: '3307';
    $dbname = getenv('DB_NAME') ?: 'inventory';
    $username = getenv('DB_USER') ?: 'root';
    $password = getenv('DB_PASSWORD') ?: '';

    $dsn = "mysql:host={$host};port={$port};dbname={$dbname};charset=utf8mb4";
    return new PDOMysqliConnection('mysql', $dsn, $username, $password, [
        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4',
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
}

try {
    $conn = build_database_connection();
} catch (Throwable $e) {
    die('Connection failed: ' . $e->getMessage());
}
