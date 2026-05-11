<?php

/**
 * PDO singleton for the BEED Student Portal.
 *
 * Credentials are read from environment variables with sensible defaults:
 *   DB_HOST  — defaults to "localhost"
 *   DB_NAME  — defaults to "beed_portal"
 *   DB_USER  — defaults to "root"
 *   DB_PASS  — defaults to ""
 */
class Database
{
    private static ?PDO $instance = null;

    /**
     * Returns the shared PDO connection, creating it on first call.
     */
    public static function getConnection(): PDO
    {
        if (self::$instance === null) {
            $host    = getenv('DB_HOST') ?: 'localhost';
            $dbName  = getenv('DB_NAME') ?: 'beed_portal';
            $user    = getenv('DB_USER') ?: 'root';
            $pass    = getenv('DB_PASS') ?: '';

            $dsn = "mysql:host={$host};dbname={$dbName};charset=utf8mb4";

            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            try {
                self::$instance = new PDO($dsn, $user, $pass, $options);
            } catch (PDOException $e) {
                // Log the real error server-side; never expose it to the browser.
                error_log('Database connection failed: ' . $e->getMessage());
                throw new RuntimeException('Could not connect to the database. Please try again later.');
            }
        }

        return self::$instance;
    }

    /**
     * Resets the singleton — useful in tests that need a fresh connection.
     */
    public static function reset(): void
    {
        self::$instance = null;
    }

    // Prevent instantiation and cloning.
    private function __construct() {}
    private function __clone() {}
}
