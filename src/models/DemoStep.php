<?php

/**
 * DemoStep model.
 *
 * Handles all database operations for the `demo_steps` table using PDO
 * prepared statements — never string-interpolated SQL (Requirements 7.3, 7.4).
 *
 * Steps are always retrieved ordered by `step_number` ASC so that the
 * Lesson Proper section renders in the correct sequence (Requirements 3.7, 3.8).
 */
class DemoStep
{
    /**
     * Return all steps for a given demo, ordered by step_number ascending.
     *
     * @param PDO $db     PDO connection (ERRMODE_EXCEPTION, FETCH_ASSOC).
     * @param int $demoId The demo's primary key.
     *
     * @return array Array of associative-array rows (id, demo_id, step_number,
     *               description); empty array if the demo has no steps.
     */
    public static function findByDemo(PDO $db, int $demoId): array
    {
        $sql  = 'SELECT id, demo_id, step_number, description
                 FROM   demo_steps
                 WHERE  demo_id = :demo_id
                 ORDER  BY step_number ASC';
        $stmt = $db->prepare($sql);
        $stmt->execute([':demo_id' => $demoId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Replace all steps for a demo with a new set of steps.
     *
     * Runs inside a transaction:
     *   1. DELETE all existing rows for $demoId.
     *   2. INSERT each entry from $steps (if any).
     *   3. COMMIT on success; ROLLBACK on any exception.
     *
     * Each element of $steps must be an associative array with:
     *   - 'step_number'  (int)    — display/sort order
     *   - 'description'  (string) — step text
     *
     * If $steps is empty, the method simply deletes all existing steps and
     * commits — no INSERT statements are executed.
     *
     * @param PDO   $db     PDO connection (ERRMODE_EXCEPTION, FETCH_ASSOC).
     * @param int   $demoId The demo's primary key.
     * @param array $steps  Array of step associative arrays to insert.
     *
     * @return void
     *
     * @throws PDOException Re-thrown after rolling back the transaction if a
     *                      DB error occurs during the operation.
     */
    public static function replaceForDemo(PDO $db, int $demoId, array $steps): void
    {
        $db->beginTransaction();

        try {
            // 1. Remove all existing steps for this demo.
            $deleteStmt = $db->prepare(
                'DELETE FROM demo_steps WHERE demo_id = :demo_id'
            );
            $deleteStmt->execute([':demo_id' => $demoId]);

            // 2. Insert the new steps (if any).
            if (!empty($steps)) {
                $insertStmt = $db->prepare(
                    'INSERT INTO demo_steps (demo_id, step_number, description)
                     VALUES (:demo_id, :step_number, :description)'
                );

                foreach ($steps as $step) {
                    $insertStmt->execute([
                        ':demo_id'     => $demoId,
                        ':step_number' => (int) $step['step_number'],
                        ':description' => $step['description'],
                    ]);
                }
            }

            $db->commit();
        } catch (PDOException $e) {
            $db->rollBack();
            throw $e;
        }
    }
}
