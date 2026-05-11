<?php

// Feature: beed-student-portal, Task 2.2: DB schema verification

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

/**
 * Integration test for DB schema verification.
 *
 * Validates: Requirements 7.1
 *
 * Verifies that running sql/schema.sql against the test database creates all
 * five required tables (students, demos, demo_steps, lesson_plans,
 * lesson_objectives) with the correct columns and foreign key constraints.
 *
 * Database credentials are read from environment variables:
 *   TEST_DB_HOST  — defaults to "localhost"
 *   TEST_DB_NAME  — defaults to "beed_portal_test"
 *   TEST_DB_USER  — defaults to "root"
 *   TEST_DB_PASS  — defaults to ""
 */
class SchemaVerificationTest extends TestCase
{
    private PDO $db;

    // -------------------------------------------------------------------------
    // Setup
    // -------------------------------------------------------------------------

    /**
     * Connect to the test database and ensure the schema is in place.
     *
     * If the connection fails (e.g. no test DB available) the test is marked
     * as skipped rather than failed, so CI pipelines without a DB do not
     * produce false negatives.
     */
    protected function setUp(): void
    {
        $host   = getenv('TEST_DB_HOST') ?: 'localhost';
        $dbName = getenv('TEST_DB_NAME') ?: 'beed_portal_test';
        $user   = getenv('TEST_DB_USER') ?: 'root';
        $pass   = getenv('TEST_DB_PASS') ?: '';

        $dsn = "mysql:host={$host};dbname={$dbName};charset=utf8mb4";

        try {
            $this->db = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
        } catch (PDOException $e) {
            $this->markTestSkipped(
                'Test database not available: ' . $e->getMessage()
            );
        }

        $this->ensureSchema();
    }

    // -------------------------------------------------------------------------
    // Helper: schema bootstrap
    // -------------------------------------------------------------------------

    /**
     * Run the project's schema.sql against the test database so that all
     * tables and foreign key constraints exist before the test runs.
     *
     * The schema uses IF NOT EXISTS guards, so re-running it is safe.
     */
    private function ensureSchema(): void
    {
        $schemaPath = __DIR__ . '/../../sql/schema.sql';

        if (!file_exists($schemaPath)) {
            $this->markTestSkipped('sql/schema.sql not found; cannot bootstrap test schema.');
        }

        $sql = file_get_contents($schemaPath);

        // Split on semicolons to execute each statement individually via PDO.
        // Strip the CREATE DATABASE / USE statements — the test DB already exists.
        $statements = array_filter(
            array_map('trim', explode(';', $sql)),
            static function (string $stmt): bool {
                if ($stmt === '') {
                    return false;
                }
                // Skip CREATE DATABASE and USE statements.
                if (preg_match('/^\s*(CREATE\s+DATABASE|USE\s+)/i', $stmt)) {
                    return false;
                }
                return true;
            }
        );

        foreach ($statements as $statement) {
            try {
                $this->db->exec($statement);
            } catch (PDOException $e) {
                // Ignore "Duplicate key name" errors from ALTER TABLE ADD INDEX
                // when the index already exists (error code 1061).
                if ($e->getCode() !== '42000' || strpos($e->getMessage(), '1061') === false) {
                    throw $e;
                }
            }
        }
    }

    // -------------------------------------------------------------------------
    // Helper: column lookup
    // -------------------------------------------------------------------------

    /**
     * Return the set of column names for the given table.
     *
     * @return string[]
     */
    private function getTableColumns(string $table): array
    {
        $stmt = $this->db->query("SHOW COLUMNS FROM `{$table}`");
        $rows = $stmt->fetchAll();
        return array_column($rows, 'Field');
    }

    // -------------------------------------------------------------------------
    // Tests: table existence
    // -------------------------------------------------------------------------

    /**
     * All five required tables must exist in the test database after running
     * schema.sql.
     *
     * Validates: Requirements 7.1
     */
    public function testAllTablesExist(): void
    {
        $expectedTables = [
            'students',
            'demos',
            'demo_steps',
            'lesson_plans',
            'lesson_objectives',
        ];

        $dbName = getenv('TEST_DB_NAME') ?: 'beed_portal_test';

        foreach ($expectedTables as $table) {
            $stmt = $this->db->prepare(
                "SELECT TABLE_NAME
                 FROM INFORMATION_SCHEMA.TABLES
                 WHERE TABLE_SCHEMA = :db_name
                   AND TABLE_NAME   = :table_name"
            );
            $stmt->execute([':db_name' => $dbName, ':table_name' => $table]);
            $result = $stmt->fetchColumn();

            $this->assertNotFalse(
                $result,
                "Table '{$table}' should exist in the database"
            );
            $this->assertSame(
                $table,
                $result,
                "Table '{$table}' should exist in the database"
            );
        }
    }

