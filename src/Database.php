<?php

declare(strict_types=1);

/**
 * OOP mysqli database wrapper with prepared-statement support.
 *
 * The Database class encapsulates the mysqli connection and provides a small,
 * safe API used by the rest of the (procedural) game code. A singleton instance
 * is exposed via Database::instance() so that both the class API and the
 * procedural helper wrappers share a single connection.
 */
final class Database
{
    private ?mysqli $connection = null;

    private static ?Database $instance = null;

    private function __construct(
        private readonly string $host,
        private readonly string $user,
        private readonly string $pass,
        private readonly string $name,
    ) {
        $this->connect();
    }

    public static function instance(): self
    {
        if (self::$instance === null) {
            $dbhost = $GLOBALS['dbhost'] ?? 'localhost';
            $database = $GLOBALS['database'] ?? 'fallendb';
            $dbuser = $GLOBALS['dbuser'] ?? 'fallen';
            $dbpass = $GLOBALS['dbpass'] ?? '';

            self::$instance = new self($dbhost, $dbuser, $dbpass, $database);
        }

        return self::$instance;
    }

    public static function reset(): void
    {
        self::$instance = null;
    }

    private function connect(): void
    {
        mysqli_report(MYSQLI_REPORT_OFF);
        $this->connection = @mysqli_connect($this->host, $this->user, $this->pass, $this->name);
        if (!$this->connection) {
            throw new RuntimeException('Database connection failed: ' . mysqli_connect_error());
        }
        mysqli_set_charset($this->connection, 'utf8');
    }

    public function connection(): mysqli
    {
        return $this->connection;
    }

    /**
     * Run a query. When $params is non-empty a prepared statement is used;
     * otherwise a plain query is executed.
     *
     * @param string $sql    SQL with ? placeholders when using $params
     * @param array  $params Positional bound parameters
     * @return mysqli_result|bool
     */
    public function query(string $sql, array $params = []): mysqli_result|bool
    {
        if (empty($params)) {
            return @mysqli_query($this->connection, $sql);
        }

        $stmt = @mysqli_prepare($this->connection, $sql);
        if ($stmt === false) {
            return false;
        }

        $types = '';
        foreach ($params as $param) {
            if (is_int($param)) {
                $types .= 'i';
            } elseif (is_float($param)) {
                $types .= 'd';
            } else {
                $types .= 's';
            }
        }

        mysqli_stmt_bind_param($stmt, $types, ...array_values($params));
        if (!@mysqli_stmt_execute($stmt)) {
            return false;
        }

        $result = @mysqli_stmt_get_result($stmt);
        if ($result === false && mysqli_stmt_errno($stmt) !== 0) {
            return false;
        }

        return $result === false ? true : $result;
    }

    /**
     * @return array<string, string>|false
     */
    public function fetchAssoc(mysqli_result $result): array|false
    {
        $row = mysqli_fetch_assoc($result);
        return $row === null ? false : $row;
    }

    /**
     * @return array<string|int, string>|false
     */
    public function fetchArray(mysqli_result $result, int $mode = MYSQLI_BOTH): array|false
    {
        $row = mysqli_fetch_array($result, $mode);
        return $row === null ? false : $row;
    }

    public function numRows(mysqli_result $result): int
    {
        return (int) mysqli_num_rows($result);
    }

    /**
     * @return int|string
     */
    public function insertId(): int|string
    {
        return mysqli_insert_id($this->connection);
    }

    public function error(): string
    {
        return (string) mysqli_error($this->connection);
    }

    public function escape(string $value): string
    {
        return mysqli_real_escape_string($this->connection, $value);
    }

    public function affectedRows(): int
    {
        return (int) mysqli_affected_rows($this->connection);
    }

    public function ping(): bool
    {
        return mysqli_ping($this->connection);
    }
}
