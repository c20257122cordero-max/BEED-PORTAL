<?php
declare(strict_types=1);

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/LessonPlanTemplate.php';
require_once __DIR__ . '/../middleware/AuthMiddleware.php';

/**
 * TemplateController — manages custom lesson plan templates.
 */
class TemplateController
{
    private function abort404(): never
    {
        http_response_code(404);
        echo '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>404</title></head>'
            . '<body style="font-family:sans-serif;text-align:center;padding:4rem">'
            . '<h1>404 — Template Not Found</h1>'
            . '<a href="' . url('/templates') . '">Back to Templates</a>'
            . '</body></html>';
        exit;
    }

    /** GET /templates */
    public function index(): void
    {
        AuthMiddleware::requireAuth();
        $studentId = (int) $_SESSION['student_id'];
        $db        = Database::getConnection();
        $templates = LessonPlanTemplate::findByStudent($db, $studentId);
        $success   = $_GET['saved'] ?? null;
        require __DIR__ . '/../../views/templates/index.php';
    }

    /** GET /templates/create */
    public function create(): void
    {
        AuthMiddleware::requireAuth();
        $template = null;
        $errors   = [];
        $old      = [];
        require __DIR__ . '/../../views/templates/form.php';
    }

    /** POST /templates */
    public function store(): void
    {
        AuthMiddleware::requireAuth();
        $studentId = (int) $_SESSION['student_id'];

        $errors = [];
        if (trim($_POST['name'] ?? '') === '') {
            $errors['name'] = 'Template name is required.';
        }

        if (!empty($errors)) {
            $template = null;
            $old      = $_POST;
            require __DIR__ . '/../../views/templates/form.php';
            return;
        }

        $data = $this->extractData($_POST);
        $db   = Database::getConnection();
        LessonPlanTemplate::create($db, $studentId, $data);
        redirect('/templates?saved=1');
    }

    /** GET /templates/{id}/edit */
    public function edit(int $id): void
    {
        AuthMiddleware::requireAuth();
        $studentId = (int) $_SESSION['student_id'];
        $db        = Database::getConnection();
        $template  = LessonPlanTemplate::findById($db, $id, $studentId);
        if (!$template) $this->abort404();
        $errors = [];
        $old    = [];
        require __DIR__ . '/../../views/templates/form.php';
    }

    /** POST /templates/{id} */
    public function update(int $id): void
    {
        AuthMiddleware::requireAuth();
        $studentId = (int) $_SESSION['student_id'];

        $errors = [];
        if (trim($_POST['name'] ?? '') === '') {
            $errors['name'] = 'Template name is required.';
        }

        if (!empty($errors)) {
            $db       = Database::getConnection();
            $template = LessonPlanTemplate::findById($db, $id, $studentId);
            if (!$template) $this->abort404();
            $old = $_POST;
            require __DIR__ . '/../../views/templates/form.php';
            return;
        }

        $data = $this->extractData($_POST);
        $db   = Database::getConnection();
        LessonPlanTemplate::update($db, $id, $studentId, $data);
        redirect('/templates?saved=1');
    }

    /** POST /templates/{id}/delete */
    public function delete(int $id): void
    {
        AuthMiddleware::requireAuth();
        $studentId = (int) $_SESSION['student_id'];
        $db        = Database::getConnection();
        LessonPlanTemplate::delete($db, $id, $studentId);
        redirect('/templates');
    }

    /** GET /templates/{id}/apply — returns JSON for AJAX */
    public function apply(int $id): void
    {
        AuthMiddleware::requireAuth();
        $studentId = (int) $_SESSION['student_id'];
        $db        = Database::getConnection();
        $template  = LessonPlanTemplate::findById($db, $id, $studentId);

        header('Content-Type: application/json');
        if (!$template) {
            echo json_encode(['success' => false, 'error' => 'Template not found']);
            exit;
        }
        echo json_encode(['success' => true, 'template' => $template]);
        exit;
    }

