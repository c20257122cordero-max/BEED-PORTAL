<?php

// Feature: beed-student-portal, Property 12: Student account cascade delete

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

/**
 * Integration test for Property 12: Student account cascade delete.
 *
 * Validates: Requirements 7.2
 *
 * Verifies that deleting a student row from the `students` table causes
 * MySQL's ON DELETE CASCADE foreign key constraints to automatically remove
 * all associated rows from `demos`, `demo_steps`, `lesson_plans`, and
 * `lesson_objectives`.
 *
 * Uses PDO directly (not the model classes) to keep the test isolated from
 * application logic and to exercise the database constraints themselves.
 *
 * Database credentials are read from environment variables:
 *   TEST_DB_HOST  — defaults to "localhost"
 *   TEST_DB_NAME  — defaults to "beed_portal_test"
 *   TEST_DB_USER  — defaults to "root"
 *   TEST_DB_PASS  — defaults to ""
 */
class CascadeDeleteTest extends TestCase
{
    private PDO $db;

    // -------------------------------------------------------------------------
    // Setup / Teardown
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

    /**
     * Remove any rows inserted by this test to leave the DB clean.
     *
     * Because the test exercises CASCADE DELETE, the child rows should already
     * be gone after the student is deleted. This teardown is a safety net for
     * cases where the test fails before the DELETE is executed.
     */
    protected function tearDown(): void
    {
        if (!isset($this->db)) {
            return;
        }

        // Clean up any leftover test data identified by the test email domain.
        $this->db->exec(
            "DELETE FROM students WHERE email LIKE '%@cascade-delete-test.example'"
        );
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
    // Helper: data insertion
    // -------------------------------------------------------------------------

    /**
     * Insert a student row and return its auto-generated ID.
     */
    private function insertStudent(string $suffix = ''): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO students (full_name, email, password_hash)
             VALUES (:full_name, :email, :password_hash)'
        );
        $stmt->execute([
            ':full_name'     => 'Test Student' . $suffix,
            ':email'         => 'student' . $suffix . '@cascade-delete-test.example',
            ':password_hash' => password_hash('secret', PASSWORD_BCRYPT),
        ]);
        return (int) $this->db->lastInsertId();
    }

    /**
     * Insert a demo for the given student and return its ID.
     */
    private function insertDemo(int $studentId, string $title = 'Demo Title'): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO demos (student_id, title, learning_objectives)
             VALUES (:student_id, :title, :learning_objectives)'
        );
        $stmt->execute([
            ':student_id'          => $studentId,
            ':title'               => $title,
            ':learning_objectives' => 'Objective A',
        ]);
        return (int) $this->db->lastInsertId();
    }

    /**
     * Insert a demo step for the given demo and return its ID.
     */
    private function insertDemoStep(int $demoId, int $stepNumber = 1): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO demo_steps (demo_id, step_number, description)
             VALUES (:demo_id, :step_number, :description)'
        );
        $stmt->execute([
            ':demo_id'     => $demoId,
            ':step_number' => $stepNumber,
            ':description' => 'Step ' . $stepNumber . ' description',
        ]);
        return (int) $this->db->lastInsertId();
    }

    /**
     * Insert a lesson plan for the given student and return its ID.
     */
    private function insertLessonPlan(int $studentId, string $title = 'Lesson Plan Title'): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO lesson_plans (student_id, title, learning_competency)
             VALUES (:student_id, :title, :learning_competency)'
        );
        $stmt->execute([
            ':student_id'          => $studentId,
            ':title'               => $title,
            ':learning_competency' => 'LC-001',
        ]);
        return (int) $this->db->lastInsertId();
    }

    /**
     * Insert a lesson objective for the given lesson plan and return its ID.
     */
    private function insertLessonObjective(int $lessonPlanId, int $sortOrder = 1): int
    {
        $stmt = $this->db->prepare(
            'INSERT INTO lesson_objectives (lesson_plan_id, objective_text, sort_order)
             VALUES (:lesson_plan_id, :objective_text, :sort_order)'
        );
        $stmt->execute([
            ':lesson_plan_id' => $lessonPlanId,
            ':objective_text' => 'Objective ' . $sortOrder,
            ':sort_order'     => $sortOrder,
        ]);
        return (int) $this->db->lastInsertId();
    }

    // -------------------------------------------------------------------------
    // Helper: row-count queries
    // -------------------------------------------------------------------------

    private function countDemosByStudent(int $studentId): int
    {
        $stmt = $this->db->prepare(
            'SELECT COUNT(*) FROM demos WHERE student_id = :student_id'
        );
        $stmt->execute([':student_id' => $studentId]);
        return (int) $stmt->fetchColumn();
    }

    private function countDemoStepsByDemoIds(array $demoIds): int
    {
        if (empty($demoIds)) {
            return 0;
        }
        $placeholders = implode(',', array_fill(0, count($demoIds), '?'));
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) FROM demo_steps WHERE demo_id IN ({$placeholders})"
        );
        $stmt->execute($demoIds);
        return (int) $stmt->fetchColumn();
    }

    private function countLessonPlansByStudent(int $studentId): int
    {
        $stmt = $this->db->prepare(
            'SELECT COUNT(*) FROM lesson_plans WHERE student_id = :student_id'
        );
        $stmt->execute([':student_id' => $studentId]);
        return (int) $stmt->fetchColumn();
    }

    private function countLessonObjectivesByPlanIds(array $planIds): int
    {
        if (empty($planIds)) {
            return 0;
        }
        $placeholders = implode(',', array_fill(0, count($planIds), '?'));
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) FROM lesson_objectives WHERE lesson_plan_id IN ({$placeholders})"
        );
        $stmt->execute($planIds);
        return (int) $stmt->fetchColumn();
    }

    // -------------------------------------------------------------------------
    // Tests
    // -------------------------------------------------------------------------

    /**
     * Property 12: Student account cascade delete — core scenario.
     *
     * Insert a student with 2 demos (each with 2 steps) and 2 lesson plans
     * (each with 2 objectives). Delete the student row. Assert that all
     * associated rows in demos, demo_steps, lesson_plans, and
     * lesson_objectives are gone.
     *
     * Validates: Requirements 7.2
     */
    public function testDeletingStudentCascadesToAllChildTables(): void
    {
        // --- Arrange ---
        $studentId = $this->insertStudent('_main');

        // Two demos, each with two steps.
        $demo1Id = $this->insertDemo($studentId, 'Demo One');
        $this->insertDemoStep($demo1Id, 1);
        $this->insertDemoStep($demo1Id, 2);

        $demo2Id = $this->insertDemo($studentId, 'Demo Two');
        $this->insertDemoStep($demo2Id, 1);
        $this->insertDemoStep($demo2Id, 2);

        // Two lesson plans, each with two objectives.
        $plan1Id = $this->insertLessonPlan($studentId, 'Plan One');
        $this->insertLessonObjective($plan1Id, 1);
        $this->insertLessonObjective($plan1Id, 2);

        $plan2Id = $this->insertLessonPlan($studentId, 'Plan Two');
        $this->insertLessonObjective($plan2Id, 1);
        $this->insertLessonObjective($plan2Id, 2);

        // Confirm data is present before deletion.
        $this->assertSame(2, $this->countDemosByStudent($studentId), 'Expected 2 demos before delete');
        $this->assertSame(4, $this->countDemoStepsByDemoIds([$demo1Id, $demo2Id]), 'Expected 4 demo steps before delete');
        $this->assertSame(2, $this->countLessonPlansByStudent($studentId), 'Expected 2 lesson plans before delete');
        $this->assertSame(4, $this->countLessonObjectivesByPlanIds([$plan1Id, $plan2Id]), 'Expected 4 lesson objectives before delete');

        // --- Act ---
        $stmt = $this->db->prepare('DELETE FROM students WHERE id = :id');
        $stmt->execute([':id' => $studentId]);

        // --- Assert ---
        $this->assertSame(
            0,
            $this->countDemosByStudent($studentId),
            'demos table should have no rows for the deleted student'
        );
        $this->assertSame(
            0,
            $this->countDemoStepsByDemoIds([$demo1Id, $demo2Id]),
            'demo_steps table should have no rows for the deleted student\'s demos'
        );
        $this->assertSame(
            0,
            $this->countLessonPlansByStudent($studentId),
            'lesson_plans table should have no rows for the deleted student'
        );
        $this->assertSame(
            0,
            $this->countLessonObjectivesByPlanIds([$plan1Id, $plan2Id]),
            'lesson_objectives table should have no rows for the deleted student\'s plans'
        );
    }

    /**
     * Cascade delete does not affect rows belonging to other students.
     *
     * Insert two students with their own demos and lesson plans. Delete only
     * the first student. Assert the second student's data is untouched.
     *
     * Validates: Requirements 7.2
     */
    public function testDeletingStudentDoesNotAffectOtherStudentsData(): void
    {
        // --- Arrange ---
        $studentAId = $this->insertStudent('_a');
        $demoAId    = $this->insertDemo($studentAId, 'Student A Demo');
        $this->insertDemoStep($demoAId, 1);
        $planAId = $this->insertLessonPlan($studentAId, 'Student A Plan');
        $this->insertLessonObjective($planAId, 1);

        $studentBId = $this->insertStudent('_b');
        $demoBId    = $this->insertDemo($studentBId, 'Student B Demo');
        $this->insertDemoStep($demoBId, 1);
        $planBId = $this->insertLessonPlan($studentBId, 'Student B Plan');
        $this->insertLessonObjective($planBId, 1);

        // --- Act: delete only student A ---
        $stmt = $this->db->prepare('DELETE FROM students WHERE id = :id');
        $stmt->execute([':id' => $studentAId]);

        // --- Assert: student A's data is gone ---
        $this->assertSame(0, $this->countDemosByStudent($studentAId), 'Student A demos should be deleted');
        $this->assertSame(0, $this->countDemoStepsByDemoIds([$demoAId]), 'Student A demo steps should be deleted');
        $this->assertSame(0, $this->countLessonPlansByStudent($studentAId), 'Student A lesson plans should be deleted');
        $this->assertSame(0, $this->countLessonObjectivesByPlanIds([$planAId]), 'Student A lesson objectives should be deleted');

        // --- Assert: student B's data is intact ---
        $this->assertSame(1, $this->countDemosByStudent($studentBId), 'Student B demo should still exist');
        $this->assertSame(1, $this->countDemoStepsByDemoIds([$demoBId]), 'Student B demo step should still exist');
        $this->assertSame(1, $this->countLessonPlansByStudent($studentBId), 'Student B lesson plan should still exist');
        $this->assertSame(1, $this->countLessonObjectivesByPlanIds([$planBId]), 'Student B lesson objective should still exist');

        // Clean up student B (tearDown only removes by email pattern).
        $this->db->prepare('DELETE FROM students WHERE id = :id')->execute([':id' => $studentBId]);
    }

    /**
     * Cascade delete works correctly for a student with no child records.
     *
     * Deleting a student who has no demos or lesson plans should succeed
     * without error and leave all tables empty for that student_id.
     *
     * Validates: Requirements 7.2
     */
    public function testDeletingStudentWithNoChildRecordsSucceeds(): void
    {
        // --- Arrange ---
        $studentId = $this->insertStudent('_empty');

        // --- Act ---
        $stmt = $this->db->prepare('DELETE FROM students WHERE id = :id');
        $stmt->execute([':id' => $studentId]);

        // --- Assert ---
        $this->assertSame(0, $this->countDemosByStudent($studentId));
        $this->assertSame(0, $this->countLessonPlansByStudent($studentId));
    }
}
