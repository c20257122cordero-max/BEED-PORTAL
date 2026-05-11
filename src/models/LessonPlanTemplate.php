<?php
declare(strict_types=1);

/**
 * LessonPlanTemplate model.
 * Handles CRUD for the lesson_plan_templates table.
 */
class LessonPlanTemplate
{
    private const COLUMNS = 'id, student_id, name, description,
        subject_tpl, grade_level_tpl, time_allotment_tpl, learning_competency_tpl,
        subject_matter_topic_tpl, subject_matter_references_tpl, subject_matter_materials_tpl,
        objectives_tpl,
        proc_review_drill_tpl, proc_motivation_tpl, proc_presentation_tpl,
        proc_discussion_tpl, proc_generalization_tpl, proc_application_tpl,
        evaluation_tpl, assignment_tpl,
        created_at, updated_at';

    public static function findByStudent(PDO $db, int $studentId): array
    {
        $stmt = $db->prepare(
            'SELECT ' . self::COLUMNS . '
             FROM lesson_plan_templates
             WHERE student_id = :student_id
             ORDER BY updated_at DESC'
        );
        $stmt->execute([':student_id' => $studentId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function findById(PDO $db, int $id, int $studentId): ?array
    {
        $stmt = $db->prepare(
            'SELECT ' . self::COLUMNS . '
             FROM lesson_plan_templates
             WHERE id = :id AND student_id = :student_id
             LIMIT 1'
        );
        $stmt->execute([':id' => $id, ':student_id' => $studentId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row !== false ? $row : null;
    }

    public static function create(PDO $db, int $studentId, array $data): int
    {
        $stmt = $db->prepare(
            'INSERT INTO lesson_plan_templates
                (student_id, name, description,
                 subject_tpl, grade_level_tpl, time_allotment_tpl, learning_competency_tpl,
                 subject_matter_topic_tpl, subject_matter_references_tpl, subject_matter_materials_tpl,
                 objectives_tpl,
                 proc_review_drill_tpl, proc_motivation_tpl, proc_presentation_tpl,
                 proc_discussion_tpl, proc_generalization_tpl, proc_application_tpl,
                 evaluation_tpl, assignment_tpl)
             VALUES
                (:student_id, :name, :description,
                 :subject_tpl, :grade_level_tpl, :time_allotment_tpl, :learning_competency_tpl,
                 :subject_matter_topic_tpl, :subject_matter_references_tpl, :subject_matter_materials_tpl,
                 :objectives_tpl,
                 :proc_review_drill_tpl, :proc_motivation_tpl, :proc_presentation_tpl,
                 :proc_discussion_tpl, :proc_generalization_tpl, :proc_application_tpl,
                 :evaluation_tpl, :assignment_tpl)'
        );
        $stmt->execute([
            ':student_id'                      => $studentId,
            ':name'                            => $data['name']                            ?? '',
            ':description'                     => $data['description']                     ?? null,
            ':subject_tpl'                     => $data['subject_tpl']                     ?? null,
            ':grade_level_tpl'                 => $data['grade_level_tpl']                 ?? null,
            ':time_allotment_tpl'              => isset($data['time_allotment_tpl']) && $data['time_allotment_tpl'] !== '' ? (int)$data['time_allotment_tpl'] : null,
            ':learning_competency_tpl'         => $data['learning_competency_tpl']         ?? null,
            ':subject_matter_topic_tpl'        => $data['subject_matter_topic_tpl']        ?? null,
            ':subject_matter_references_tpl'   => $data['subject_matter_references_tpl']   ?? null,
            ':subject_matter_materials_tpl'    => $data['subject_matter_materials_tpl']    ?? null,
            ':objectives_tpl'                  => $data['objectives_tpl']                  ?? null,
            ':proc_review_drill_tpl'           => $data['proc_review_drill_tpl']           ?? null,
            ':proc_motivation_tpl'             => $data['proc_motivation_tpl']             ?? null,
            ':proc_presentation_tpl'           => $data['proc_presentation_tpl']           ?? null,
            ':proc_discussion_tpl'             => $data['proc_discussion_tpl']             ?? null,
            ':proc_generalization_tpl'         => $data['proc_generalization_tpl']         ?? null,
            ':proc_application_tpl'            => $data['proc_application_tpl']            ?? null,
            ':evaluation_tpl'                  => $data['evaluation_tpl']                  ?? null,
            ':assignment_tpl'                  => $data['assignment_tpl']                  ?? null,
        ]);
        return (int) $db->lastInsertId();
    }

    public static function update(PDO $db, int $id, int $studentId, array $data): bool
    {
        $stmt = $db->prepare(
            'UPDATE lesson_plan_templates SET
                name = :name, description = :description,
                subject_tpl = :subject_tpl, grade_level_tpl = :grade_level_tpl,
                time_allotment_tpl = :time_allotment_tpl,
                learning_competency_tpl = :learning_competency_tpl,
                subject_matter_topic_tpl = :subject_matter_topic_tpl,
                subject_matter_references_tpl = :subject_matter_references_tpl,
                subject_matter_materials_tpl = :subject_matter_materials_tpl,
                objectives_tpl = :objectives_tpl,
                proc_review_drill_tpl = :proc_review_drill_tpl,
                proc_motivation_tpl = :proc_motivation_tpl,
                proc_presentation_tpl = :proc_presentation_tpl,
                proc_discussion_tpl = :proc_discussion_tpl,
                proc_generalization_tpl = :proc_generalization_tpl,
                proc_application_tpl = :proc_application_tpl,
                evaluation_tpl = :evaluation_tpl,
                assignment_tpl = :assignment_tpl
             WHERE id = :id AND student_id = :student_id'
        );
        $stmt->execute([
            ':id'                              => $id,
            ':student_id'                      => $studentId,
            ':name'                            => $data['name']                            ?? '',
            ':description'                     => $data['description']                     ?? null,
            ':subject_tpl'                     => $data['subject_tpl']                     ?? null,
            ':grade_level_tpl'                 => $data['grade_level_tpl']                 ?? null,
            ':time_allotment_tpl'              => isset($data['time_allotment_tpl']) && $data['time_allotment_tpl'] !== '' ? (int)$data['time_allotment_tpl'] : null,
            ':learning_competency_tpl'         => $data['learning_competency_tpl']         ?? null,
            ':subject_matter_topic_tpl'        => $data['subject_matter_topic_tpl']        ?? null,
            ':subject_matter_references_tpl'   => $data['subject_matter_references_tpl']   ?? null,
            ':subject_matter_materials_tpl'    => $data['subject_matter_materials_tpl']    ?? null,
            ':objectives_tpl'                  => $data['objectives_tpl']                  ?? null,
            ':proc_review_drill_tpl'           => $data['proc_review_drill_tpl']           ?? null,
            ':proc_motivation_tpl'             => $data['proc_motivation_tpl']             ?? null,
            ':proc_presentation_tpl'           => $data['proc_presentation_tpl']           ?? null,
            ':proc_discussion_tpl'             => $data['proc_discussion_tpl']             ?? null,
            ':proc_generalization_tpl'         => $data['proc_generalization_tpl']         ?? null,
            ':proc_application_tpl'            => $data['proc_application_tpl']            ?? null,
            ':evaluation_tpl'                  => $data['evaluation_tpl']                  ?? null,
            ':assignment_tpl'                  => $data['assignment_tpl']                  ?? null,
        ]);
        return $stmt->rowCount() > 0;
    }

    public static function delete(PDO $db, int $id, int $studentId): bool
    {
        $stmt = $db->prepare(
            'DELETE FROM lesson_plan_templates WHERE id = :id AND student_id = :student_id'
        );
        $stmt->execute([':id' => $id, ':student_id' => $studentId]);
        return $stmt->rowCount() > 0;
    }
}
