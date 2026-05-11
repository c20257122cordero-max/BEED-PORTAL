<?php
declare(strict_types=1);

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/LessonPlan.php';
require_once __DIR__ . '/../models/LessonObjective.php';
require_once __DIR__ . '/../models/Student.php';
require_once __DIR__ . '/../middleware/AuthMiddleware.php';

/**
 * LessonPlanController
 *
 * Handles all Lesson Plan Planner CRUD operations, the printable export view,
 * and the duplicate action.
 *
 * Requirements: 5.1, 5.2, 5.3, 5.4, 5.5, 5.6, 5.7, 6.1, 6.2, 6.3, 6.4, 6.5
 */
class LessonPlanController
{
    // ── Helpers ───────────────────────────────────────────────────────────────

    private function abort404(): never
    {
        http_response_code(404);
        echo '<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8">'
            . '<title>404 Not Found</title>'
            . '<meta name="viewport" content="width=device-width, initial-scale=1">'
            . '</head><body style="font-family:sans-serif;text-align:center;padding:4rem">'
            . '<h1>404 — Lesson Plan Not Found</h1>'
            . '<p>The lesson plan you are looking for does not exist or does not belong to your account.</p>'
            . '<a href="' . url('/lesson-plans') . '">Back to Lesson Plans</a>'
            . '</body></html>';
        exit;
    }

    private function parseObjectives(mixed $rawObjectives): array
    {
        if (!is_array($rawObjectives)) {
            return [];
        }

        $objectives = [];
        foreach ($rawObjectives as $objective) {
            if (!is_array($objective)) {
                continue;
            }
            $text = trim((string) ($objective['objective_text'] ?? ''));
            if ($text === '') {
                continue;
            }
            $objectives[] = [
                'sort_order'     => (int) ($objective['sort_order'] ?? 0),
                'objective_text' => $text,
            ];
        }

        return $objectives;
    }

    // ── Actions ───────────────────────────────────────────────────────────────

    /**
     * GET /lesson-plans
     */
    public function index(): void
    {
        AuthMiddleware::requireAuth();

        $studentId   = (int) $_SESSION['student_id'];
        $search      = trim($_GET['q']      ?? '');
        $status      = trim($_GET['status'] ?? '');
        $lessonPlans = [];

        try {
            $db          = Database::getConnection();
            $lessonPlans = LessonPlan::findByStudent($db, $studentId, $search, $status);
        } catch (PDOException $e) {
            error_log('LessonPlanController::index PDOException: ' . $e->getMessage());
        }

        require __DIR__ . '/../../views/lesson-plans/index.php';
    }

    /**
     * GET /lesson-plans/create
     */
    public function create(): void
    {
        AuthMiddleware::requireAuth();

        $lessonPlan = null;
        $objectives = [];
        $errors     = [];
        $old        = [];

        require __DIR__ . '/../../views/lesson-plans/form.php';
    }

