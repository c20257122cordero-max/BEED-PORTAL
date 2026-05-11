<?php
declare(strict_types=1);

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/DemoTemplate.php';
require_once __DIR__ . '/../middleware/AuthMiddleware.php';

class DemoTemplateController
{
    private function abort404(): never
    {
        http_response_code(404);
        echo '<body style="font-family:sans-serif;text-align:center;padding:4rem"><h1>404</h1><a href="' . url('/demo-templates') . '">Back</a></body>';
        exit;
    }

    /** GET /demo-templates */
    public function index(): void
    {
        AuthMiddleware::requireAuth();
        $db        = Database::getConnection();
        $templates = DemoTemplate::findByStudent($db, (int)$_SESSION['student_id']);
        $success   = $_GET['saved'] ?? null;
        require __DIR__ . '/../../views/demo-templates/index.php';
    }

    /** GET /demo-templates/create */
    public function create(): void
    {
        AuthMiddleware::requireAuth();
        $template = null; $errors = []; $old = [];
        require __DIR__ . '/../../views/demo-templates/form.php';
    }

    /** POST /demo-templates */
    public function store(): void
    {
        AuthMiddleware::requireAuth();
        $errors = [];
        if (trim($_POST['name'] ?? '') === '') { $errors['name'] = 'Template name is required.'; }
        if (!empty($errors)) { $template = null; $old = $_POST; require __DIR__ . '/../../views/demo-templates/form.php'; return; }
        $db = Database::getConnection();
        DemoTemplate::create($db, (int)$_SESSION['student_id'], $this->extract($_POST));
        redirect('/demo-templates?saved=1');
    }

    /** GET /demo-templates/{id}/edit */
    public function edit(int $id): void
    {
        AuthMiddleware::requireAuth();
        $db = Database::getConnection();
        $template = DemoTemplate::findById($db, $id, (int)$_SESSION['student_id']);
        if (!$template) $this->abort404();
        $errors = []; $old = [];
        require __DIR__ . '/../../views/demo-templates/form.php';
    }

    /** POST /demo-templates/{id} */
    public function update(int $id): void
    {
        AuthMiddleware::requireAuth();
        $errors = [];
        if (trim($_POST['name'] ?? '') === '') { $errors['name'] = 'Template name is required.'; }
        if (!empty($errors)) {
            $db = Database::getConnection();
            $template = DemoTemplate::findById($db, $id, (int)$_SESSION['student_id']);
            if (!$template) $this->abort404();
            $old = $_POST;
            require __DIR__ . '/../../views/demo-templates/form.php';
            return;
        }
        $db = Database::getConnection();
        DemoTemplate::update($db, $id, (int)$_SESSION['student_id'], $this->extract($_POST));
        redirect('/demo-templates?saved=1');
    }

    /** POST /demo-templates/{id}/delete */
    public function delete(int $id): void
    {
        AuthMiddleware::requireAuth();
        $db = Database::getConnection();
        DemoTemplate::delete($db, $id, (int)$_SESSION['student_id']);
        redirect('/demo-templates');
    }

    /** GET /demo-templates/{id}/apply — JSON */
    public function apply(int $id): void
    {
        AuthMiddleware::requireAuth();
        $db = Database::getConnection();
        $tpl = DemoTemplate::findById($db, $id, (int)$_SESSION['student_id']);
        header('Content-Type: application/json');
        echo json_encode($tpl ? ['success' => true, 'template' => $tpl] : ['success' => false]);
        exit;
    }

    /** POST /demo-templates/save-from-demo — JSON */
    public function saveFromDemo(): void
    {
        AuthMiddleware::requireAuth();
        header('Content-Type: application/json');
        $name = trim($_POST['template_name'] ?? '');
        if ($name === '') { echo json_encode(['success' => false, 'error' => 'Template name is required.']); exit; }

        // Build steps JSON from posted steps array
        $stepsRaw = $_POST['steps'] ?? [];
        $stepTexts = [];
        if (is_array($stepsRaw)) {
            foreach ($stepsRaw as $s) {
                $desc = trim((string)($s['description'] ?? ''));
                if ($desc !== '') $stepTexts[] = $desc;
            }
        }

        $data = [
            'name'                    => $name,
            'description'             => trim($_POST['template_description'] ?? ''),
            'subject_tpl'             => trim($_POST['subject']             ?? ''),
            'grade_level_tpl'         => trim($_POST['grade_level']         ?? ''),
            'duration_minutes_tpl'    => $_POST['duration_minutes']         ?? '',
            'learning_objectives_tpl' => trim($_POST['learning_objectives'] ?? ''),
            'materials_needed_tpl'    => trim($_POST['materials_needed']    ?? ''),
            'introduction_tpl'        => trim($_POST['introduction']        ?? ''),
            'generalization_tpl'      => trim($_POST['generalization']      ?? ''),
            'application_tpl'         => trim($_POST['application']         ?? ''),
            'assessment_tpl'          => trim($_POST['assessment']          ?? ''),
            'steps_tpl'               => !empty($stepTexts) ? json_encode($stepTexts) : null,
        ];

        $db  = Database::getConnection();
        $id  = DemoTemplate::create($db, (int)$_SESSION['student_id'], $data);
        echo json_encode(['success' => true, 'id' => $id, 'name' => $name]);
        exit;
    }

    private function extract(array $p): array
    {
        $stepsRaw = $p['steps'] ?? [];
        $stepTexts = [];
        if (is_array($stepsRaw)) {
            foreach ($stepsRaw as $s) {
                $desc = trim((string)($s['description'] ?? ''));
                if ($desc !== '') $stepTexts[] = $desc;
            }
        }
        return [
            'name'                    => trim($p['name']                    ?? ''),
            'description'             => trim($p['description']             ?? ''),
            'subject_tpl'             => trim($p['subject_tpl']             ?? ''),
            'grade_level_tpl'         => trim($p['grade_level_tpl']         ?? ''),
            'duration_minutes_tpl'    => $p['duration_minutes_tpl']         ?? '',
            'learning_objectives_tpl' => trim($p['learning_objectives_tpl'] ?? ''),
            'materials_needed_tpl'    => trim($p['materials_needed_tpl']    ?? ''),
            'introduction_tpl'        => trim($p['introduction_tpl']        ?? ''),
            'generalization_tpl'      => trim($p['generalization_tpl']      ?? ''),
            'application_tpl'         => trim($p['application_tpl']         ?? ''),
            'assessment_tpl'          => trim($p['assessment_tpl']          ?? ''),
            'steps_tpl'               => !empty($stepTexts) ? json_encode($stepTexts) : null,
        ];
    }
}
