<?php

class AuthMiddleware {
    public static function requireAuth(): void {
        session_start();
        if (empty($_SESSION['student_id'])) {
            redirect('/login');
        }
    }
}