    /**
     * POST /lesson-plans
     */
    public function store(): void
    {
        AuthMiddleware::requireAuth();

        $studentId = (int) $_SESSION['student_id'];

        $errors = [];

        if (trim($_POST['title'] ?? '') === '') {
            $errors['title'] = 'Lesson Title is required.';
        }

        if (trim($_POST['learning_competency'] ?? '') === '') {
            $errors['learning_competency'] = 'Learning Competency is required.';
        }

        if (!empty($errors)) {
            $lessonPlan = null;
            $objectives = $this->parseObjectives($_POST['objectives'] ?? null);
            $old        = $_POST;
            require __DIR__ . '/../../views/lesson-plans/form.php';
            return;
        }

        $data = [
            'title'                     => trim($_POST['title']                     ?? ''),
            'subject'                   => trim($_POST['subject']                   ?? ''),
            'grade_level'               => trim($_POST['grade_level']               ?? ''),
            'quarter'                   => $_POST['quarter']                        ?? '',
            'week'                      => $_POST['week']                           ?? '',
            'status'                    => $_POST['status']                         ?? 'draft',
            'date'                      => trim($_POST['date']                      ?? ''),
            'time_allotment_minutes'    => $_POST['time_allotment_minutes']         ?? '',
            'learning_competency'       => trim($_POST['learning_competency']       ?? ''),
            'subject_matter_topic'      => trim($_POST['subject_matter_topic']      ?? ''),
            'subject_matter_references' => trim($_POST['subject_matter_references'] ?? ''),
            'subject_matter_materials'  => trim($_POST['subject_matter_materials']  ?? ''),
            'proc_review_drill'         => trim($_POST['proc_review_drill']         ?? ''),
            'proc_motivation'           => trim($_POST['proc_motivation']           ?? ''),
            'proc_presentation'         => trim($_POST['proc_presentation']         ?? ''),
            'proc_discussion'           => trim($_POST['proc_discussion']           ?? ''),
            'proc_generalization'       => trim($_POST['proc_generalization']       ?? ''),
            'proc_application'          => trim($_POST['proc_application']          ?? ''),
            'evaluation'                => trim($_POST['evaluation']                ?? ''),
            'assignment'                => trim($_POST['assignment']                ?? ''),
        ];

        $objectives = $this->parseObjectives($_POST['objectives'] ?? null);

        try {
            $db    = Database::getConnection();
            $newId = LessonPlan::create($db, $studentId, $data);
            LessonObjective::replaceForLessonPlan($db, $newId, $objectives);
        } catch (PDOException $e) {
            error_log('LessonPlanController::store PDOException: ' . $e->getMessage());
            $lessonPlan = null;
            $old        = $_POST;
            $errors     = ['general' => 'Something went wrong. Please try again.'];
            require __DIR__ . '/../../views/lesson-plans/form.php';
            return;
        }

        redirect('/lesson-plans');
    }

    /**
     * GET /lesson-plans/{id}/edit
     */
    public function edit(int $id): void
    {
        AuthMiddleware::requireAuth();

        $studentId = (int) $_SESSION['student_id'];

        try {
            $db         = Database::getConnection();
            $lessonPlan = LessonPlan::findById($db, $id, $studentId);

            if ($lessonPlan === null) {
                $this->abort404();
            }

            $objectives = LessonObjective::findByLessonPlan($db, $id);
        } catch (PDOException $e) {
            error_log('LessonPlanController::edit PDOException: ' . $e->getMessage());
            $this->abort404();
        }

        $errors = [];
        $old    = [];

        require __DIR__ . '/../../views/lesson-plans/form.php';
    }

    /**
     * POST /lesson-plans/{id}
     */
    public function update(int $id): void
    {
        AuthMiddleware::requireAuth();

        $studentId = (int) $_SESSION['student_id'];

        $errors = [];

        if (trim($_POST['title'] ?? '') === '') {
            $errors['title'] = 'Lesson Title is required.';
        }

        if (trim($_POST['learning_competency'] ?? '') === '') {
            $errors['learning_competency'] = 'Learning Competency is required.';
        }

        if (!empty($errors)) {
            try {
                $db         = Database::getConnection();
                $lessonPlan = LessonPlan::findById($db, $id, $studentId);
            } catch (PDOException $e) {
                error_log('LessonPlanController::update (re-load) PDOException: ' . $e->getMessage());
                $lessonPlan = null;
            }

            if ($lessonPlan === null) {
                $this->abort404();
            }

            $objectives = $this->parseObjectives($_POST['objectives'] ?? null);
            $old        = $_POST;
            require __DIR__ . '/../../views/lesson-plans/form.php';
            return;
        }

        $data = [
            'title'                     => trim($_POST['title']                     ?? ''),
            'subject'                   => trim($_POST['subject']                   ?? ''),
            'grade_level'               => trim($_POST['grade_level']               ?? ''),
            'quarter'                   => $_POST['quarter']                        ?? '',
            'week'                      => $_POST['week']                           ?? '',
            'status'                    => $_POST['status']                         ?? 'draft',
            'date'                      => trim($_POST['date']                      ?? ''),
            'time_allotment_minutes'    => $_POST['time_allotment_minutes']         ?? '',
            'learning_competency'       => trim($_POST['learning_competency']       ?? ''),
            'subject_matter_topic'      => trim($_POST['subject_matter_topic']      ?? ''),
            'subject_matter_references' => trim($_POST['subject_matter_references'] ?? ''),
            'subject_matter_materials'  => trim($_POST['subject_matter_materials']  ?? ''),
            'proc_review_drill'         => trim($_POST['proc_review_drill']         ?? ''),
            'proc_motivation'           => trim($_POST['proc_motivation']           ?? ''),
            'proc_presentation'         => trim($_POST['proc_presentation']         ?? ''),
            'proc_discussion'           => trim($_POST['proc_discussion']           ?? ''),
            'proc_generalization'       => trim($_POST['proc_generalization']       ?? ''),
            'proc_application'          => trim($_POST['proc_application']          ?? ''),
            'evaluation'                => trim($_POST['evaluation']                ?? ''),
            'assignment'                => trim($_POST['assignment']                ?? ''),
        ];

        $objectives = $this->parseObjectives($_POST['objectives'] ?? null);

        try {
            $db = Database::getConnection();
            LessonPlan::update($db, $id, $studentId, $data);
            LessonObjective::replaceForLessonPlan($db, $id, $objectives);
        } catch (PDOException $e) {
            error_log('LessonPlanController::update PDOException: ' . $e->getMessage());

            try {
                $lessonPlan = LessonPlan::findById($db, $id, $studentId);
            } catch (PDOException $e2) {
                $lessonPlan = null;
            }

            if ($lessonPlan === null) {
                $this->abort404();
            }

            $objectives = $this->parseObjectives($_POST['objectives'] ?? null);
            $old        = $_POST;
            $errors     = ['general' => 'Something went wrong. Please try again.'];
            require __DIR__ . '/../../views/lesson-plans/form.php';
            return;
        }

        redirect('/lesson-plans');
    }

