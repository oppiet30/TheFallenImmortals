<?php

declare(strict_types=1);

/**
 * Procedural helper wrappers around the OOP Database class.
 *
 * These thin functions let the existing (procedural) game code keep working
 * while all real work is delegated to the Database singleton. The Database
 * class is auto-loaded via an autoloader registered below, or can be
 * explicitly declared if a PSR-4/composer autoload is not present.
 */

if (!class_exists('Database')) {
    require_once __DIR__ . '/Database.php';
}

/**
 * "Connect" hook kept for compatibility. The Database singleton establishes
 * the connection lazily on first use, reading the global credential variables
 * that have historically been set by db-conn.php / db.php.
 *
 * @param string $host MySQL host
 * @param string $user MySQL user
 * @param string $pass MySQL password
 * @param string $database Database name
 */
function db_connect(string $host, string $user, string $pass, string $database): void
{
    $GLOBALS['dbhost'] = $host;
    $GLOBALS['dbuser'] = $user;
    $GLOBALS['dbpass'] = $pass;
    $GLOBALS['database'] = $database;

    Database::instance();
}

/**
 * Execute a query, using prepared statements when parameters are provided.
 *
 * @param string $sql    SQL query with ? placeholders when using params
 * @param array  $params Bound parameter values
 * @return mysqli_result|bool
 */
function db_query(string $sql, array $params = []): mysqli_result|bool
{
    return Database::instance()->query($sql, $params);
}

/**
 * Fetch a result row as an associative array.
 *
 * @return array<string, string>|false
 */
function db_fetch_assoc(mysqli_result $result): array|false
{
    return Database::instance()->fetchAssoc($result);
}

/**
 * Fetch a result row as a numeric array, associative array, or both.
 *
 * @return array<string|int, string>|false
 */
function db_fetch_array(mysqli_result $result, int $result_mode = MYSQLI_BOTH): array|false
{
    return Database::instance()->fetchArray($result, $result_mode);
}

/**
 * Get number of rows in a result set.
 */
function db_num_rows(mysqli_result $result): int
{
    return Database::instance()->numRows($result);
}

/**
 * Get the ID generated from the previous INSERT operation.
 *
 * @return int|string
 */
function db_insert_id(): int|string
{
    return Database::instance()->insertId();
}

/**
 * Returns the error message from the most recent mysqli function call.
 */
function db_error(): string
{
    return Database::instance()->error();
}

/**
 * Escape a string for use in a SQL query (fallback when prepared statements
 * aren't practical).
 */
function db_escape(string $string): string
{
    return Database::instance()->escape($string);
}

/**
 * Fetch a result row as a numeric array.
 *
 * @return array<int, string>|false
 */
function db_fetch_row(mysqli_result $result): array|false
{
    $row = mysqli_fetch_row($result);
    return $row === null ? false : $row;
}

/**
 * Build a base URL (scheme + host + script directory, no trailing slash) from
 * the current request.
 *
 * Used in email templates and redirects so the legacy hardcoded
 * thefallenimmortals.com domain is replaced by whatever host is serving the
 * site. The script directory keeps links working when the game is served under
 * a user dir / sub-path (e.g. http://host/~oppie/TheFallenImmortals).
 */
function db_base_url(): string
{
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? ($_SERVER['SERVER_NAME'] ?? 'localhost');
    $path = '';
    if (!empty($_SERVER['SCRIPT_NAME'])) {
        $dir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
        if ($dir !== '/' && $dir !== '.' && $dir !== '') {
            $path = rtrim($dir, '/');
        }
    }
    return $scheme . '://' . $host . $path;
}
