<?php

/**
 * PDO singleton for the BEED Student Portal.
 *
 * For local XAMPP:
 *   DB_HOST=localhost, DB_NAME=beed_portal, DB_USER=root, DB_PASS=
 *
 * For InfinityFree deployment:
 *   Set these values directly below or via environment variables.
 */
class Database
{
    private static ?PDO $instance = null;

    public static function getConnection(): PDO
    {
        if (self::$instance === null) {
            //  Change these for InfinityFree deployment 
            $host   = getenv('DB_HOST') ?: 'localhost';
            $dbName = getenv('DB_NAME') ?: 'beed_portal';
            $user   = getenv('DB_USER') ?: 'root';
            $pass   = getenv('DB_PASS') ?: '';
            // 

            $dsn = "mysql:host={$host};dbname={$dbName};charset=utf8mb4";

            $options = [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ];

            try {
                self::$instance = new PDO($dsn, $user, $pass, $options);
            } catch (PDOException $e) {
                error_log('Database connection failed: ' . $e->getMessage());
                throw new RuntimeException('Could not connect to the database. Please try again later.');
            }
        }

        return self::$instance;
    }

    public static function reset(): void
    {
        self::$instance = null;
    }

    private function __construct() {}
    private function __clone() {}
}