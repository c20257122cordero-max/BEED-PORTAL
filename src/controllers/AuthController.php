<?php

declare(strict_types=1);

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/Student.php';

/**
 * AuthController
 *
 * Handles user registration, login, and logout.
 *
 * Requirements: 1.1, 1.2, 1.3, 1.4, 1.5, 1.6, 1.7
 */
class AuthController
{
    // ── Show forms ────────────────────────────────────────────────────────────

    /**
     * GET /login
     *
     * Renders the login form.
     */
    public function showLogin(): void
    {
        $error = null;
        require __DIR__ . '/../../views/auth/login.php';
    }

    /**
     * GET /register
     *
     * Renders the registration form.
     */
    public function showRegister(): void
    {
        $errors = [];
        $old    = [];
        require __DIR__ . '/../../views/auth/register.php';
    }

    // ── Actions ───────────────────────────────────────────────────────────────

    /**
     * POST /register
     *
     * Validates input, hashes the password with bcrypt, and creates a new
     * student account.  On duplicate email (SQLSTATE 23000) an error is shown
     * instead of creating a second record (Requirements 1.2, 1.3, 1.4).
     */
    public function register(): void
    {
        session_start();

        $fullName = trim($_POST['full_name'] ?? '');
        $email    = trim($_POST['email']     ?? '');
        $password = $_POST['password']       ?? '';

        // ── Validation ────────────────────────────────────────────────────────
        $errors = [];
        $old    = ['full_name' => $fullName, 'email' => $email];

        if ($fullName === '') {
            $errors['full_name'] = 'Full name is required.';
        }

        if ($email === '') {
            $errors['email'] = 'Email address is required.';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Please enter a valid email address.';
        }

        if ($password === '') {
            $errors['password'] = 'Password is required.';
        } elseif (strlen($password) < 8) {
            $errors['password'] = 'Password must be at least 8 characters.';
        }

        if (!empty($errors)) {
            require __DIR__ . '/../../views/auth/register.php';
            return;
        }

        // ── Persist ───────────────────────────────────────────────────────────
        $passwordHash = password_hash($password, PASSWORD_BCRYPT);

        try {
            $db = Database::getConnection();
            Student::create($db, $fullName, $email, $passwordHash);
        } catch (PDOException $e) {
            // SQLSTATE 23000 — unique constraint violation (duplicate email)
            if (str_starts_with($e->getCode(), '23')) {
                $errors['email'] = 'This email address is already registered.';
                require __DIR__ . '/../../views/auth/register.php';
                return;
            }

            // Unexpected DB error — log and show generic message
            error_log('AuthController::register PDOException: ' . $e->getMessage());
            $errors['general'] = 'Something went wrong. Please try again.';
            require __DIR__ . '/../../views/auth/register.php';
            return;
        }

        // Registration successful — redirect to login
        redirect('/login');
    }

    /**
     * POST /login
     *
     * Verifies credentials with password_verify().  On success, stores the
     * student ID and name in the session and redirects to the dashboard
     * (Requirements 1.5, 1.6).  On failure, shows a generic error message
     * that does not reveal which field was wrong.
     */
    public function login(): void
    {
        session_start();

        $email    = trim($_POST['email']    ?? '');
        $password = $_POST['password']      ?? '';
        $error    = null;

        // Basic presence check before hitting the DB
        if ($email === '' || $password === '') {
            $error = 'Invalid email or password.';
            require __DIR__ . '/../../views/auth/login.php';
            return;
        }

        try {
            $db      = Database::getConnection();
            $student = Student::findByEmail($db, $email);
        } catch (PDOException $e) {
            error_log('AuthController::login PDOException: ' . $e->getMessage());
            $error = 'Something went wrong. Please try again.';
            require __DIR__ . '/../../views/auth/login.php';
            return;
        }

        // Verify password — intentionally generic error to prevent enumeration
        if ($student === null || !password_verify($password, $student['password_hash'])) {
            $error = 'Invalid email or password.';
            require __DIR__ . '/../../views/auth/login.php';
            return;
        }

        // ── Authenticated — create session ────────────────────────────────────
        session_regenerate_id(true); // prevent session fixation

        $_SESSION['student_id']   = (int) $student['id'];
        $_SESSION['student_name'] = $student['full_name'];

        redirect('/dashboard');
    }

    /**
     * POST /logout
     *
     * Destroys the session and redirects to the login page (Requirement 1.7).
     */
    public function logout(): void
    {
        session_start();
        session_destroy();

        redirect('/login');
    }
}