    // -------------------------------------------------------------------------
    // Tests: column verification
    // -------------------------------------------------------------------------

    /**
     * The students table must have the required columns.
     *
     * Validates: Requirements 7.1
     */
    public function testStudentsTableColumns(): void
    {
        $columns = $this->getTableColumns('students');

        $requiredColumns = ['id', 'full_name', 'email', 'password_hash', 'created_at'];

        foreach ($requiredColumns as $column) {
            $this->assertContains(
                $column,
                $columns,
                "students table should have column '{$column}'"
            );
        }
    }

    /**
     * The demos table must have the required columns, including the student_id
     * foreign key column.
     *
     * Validates: Requirements 7.1
     */
    public function testDemosTableColumns(): void
    {
        $columns = $this->getTableColumns('demos');

        $requiredColumns = [
            'id',
            'student_id',
            'title',
            'subject',
            'grade_level',
            'duration_minutes',
            'learning_objectives',
            'materials_needed',
            'introduction',
            'generalization',
            'application',
            'assessment',
            'created_at',
            'updated_at',
        ];

        foreach ($requiredColumns as $column) {
            $this->assertContains(
                $column,
                $columns,
                "demos table should have column '{$column}'"
            );
        }
    }

    /**
     * The demo_steps table must have the required columns.
     *
     * Validates: Requirements 7.1
     */
    public function testDemoStepsTableColumns(): void
    {
        $columns = $this->getTableColumns('demo_steps');

        $requiredColumns = ['id', 'demo_id', 'step_number', 'description'];

        foreach ($requiredColumns as $column) {
            $this->assertContains(
                $column,
                $columns,
                "demo_steps table should have column '{$column}'"
            );
        }
    }

    /**
     * The lesson_plans table must have the required columns, including the
     * student_id foreign key column.
     *
     * Validates: Requirements 7.1
     */
    public function testLessonPlansTableColumns(): void
    {
        $columns = $this->getTableColumns('lesson_plans');

        $requiredColumns = [
            'id',
            'student_id',
            'title',
            'subject',
            'grade_level',
            'date',
            'time_allotment_minutes',
            'learning_competency',
            'created_at',
            'updated_at',
        ];

        foreach ($requiredColumns as $column) {
            $this->assertContains(
                $column,
                $columns,
                "lesson_plans table should have column '{$column}'"
            );
        }
    }

    /**
     * The lesson_objectives table must have the required columns.
     *
     * Validates: Requirements 7.1
     */
    public function testLessonObjectivesTableColumns(): void
    {
        $columns = $this->getTableColumns('lesson_objectives');

        $requiredColumns = ['id', 'lesson_plan_id', 'objective_text', 'sort_order'];

        foreach ($requiredColumns as $column) {
            $this->assertContains(
                $column,
                $columns,
                "lesson_objectives table should have column '{$column}'"
            );
        }
    }

    // -------------------------------------------------------------------------
    // Tests: foreign key constraints
    // -------------------------------------------------------------------------

    /**
     * All four foreign key constraints must exist in the schema:
     *   - demos.student_id → students.id
     *   - demo_steps.demo_id → demos.id
     *   - lesson_plans.student_id → students.id
     *   - lesson_objectives.lesson_plan_id → lesson_plans.id
     *
     * Validates: Requirements 7.1
     */
    public function testForeignKeyConstraintsExist(): void
    {
        $dbName = getenv('TEST_DB_NAME') ?: 'beed_portal_test';

        $stmt = $this->db->prepare(
            "SELECT
                TABLE_NAME,
                COLUMN_NAME,
                REFERENCED_TABLE_NAME,
                REFERENCED_COLUMN_NAME
             FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
             WHERE TABLE_SCHEMA = :db_name
               AND REFERENCED_TABLE_NAME IS NOT NULL"
        );
        $stmt->execute([':db_name' => $dbName]);
        $rows = $stmt->fetchAll();

        // Build a lookup set of "table.column→ref_table.ref_column" strings.
        $fkSet = [];
        foreach ($rows as $row) {
            $key = sprintf(
                '%s.%s→%s.%s',
                $row['TABLE_NAME'],
                $row['COLUMN_NAME'],
                $row['REFERENCED_TABLE_NAME'],
                $row['REFERENCED_COLUMN_NAME']
            );
            $fkSet[] = $key;
        }

        $expectedForeignKeys = [
            'demos.student_id→students.id',
            'demo_steps.demo_id→demos.id',
            'lesson_plans.student_id→students.id',
            'lesson_objectives.lesson_plan_id→lesson_plans.id',
        ];

        foreach ($expectedForeignKeys as $fk) {
            $this->assertContains(
                $fk,
                $fkSet,
                "Foreign key constraint '{$fk}' should exist in the schema"
            );
        }
    }
}