    /**
     * POST /lesson-plans/{id}/delete
     */
    public function delete(int $id): void
    {
        AuthMiddleware::requireAuth();

        $studentId = (int) $_SESSION['student_id'];

        try {
            $db = Database::getConnection();
            LessonPlan::delete($db, $id, $studentId);
        } catch (PDOException $e) {
            error_log('LessonPlanController::delete PDOException: ' . $e->getMessage());
        }

        redirect('/lesson-plans');
    }

    /**
     * GET /lesson-plans/{id}/export
     */
    public function export(int $id): void
    {
        AuthMiddleware::requireAuth();

        $studentId = (int) $_SESSION['student_id'];

        try {
            $db         = Database::getConnection();
            $lessonPlan = LessonPlan::findById($db, $id, $studentId);

            if ($lessonPlan === null) {
                $this->abort404();
            }

            $objectives = LessonObjective::findByLessonPlan($db, $id);
            $student    = Student::findById($db, $studentId);
        } catch (PDOException $e) {
            error_log('LessonPlanController::export PDOException: ' . $e->getMessage());
            $this->abort404();
        }

        require __DIR__ . '/../../views/lesson-plans/export.php';
    }

    /**
     * POST /lesson-plans/{id}/duplicate
     *
     * Creates a copy of the lesson plan (and its objectives) with "Copy of "
     * prefix and status reset to draft, then redirects to the new plan's edit page.
     */
    public function duplicate(int $id): void
    {
        AuthMiddleware::requireAuth();

        $studentId = (int) $_SESSION['student_id'];

        try {
            $db         = Database::getConnection();
            $lessonPlan = LessonPlan::findById($db, $id, $studentId);

            if ($lessonPlan === null) {
                $this->abort404();
            }

            $objectives = LessonObjective::findByLessonPlan($db, $id);

            $data = $lessonPlan;
            $data['title']  = 'Copy of ' . $lessonPlan['title'];
            $data['status'] = 'draft';
            unset($data['id'], $data['created_at'], $data['updated_at'], $data['student_id']);

            $newId = LessonPlan::create($db, $studentId, $data);
            LessonObjective::replaceForLessonPlan($db, $newId, $objectives);
        } catch (PDOException $e) {
            error_log('LessonPlanController::duplicate PDOException: ' . $e->getMessage());
            redirect('/lesson-plans');
        }

        redirect('/lesson-plans/' . $newId . '/edit');
    }
}
