<?php
declare(strict_types=1);

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/Demo.php';
require_once __DIR__ . '/../models/DemoStep.php';
require_once __DIR__ . '/../models/Student.php';
require_once __DIR__ . '/../middleware/AuthMiddleware.php';

/**
 * DemoController
 *
 * Handles all Demo Maker CRUD operations, the printable export view,
 * and the duplicate action.
 *
 * Requirements: 3.1, 3.2, 3.3, 3.4, 3.5, 3.6, 3.7, 3.8, 4.1, 4.2, 4.3,
 *               4.4, 4.5
 */
class DemoController
{
    // ── Helpers ───────────────────────────────────────────────────────────────

    private function abort404(): never
    {
        http_response_code(404);
        echo '<!DOCTYPE html><html lang="en"><head><meta charset="UTF-8">'
            . '<title>404 Not Found</title>'
            . '<meta name="viewport" content="width=device-width, initial-scale=1">'
            . '</head><body style="font-family:sans-serif;text-align:center;padding:4rem">'
            . '<h1>404 — Demo Not Found</h1>'
            . '<p>The demo you are looking for does not exist or does not belong to your account.</p>'
            . '<a href="' . url('/demos') . '">Back to Demos</a>'
            . '</body></html>';
        exit;
    }

    private function parseSteps(mixed $rawSteps): array
    {
        if (!is_array($rawSteps)) {
            return [];
        }

        $steps = [];
        foreach ($rawSteps as $step) {
            if (!is_array($step)) {
                continue;
            }
            $description = trim((string) ($step['description'] ?? ''));
            if ($description === '') {
                continue;
            }
            $steps[] = [
                'step_number' => (int) ($step['step_number'] ?? 0),
                'description' => $description,
            ];
        }

        return $steps;
    }

    // ── Actions ───────────────────────────────────────────────────────────────

    /**
     * GET /demos
     */
    public function index(): void
    {
        AuthMiddleware::requireAuth();

        $studentId = (int) $_SESSION['student_id'];
        $search    = trim($_GET['q']      ?? '');
        $status    = trim($_GET['status'] ?? '');

        $demos = [];

        try {
            $db    = Database::getConnection();
            $demos = Demo::findByStudent($db, $studentId, $search, $status);
        } catch (PDOException $e) {
            error_log('DemoController::index PDOException: ' . $e->getMessage());
        }

        require __DIR__ . '/../../views/demos/index.php';
    }

    /**
     * GET /demos/create
     */
    public function create(): void
    {
        AuthMiddleware::requireAuth();

        $demo   = null;
        $steps  = [];
        $errors = [];
        $old    = [];

        require __DIR__ . '/../../views/demos/form.php';
    }

    /**
     * POST /demos
     */
    public function store(): void
    {
        AuthMiddleware::requireAuth();

        $studentId = (int) $_SESSION['student_id'];

        $errors = [];

        if (trim($_POST['title'] ?? '') === '') {
            $errors['title'] = 'Demo Title is required.';
        }

        if (trim($_POST['learning_objectives'] ?? '') === '') {
            $errors['learning_objectives'] = 'At least one Learning Objective is required.';
        }

        if (!empty($errors)) {
            $demo  = null;
            $steps = $this->parseSteps($_POST['steps'] ?? null);
            $old   = $_POST;
            require __DIR__ . '/../../views/demos/form.php';
            return;
        }

        $data = [
            'title'               => trim($_POST['title']               ?? ''),
            'subject'             => trim($_POST['subject']             ?? ''),
            'grade_level'         => trim($_POST['grade_level']         ?? ''),
            'quarter'             => $_POST['quarter']                  ?? '',
            'week'                => $_POST['week']                     ?? '',
            'status'              => $_POST['status']                   ?? 'draft',
            'duration_minutes'    => $_POST['duration_minutes']         ?? '',
            'learning_objectives' => trim($_POST['learning_objectives'] ?? ''),
            'materials_needed'    => trim($_POST['materials_needed']    ?? ''),
            'introduction'        => trim($_POST['introduction']        ?? ''),
            'generalization'      => trim($_POST['generalization']      ?? ''),
            'application'         => trim($_POST['application']         ?? ''),
            'assessment'          => trim($_POST['assessment']          ?? ''),
        ];

        $steps = $this->parseSteps($_POST['steps'] ?? null);

        try {
            $db    = Database::getConnection();
            $newId = Demo::create($db, $studentId, $data);
            DemoStep::replaceForDemo($db, $newId, $steps);
        } catch (PDOException $e) {
            error_log('DemoController::store PDOException: ' . $e->getMessage());
            $demo   = null;
            $old    = $_POST;
            $errors = ['general' => 'Something went wrong. Please try again.'];
            require __DIR__ . '/../../views/demos/form.php';
            return;
        }

        redirect('/demos');
    }

    /**
     * GET /demos/{id}/edit
     */
    public function edit(int $id): void
    {
        AuthMiddleware::requireAuth();

        $studentId = (int) $_SESSION['student_id'];

        try {
            $db   = Database::getConnection();
            $demo = Demo::findById($db, $id, $studentId);

            if ($demo === null) {
                $this->abort404();
            }

            $steps = DemoStep::findByDemo($db, $id);
        } catch (PDOException $e) {
            error_log('DemoController::edit PDOException: ' . $e->getMessage());
            $this->abort404();
        }

        $errors = [];
        $old    = [];

        require __DIR__ . '/../../views/demos/form.php';
    }

