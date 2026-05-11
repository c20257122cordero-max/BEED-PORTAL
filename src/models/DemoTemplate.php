<?php
declare(strict_types=1);

class DemoTemplate
{
    private const COLS = 'id, student_id, name, description,
        subject_tpl, grade_level_tpl, duration_minutes_tpl,
        learning_objectives_tpl, materials_needed_tpl,
        introduction_tpl, generalization_tpl, application_tpl, assessment_tpl,
        steps_tpl, created_at, updated_at';

    public static function findByStudent(PDO $db, int $studentId): array
    {
        $stmt = $db->prepare('SELECT ' . self::COLS . ' FROM demo_templates WHERE student_id = :sid ORDER BY updated_at DESC');
        $stmt->execute([':sid' => $studentId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function findById(PDO $db, int $id, int $studentId): ?array
    {
        $stmt = $db->prepare('SELECT ' . self::COLS . ' FROM demo_templates WHERE id = :id AND student_id = :sid LIMIT 1');
        $stmt->execute([':id' => $id, ':sid' => $studentId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row !== false ? $row : null;
    }

    public static function create(PDO $db, int $studentId, array $d): int
    {
        $stmt = $db->prepare('INSERT INTO demo_templates
            (student_id, name, description, subject_tpl, grade_level_tpl, duration_minutes_tpl,
             learning_objectives_tpl, materials_needed_tpl, introduction_tpl,
             generalization_tpl, application_tpl, assessment_tpl, steps_tpl)
            VALUES (:sid,:name,:desc,:subj,:grade,:dur,:obj,:mat,:intro,:gen,:app,:ass,:steps)');
        $stmt->execute([
            ':sid'   => $studentId,
            ':name'  => $d['name']  ?? '',
            ':desc'  => $d['description'] ?? null,
            ':subj'  => $d['subject_tpl'] ?? null,
            ':grade' => $d['grade_level_tpl'] ?? null,
            ':dur'   => isset($d['duration_minutes_tpl']) && $d['duration_minutes_tpl'] !== '' ? (int)$d['duration_minutes_tpl'] : null,
            ':obj'   => $d['learning_objectives_tpl'] ?? null,
            ':mat'   => $d['materials_needed_tpl'] ?? null,
            ':intro' => $d['introduction_tpl'] ?? null,
            ':gen'   => $d['generalization_tpl'] ?? null,
            ':app'   => $d['application_tpl'] ?? null,
            ':ass'   => $d['assessment_tpl'] ?? null,
            ':steps' => $d['steps_tpl'] ?? null,
        ]);
        return (int) $db->lastInsertId();
    }

    public static function update(PDO $db, int $id, int $studentId, array $d): bool
    {
        $stmt = $db->prepare('UPDATE demo_templates SET
            name=:name, description=:desc, subject_tpl=:subj, grade_level_tpl=:grade,
            duration_minutes_tpl=:dur, learning_objectives_tpl=:obj, materials_needed_tpl=:mat,
            introduction_tpl=:intro, generalization_tpl=:gen, application_tpl=:app,
            assessment_tpl=:ass, steps_tpl=:steps
            WHERE id=:id AND student_id=:sid');
        $stmt->execute([
            ':id'    => $id,
            ':sid'   => $studentId,
            ':name'  => $d['name']  ?? '',
            ':desc'  => $d['description'] ?? null,
            ':subj'  => $d['subject_tpl'] ?? null,
            ':grade' => $d['grade_level_tpl'] ?? null,
            ':dur'   => isset($d['duration_minutes_tpl']) && $d['duration_minutes_tpl'] !== '' ? (int)$d['duration_minutes_tpl'] : null,
            ':obj'   => $d['learning_objectives_tpl'] ?? null,
            ':mat'   => $d['materials_needed_tpl'] ?? null,
            ':intro' => $d['introduction_tpl'] ?? null,
            ':gen'   => $d['generalization_tpl'] ?? null,
            ':app'   => $d['application_tpl'] ?? null,
            ':ass'   => $d['assessment_tpl'] ?? null,
            ':steps' => $d['steps_tpl'] ?? null,
        ]);
        return $stmt->rowCount() > 0;
    }

    public static function delete(PDO $db, int $id, int $studentId): bool
    {
        $stmt = $db->prepare('DELETE FROM demo_templates WHERE id=:id AND student_id=:sid');
        $stmt->execute([':id' => $id, ':sid' => $studentId]);
        return $stmt->rowCount() > 0;
    }
}
