<?php
declare(strict_types=1);

/**
 * Demo model.
 *
 * Handles all database operations for the `demos` table using PDO prepared
 * statements — never string-interpolated SQL (Requirements 7.3, 7.4).
 *
 * Every query that reads or mutates a demo record includes a `student_id`
 * condition to enforce per-student data isolation (Requirements 3.2, 4.1).
 */
class Demo
{
    /**
     * Columns selected in list/detail queries.
     */
    private const COLUMNS = 'id, student_id, title, subject, grade_level,
        quarter, week, status,
        duration_minutes, learning_objectives, materials_needed,
        introduction, generalization, application, assessment,
        created_at, updated_at';

    /**
     * Return all demos belonging to a student, optionally filtered by a
     * search term and/or status.
     *
     * @param PDO    $db        PDO connection (ERRMODE_EXCEPTION, FETCH_ASSOC).
     * @param int    $studentId The authenticated student's primary key.
     * @param string $search    Optional search term; empty string returns all.
     * @param string $status    Optional status filter (draft|for_review|submitted).
     *
     * @return array Array of associative-array rows; empty array if none found.
     */
    public static function findByStudent(
        PDO $db,
        int $studentId,
        string $search = '',
        string $status = ''
    ): array {
        $statusClause = $status !== '' ? ' AND status = :status' : '';

        if ($search === '') {
            $sql  = 'SELECT ' . self::COLUMNS . '
                     FROM   demos
                     WHERE  student_id = :student_id'
                     . $statusClause . '
                     ORDER  BY updated_at DESC';
            $stmt = $db->prepare($sql);
            $params = [':student_id' => $studentId];
            if ($status !== '') {
                $params[':status'] = $status;
            }
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        // --- FULLTEXT attempt ---
        try {
            $sql  = 'SELECT ' . self::COLUMNS . '
                     FROM   demos
                     WHERE  student_id = :student_id
                       AND  MATCH(title, subject) AGAINST(:search IN BOOLEAN MODE)'
                     . $statusClause . '
                     ORDER  BY updated_at DESC';
            $stmt = $db->prepare($sql);
            $params = [
                ':student_id' => $studentId,
                ':search'     => $search,
            ];
            if ($status !== '') {
                $params[':status'] = $status;
            }
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // FULLTEXT not available — fall through to LIKE fallback.
        }

        // --- LIKE fallback ---
        $like = '%' . $search . '%';
        $sql  = 'SELECT ' . self::COLUMNS . '
                 FROM   demos
                 WHERE  student_id = :student_id
                   AND  (title LIKE :search OR subject LIKE :search)'
                 . $statusClause . '
                 ORDER  BY updated_at DESC';
        $stmt = $db->prepare($sql);
        $params = [
            ':student_id' => $studentId,
            ':search'     => $like,
        ];
        if ($status !== '') {
            $params[':status'] = $status;
        }
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Return a single demo row by its primary key, scoped to the given student.
     *
     * Returns null when no matching record exists or when the demo belongs to
     * a different student — the controller treats null as a 404 (Property 8).
     *
     * @param PDO $db        PDO connection (ERRMODE_EXCEPTION, FETCH_ASSOC).
     * @param int $id        The demo's primary key.
     * @param int $studentId The authenticated student's primary key.
     *
     * @return array|null Associative array of the demo row, or null.
     */
    public static function findById(PDO $db, int $id, int $studentId): ?array
    {
        $sql  = 'SELECT ' . self::COLUMNS . '
                 FROM   demos
                 WHERE  id = :id
                   AND  student_id = :student_id
                 LIMIT  1';
        $stmt = $db->prepare($sql);
        $stmt->execute([
            ':id'         => $id,
            ':student_id' => $studentId,
        ]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row !== false ? $row : null;
    }

    /**
     * Insert a new demo record for the given student.
     *
     * Returns the auto-generated primary key of the new demo row.
     *
     * @param PDO   $db        PDO connection (ERRMODE_EXCEPTION, FETCH_ASSOC).
     * @param int   $studentId The authenticated student's primary key.
     * @param array $data      Associative array of field values from the form.
     *
     * @return int The new demo's auto-incremented ID.
     */
    public static function create(PDO $db, int $studentId, array $data): int
    {
        $sql = 'INSERT INTO demos
                    (student_id, title, subject, grade_level, quarter, week, status,
                     duration_minutes, learning_objectives, materials_needed,
                     introduction, generalization, application, assessment)
                VALUES
                    (:student_id, :title, :subject, :grade_level, :quarter, :week, :status,
                     :duration_minutes, :learning_objectives, :materials_needed,
                     :introduction, :generalization, :application, :assessment)';

        $stmt = $db->prepare($sql);
        $stmt->execute([
            ':student_id'          => $studentId,
            ':title'               => $data['title']               ?? null,
            ':subject'             => $data['subject']             ?? null,
            ':grade_level'         => $data['grade_level']         ?? null,
            ':quarter'             => isset($data['quarter']) && $data['quarter'] !== ''
                                          ? (int) $data['quarter']
                                          : null,
            ':week'                => isset($data['week']) && $data['week'] !== ''
                                          ? (int) $data['week']
                                          : null,
            ':status'              => $data['status']              ?? 'draft',
            ':duration_minutes'    => isset($data['duration_minutes']) && $data['duration_minutes'] !== ''
                                          ? (int) $data['duration_minutes']
                                          : null,
            ':learning_objectives' => $data['learning_objectives'] ?? null,
            ':materials_needed'    => $data['materials_needed']    ?? null,
            ':introduction'        => $data['introduction']        ?? null,
            ':generalization'      => $data['generalization']      ?? null,
            ':application'         => $data['application']         ?? null,
            ':assessment'          => $data['assessment']          ?? null,
        ]);

        return (int) $db->lastInsertId();
    }

    /**
     * Update an existing demo record for the given student.
     *
     * Returns true on success, false if no matching record was found or the
     * demo belongs to a different student.
     *
     * @param PDO   $db        PDO connection (ERRMODE_EXCEPTION, FETCH_ASSOC).
     * @param int   $id        The demo's primary key.
     * @param int   $studentId The authenticated student's primary key.
     * @param array $data      Associative array of updated field values.
     *
     * @return bool True if the record was updated; false otherwise.
     */
    public static function update(PDO $db, int $id, int $studentId, array $data): bool
    {
        $sql = 'UPDATE demos
                SET    title               = :title,
                       subject             = :subject,
                       grade_level         = :grade_level,
                       quarter             = :quarter,
                       week                = :week,
                       status              = :status,
                       duration_minutes    = :duration_minutes,
                       learning_objectives = :learning_objectives,
                       materials_needed    = :materials_needed,
                       introduction        = :introduction,
                       generalization      = :generalization,
                       application         = :application,
                       assessment          = :assessment
                WHERE  id         = :id
                  AND  student_id = :student_id';

        $stmt = $db->prepare($sql);
        $stmt->execute([
            ':id'                  => $id,
            ':student_id'          => $studentId,
            ':title'               => $data['title']               ?? null,
            ':subject'             => $data['subject']             ?? null,
            ':grade_level'         => $data['grade_level']         ?? null,
            ':quarter'             => isset($data['quarter']) && $data['quarter'] !== ''
                                          ? (int) $data['quarter']
                                          : null,
            ':week'                => isset($data['week']) && $data['week'] !== ''
                                          ? (int) $data['week']
                                          : null,
            ':status'              => $data['status']              ?? 'draft',
            ':duration_minutes'    => isset($data['duration_minutes']) && $data['duration_minutes'] !== ''
                                          ? (int) $data['duration_minutes']
                                          : null,
            ':learning_objectives' => $data['learning_objectives'] ?? null,
            ':materials_needed'    => $data['materials_needed']    ?? null,
            ':introduction'        => $data['introduction']        ?? null,
            ':generalization'      => $data['generalization']      ?? null,
            ':application'         => $data['application']         ?? null,
            ':assessment'          => $data['assessment']          ?? null,
        ]);

        return $stmt->rowCount() > 0;
    }

    /**
     * Delete a demo record (and its steps via CASCADE) for the given student.
     *
     * @param PDO $db        PDO connection (ERRMODE_EXCEPTION, FETCH_ASSOC).
     * @param int $id        The demo's primary key.
     * @param int $studentId The authenticated student's primary key.
     *
     * @return bool True if the record was deleted; false otherwise.
     */
    public static function delete(PDO $db, int $id, int $studentId): bool
    {
        $sql  = 'DELETE FROM demos
                 WHERE  id         = :id
                   AND  student_id = :student_id';
        $stmt = $db->prepare($sql);
        $stmt->execute([
            ':id'         => $id,
            ':student_id' => $studentId,
        ]);
        return $stmt->rowCount() > 0;
    }

    /**
     * Return the N most recently modified demos for a student.
     *
     * @param PDO $db        PDO connection (ERRMODE_EXCEPTION, FETCH_ASSOC).
     * @param int $studentId The authenticated student's primary key.
     * @param int $limit     Maximum number of rows to return (default 5).
     *
     * @return array Array of associative-array rows; empty array if none found.
     */
    public static function recentByStudent(PDO $db, int $studentId, int $limit = 5): array
    {
        $sql  = 'SELECT ' . self::COLUMNS . '
                 FROM   demos
                 WHERE  student_id = :student_id
                 ORDER  BY updated_at DESC
                 LIMIT  :limit';
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':student_id', $studentId, PDO::PARAM_INT);
        $stmt->bindValue(':limit',      $limit,     PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