    /**
     * POST /demos/{id}
     */
    public function update(int $id): void
    {
        AuthMiddleware::requireAuth();

        $studentId = (int) $_SESSION['student_id'];

        $errors = [];

        if (trim($_POST['title'] ?? '') === '') {
            $errors['title'] = 'Demo Title is required.';
        }

        if (trim($_POST['learning_objectives'] ?? '') === '') {
            $errors['learning_objectives'] = 'At least one Learning Objective is required.';
        }

        if (!empty($errors)) {
            try {
                $db   = Database::getConnection();
                $demo = Demo::findById($db, $id, $studentId);
            } catch (PDOException $e) {
                error_log('DemoController::update (re-load) PDOException: ' . $e->getMessage());
                $demo = null;
            }

            if ($demo === null) {
                $this->abort404();
            }

            $steps = $this->parseSteps($_POST['steps'] ?? null);
            $old   = $_POST;
            require __DIR__ . '/../../views/demos/form.php';
            return;
        }

        $data = [
            'title'               => trim($_POST['title']               ?? ''),
            'subject'             => trim($_POST['subject']             ?? ''),
            'grade_level'         => trim($_POST['grade_level']         ?? ''),
            'quarter'             => $_POST['quarter']                  ?? '',
            'week'                => $_POST['week']                     ?? '',
            'status'              => $_POST['status']                   ?? 'draft',
            'duration_minutes'    => $_POST['duration_minutes']         ?? '',
            'learning_objectives' => trim($_POST['learning_objectives'] ?? ''),
            'materials_needed'    => trim($_POST['materials_needed']    ?? ''),
            'introduction'        => trim($_POST['introduction']        ?? ''),
            'generalization'      => trim($_POST['generalization']      ?? ''),
            'application'         => trim($_POST['application']         ?? ''),
            'assessment'          => trim($_POST['assessment']          ?? ''),
        ];

        $steps = $this->parseSteps($_POST['steps'] ?? null);

        try {
            $db = Database::getConnection();
            Demo::update($db, $id, $studentId, $data);
            DemoStep::replaceForDemo($db, $id, $steps);
        } catch (PDOException $e) {
            error_log('DemoController::update PDOException: ' . $e->getMessage());

            try {
                $demo = Demo::findById($db, $id, $studentId);
            } catch (PDOException $e2) {
                $demo = null;
            }

            if ($demo === null) {
                $this->abort404();
            }

            $steps  = $this->parseSteps($_POST['steps'] ?? null);
            $old    = $_POST;
            $errors = ['general' => 'Something went wrong. Please try again.'];
            require __DIR__ . '/../../views/demos/form.php';
            return;
        }

        redirect('/demos');
    }

    /**
     * POST /demos/{id}/delete
     */
    public function delete(int $id): void
    {
        AuthMiddleware::requireAuth();

        $studentId = (int) $_SESSION['student_id'];

        try {
            $db = Database::getConnection();
            Demo::delete($db, $id, $studentId);
        } catch (PDOException $e) {
            error_log('DemoController::delete PDOException: ' . $e->getMessage());
        }

        redirect('/demos');
    }

    /**
     * GET /demos/{id}/export
     */
    public function export(int $id): void
    {
        AuthMiddleware::requireAuth();

        $studentId = (int) $_SESSION['student_id'];

        try {
            $db      = Database::getConnection();
            $demo    = Demo::findById($db, $id, $studentId);

            if ($demo === null) {
                $this->abort404();
            }

            $steps   = DemoStep::findByDemo($db, $id);
            $student = Student::findById($db, $studentId);
        } catch (PDOException $e) {
            error_log('DemoController::export PDOException: ' . $e->getMessage());
            $this->abort404();
        }

        require __DIR__ . '/../../views/demos/export.php';
    }

    /**
     * POST /demos/{id}/duplicate
     *
     * Creates a copy of the demo (and its steps) with "Copy of " prefix and
     * status reset to draft, then redirects to the new demo's edit page.
     */
    public function duplicate(int $id): void
    {
        AuthMiddleware::requireAuth();

        $studentId = (int) $_SESSION['student_id'];

        try {
            $db   = Database::getConnection();
            $demo = Demo::findById($db, $id, $studentId);

            if ($demo === null) {
                $this->abort404();
            }

            $steps = DemoStep::findByDemo($db, $id);

            // Build copy data — strip identity/timestamp fields
            $data = $demo;
            $data['title']  = 'Copy of ' . $demo['title'];
            $data['status'] = 'draft';
            unset($data['id'], $data['created_at'], $data['updated_at'], $data['student_id']);

            $newId = Demo::create($db, $studentId, $data);
            DemoStep::replaceForDemo($db, $newId, $steps);
        } catch (PDOException $e) {
            error_log('DemoController::duplicate PDOException: ' . $e->getMessage());
            redirect('/demos');
        }

        redirect('/demos/' . $newId . '/edit');
    }
}