    /** POST /templates/save-from-plan — AJAX: save current form as template */
    public function saveFromPlan(): void
    {
        AuthMiddleware::requireAuth();
        $studentId = (int) $_SESSION['student_id'];

        header('Content-Type: application/json');

        $name = trim($_POST['template_name'] ?? '');
        if ($name === '') {
            echo json_encode(['success' => false, 'error' => 'Template name is required.']);
            exit;
        }

        // Build objectives JSON from posted objectives array
        $objectivesRaw = $_POST['objectives'] ?? [];
        $objectiveTexts = [];
        if (is_array($objectivesRaw)) {
            foreach ($objectivesRaw as $obj) {
                $text = trim((string)($obj['objective_text'] ?? ''));
                if ($text !== '') $objectiveTexts[] = $text;
            }
        }

        $data = [
            'name'                            => $name,
            'description'                     => trim($_POST['description'] ?? ''),
            'subject_tpl'                     => trim($_POST['subject']                   ?? ''),
            'grade_level_tpl'                 => trim($_POST['grade_level']               ?? ''),
            'time_allotment_tpl'              => $_POST['time_allotment_minutes']          ?? '',
            'learning_competency_tpl'         => trim($_POST['learning_competency']        ?? ''),
            'subject_matter_topic_tpl'        => trim($_POST['subject_matter_topic']       ?? ''),
            'subject_matter_references_tpl'   => trim($_POST['subject_matter_references']  ?? ''),
            'subject_matter_materials_tpl'    => trim($_POST['subject_matter_materials']   ?? ''),
            'objectives_tpl'                  => !empty($objectiveTexts) ? json_encode($objectiveTexts) : null,
            'proc_review_drill_tpl'           => trim($_POST['proc_review_drill']          ?? ''),
            'proc_motivation_tpl'             => trim($_POST['proc_motivation']            ?? ''),
            'proc_presentation_tpl'           => trim($_POST['proc_presentation']          ?? ''),
            'proc_discussion_tpl'             => trim($_POST['proc_discussion']            ?? ''),
            'proc_generalization_tpl'         => trim($_POST['proc_generalization']        ?? ''),
            'proc_application_tpl'            => trim($_POST['proc_application']           ?? ''),
            'evaluation_tpl'                  => trim($_POST['evaluation']                 ?? ''),
            'assignment_tpl'                  => trim($_POST['assignment']                 ?? ''),
        ];

        $db  = Database::getConnection();
        $id  = LessonPlanTemplate::create($db, $studentId, $data);
        echo json_encode(['success' => true, 'id' => $id, 'name' => $name]);
        exit;
    }

    /** Extract template data from POST (template form fields use _tpl suffix) */
    private function extractData(array $post): array
    {
        // Build objectives JSON
        $objectivesRaw  = $post['objectives'] ?? [];
        $objectiveTexts = [];
        if (is_array($objectivesRaw)) {
            foreach ($objectivesRaw as $obj) {
                $text = trim((string)($obj['objective_text'] ?? ''));
                if ($text !== '') $objectiveTexts[] = $text;
            }
        }

        return [
            'name'                            => trim($post['name']                            ?? ''),
            'description'                     => trim($post['description']                     ?? ''),
            'subject_tpl'                     => trim($post['subject_tpl']                     ?? ''),
            'grade_level_tpl'                 => trim($post['grade_level_tpl']                 ?? ''),
            'time_allotment_tpl'              => $post['time_allotment_tpl']                   ?? '',
            'learning_competency_tpl'         => trim($post['learning_competency_tpl']         ?? ''),
            'subject_matter_topic_tpl'        => trim($post['subject_matter_topic_tpl']        ?? ''),
            'subject_matter_references_tpl'   => trim($post['subject_matter_references_tpl']   ?? ''),
            'subject_matter_materials_tpl'    => trim($post['subject_matter_materials_tpl']    ?? ''),
            'objectives_tpl'                  => !empty($objectiveTexts) ? json_encode($objectiveTexts) : null,
            'proc_review_drill_tpl'           => trim($post['proc_review_drill_tpl']           ?? ''),
            'proc_motivation_tpl'             => trim($post['proc_motivation_tpl']             ?? ''),
            'proc_presentation_tpl'           => trim($post['proc_presentation_tpl']           ?? ''),
            'proc_discussion_tpl'             => trim($post['proc_discussion_tpl']             ?? ''),
            'proc_generalization_tpl'         => trim($post['proc_generalization_tpl']         ?? ''),
            'proc_application_tpl'            => trim($post['proc_application_tpl']            ?? ''),
            'evaluation_tpl'                  => trim($post['evaluation_tpl']                  ?? ''),
            'assignment_tpl'                  => trim($post['assignment_tpl']                  ?? ''),
        ];
    }
}
