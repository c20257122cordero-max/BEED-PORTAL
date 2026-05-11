<?php

declare(strict_types=1);

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/Demo.php';
require_once __DIR__ . '/../models/LessonPlan.php';
require_once __DIR__ . '/../middleware/AuthMiddleware.php';

/**
 * DashboardController
 *
 * Renders the student dashboard with the 5 most recently modified demos and
 * lesson plans for the authenticated student.
 *
 * Requirements: 2.1, 2.2, 2.3
 */
class DashboardController
{
    /**
     * GET /dashboard
     *
     * Enforces authentication, then fetches the 5 most recent demos and
     * lesson plans for the current student and passes them to the dashboard
     * view (Requirements 2.1, 2.2, 2.3).
     */
    public function index(): void
    {
        AuthMiddleware::requireAuth();

        $studentId   = (int) $_SESSION['student_id'];
        $studentName = $_SESSION['student_name'] ?? '';

        try {
            $db = Database::getConnection();

            $recentDemos       = Demo::recentByStudent($db, $studentId, 5);
            $recentLessonPlans = LessonPlan::recentByStudent($db, $studentId, 5);
        } catch (PDOException $e) {
            error_log('DashboardController::index PDOException: ' . $e->getMessage());
            $recentDemos       = [];
            $recentLessonPlans = [];
        }

        require __DIR__ . '/../../views/dashboard/index.php';
    }
}
