<?php

/**
 * LessonObjective model.
 *
 * Handles all database operations for the `lesson_objectives` table using PDO
 * prepared statements — never string-interpolated SQL (Requirements 7.3, 7.4).
 *
 * Objectives are always retrieved ordered by `sort_order` ASC so that the
 * learning objectives section renders in the correct sequence (Requirement 5.7).
 */
class LessonObjective
{
    /**
     * Return all objectives for a given lesson plan, ordered by sort_order ascending.
     *
     * @param PDO $db           PDO connection (ERRMODE_EXCEPTION, FETCH_ASSOC).
     * @param int $lessonPlanId The lesson plan's primary key.
     *
     * @return array Array of associative-array rows (id, lesson_plan_id,
     *               objective_text, sort_order); empty array if the lesson
     *               plan has no objectives.
     */
    public static function findByLessonPlan(PDO $db, int $lessonPlanId): array
    {
        $sql  = 'SELECT id, lesson_plan_id, objective_text, sort_order
                 FROM   lesson_objectives
                 WHERE  lesson_plan_id = :lesson_plan_id
                 ORDER  BY sort_order ASC';
        $stmt = $db->prepare($sql);
        $stmt->execute([':lesson_plan_id' => $lessonPlanId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Replace all objectives for a lesson plan with a new set of objectives.
     *
     * Runs inside a transaction:
     *   1. DELETE all existing rows for $lessonPlanId.
     *   2. INSERT each entry from $objectives (if any).
     *   3. COMMIT on success; ROLLBACK on any exception.
     *
     * Each element of $objectives must be an associative array with:
     *   - 'sort_order'      (int)    — display/sort order
     *   - 'objective_text'  (string) — objective text
     *
     * If $objectives is empty, the method simply deletes all existing objectives
     * and commits — no INSERT statements are executed.
     *
     * @param PDO   $db           PDO connection (ERRMODE_EXCEPTION, FETCH_ASSOC).
     * @param int   $lessonPlanId The lesson plan's primary key.
     * @param array $objectives   Array of objective associative arrays to insert.
     *
     * @return void
     *
     * @throws PDOException Re-thrown after rolling back the transaction if a
     *                      DB error occurs during the operation.
     */
    public static function replaceForLessonPlan(PDO $db, int $lessonPlanId, array $objectives): void
    {
        $db->beginTransaction();

        try {
            // 1. Remove all existing objectives for this lesson plan.
            $deleteStmt = $db->prepare(
                'DELETE FROM lesson_objectives WHERE lesson_plan_id = :lesson_plan_id'
            );
            $deleteStmt->execute([':lesson_plan_id' => $lessonPlanId]);

            // 2. Insert the new objectives (if any).
            if (!empty($objectives)) {
                $insertStmt = $db->prepare(
                    'INSERT INTO lesson_objectives (lesson_plan_id, objective_text, sort_order)
                     VALUES (:lesson_plan_id, :objective_text, :sort_order)'
                );

                foreach ($objectives as $objective) {
                    $insertStmt->execute([
                        ':lesson_plan_id' => $lessonPlanId,
                        ':objective_text' => $objective['objective_text'],
                        ':sort_order'     => (int) $objective['sort_order'],
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
