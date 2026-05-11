<?php
declare(strict_types=1);

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/Student.php';
require_once __DIR__ . '/../middleware/AuthMiddleware.php';

/**
 * ProfileController
 *
 * Handles viewing and updating the student's profile information.
 */
class ProfileController
{
    /**
     * GET /profile
     *
     * Renders the profile form pre-populated with the student's current data.
     */
    public function show(): void
    {
        AuthMiddleware::requireAuth();

        $db      = Database::getConnection();
        $student = Student::findById($db, (int) $_SESSION['student_id']);
        $errors  = [];
        $success = isset($_GET['saved']) && $_GET['saved'] === '1';

        require __DIR__ . '/../../views/profile/show.php';
    }

    /**
     * POST /profile
     *
     * Saves the student's profile fields and redirects back with ?saved=1.
     */
    public function update(): void
    {
        AuthMiddleware::requireAuth();

        $db   = Database::getConnection();
        $data = [
            'school_name'         => trim($_POST['school_name']         ?? ''),
            'section'             => trim($_POST['section']             ?? ''),
            'year_level'          => trim($_POST['year_level']          ?? ''),
            'cooperating_teacher' => trim($_POST['cooperating_teacher'] ?? ''),
        ];

        try {
            Student::updateProfile($db, (int) $_SESSION['student_id'], $data);
        } catch (PDOException $e) {
            error_log('ProfileController::update PDOException: ' . $e->getMessage());
        }

        redirect('/profile?saved=1');
    }
}
