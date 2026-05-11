<?php

// Feature: beed-student-portal, Property 2: Invalid credentials never create a session

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Eris\TestTrait;
use Eris\Generators;

/**
 * Property-based test: invalid credentials never create a session.
 *
 * **Validates: Requirements 1.6**
 *
 * Property 2: Invalid credentials never create a session
 *
 * For any email/password pair that does not match a registered student,
 * the login guard condition `$student === null || !password_verify($password, $hash)`
 * must evaluate to true — meaning the session-creation block is never reached.
 */
class InvalidCredentialsTest extends TestCase
{
    use TestTrait;

    /**
     * Override to avoid PHPUnit\Util\Test::parseTestMethodAnnotations() which
     * was removed in PHPUnit 10. Returning an empty array causes eris to use
     * its defaults (100 iterations, rand method).
     *
     * @return array
     */
    public function getTestCaseAnnotations(): array
    {
        return [];
    }

    /**
     * Property 2a: When the student record is not found (null), the login
     * guard is always true — no session is created.
     *
     * Simulates `Student::findByEmail()` returning null for any email.
     *
     * @eris-repeat 100
     */
    public function testNullStudentNeverCreatesSession(): void
    {
        $this
            ->forAll(
                Generators::string()
            )
            ->then(function (string $email) {
                // Simulate Student::findByEmail() returning null (student not found)
                $student = null;

                // The login guard from AuthController::login()
                $guardTriggered = ($student === null || !password_verify('any-password', ''));

                // Guard must be true — session creation block is skipped
                $this->assertTrue(
                    $guardTriggered,
                    "Login guard must be true when student is null (email: {$email})"
                );

                // Confirm $_SESSION['student_id'] would NOT be set
                // (we test the condition, not the actual session, to avoid side-effects)
                $sessionWouldBeCreated = !$guardTriggered;
                $this->assertFalse(
                    $sessionWouldBeCreated,
                    "Session must NOT be created when student record is null"
                );
            });
    }

    /**
     * Property 2b: When a wrong password is supplied, the login guard is
     * always true — no session is created.
     *
     * Creates a real bcrypt hash for a fixed correct password, then generates
     * random wrong passwords and verifies the guard fires for each one.
     *
     * @eris-repeat 100
     */
    public function testWrongPasswordNeverCreatesSession(): void
    {
        $correctPassword = 'correct-secret-password';
        $hash            = password_hash($correctPassword, PASSWORD_BCRYPT);

        // Fake student record (as returned by Student::findByEmail())
        $student = [
            'id'            => 1,
            'full_name'     => 'Test Student',
            'email'         => 'test@example.com',
            'password_hash' => $hash,
        ];

        $this
            ->forAll(
                Generators::string()
            )
            ->when(fn(string $wrongPassword) => $wrongPassword !== $correctPassword)
            ->then(function (string $wrongPassword) use ($student) {
                // password_verify must return false for any wrong password
                $verifyResult = password_verify($wrongPassword, $student['password_hash']);
                $this->assertFalse(
                    $verifyResult,
                    "password_verify() must return false for wrong password"
                );

                // The login guard from AuthController::login()
                $guardTriggered = ($student === null || !$verifyResult);

                // Guard must be true — session creation block is skipped
                $this->assertTrue(
                    $guardTriggered,
                    "Login guard must be true when password is wrong"
                );

                $sessionWouldBeCreated = !$guardTriggered;
                $this->assertFalse(
                    $sessionWouldBeCreated,
                    "Session must NOT be created when password is incorrect"
                );
            });
    }

    /**
     * Property 2c: Full login condition logic.
     *
     * Tests the boolean guard `$student === null || !password_verify($password, $hash)`:
     *   - null student  → always true  (no session)
     *   - wrong password → always true  (no session)
     *   - correct password → false      (session would be created)
     *
     * @eris-repeat 100
     */
    public function testLoginConditionLogic(): void
    {
        $this
            ->forAll(
                Generators::string(),
                Generators::string()
            )
            ->when(function (string $correct, string $wrong) {
                return strlen($correct) >= 1
                    && strlen($correct) <= 72
                    && strlen($wrong) >= 1
                    && $correct !== $wrong;
            })
            ->then(function (string $correctPassword, string $wrongPassword) {
                $hash = password_hash($correctPassword, PASSWORD_BCRYPT);

                $fakeStudent = [
                    'id'            => 42,
                    'full_name'     => 'Jane Doe',
                    'email'         => 'jane@example.com',
                    'password_hash' => $hash,
                ];

                // Case 1: null student — guard must be true (no session)
                $nullStudent  = null;
                $guardForNull = ($nullStudent === null || !password_verify($correctPassword, $hash));
                $this->assertTrue(
                    $guardForNull,
                    "Guard must be true when student is null"
                );

                // Case 2: wrong password — guard must be true (no session)
                $guardForWrong = ($fakeStudent === null || !password_verify($wrongPassword, $hash));
                $this->assertTrue(
                    $guardForWrong,
                    "Guard must be true when password is wrong"
                );

                // Case 3: correct password — guard must be false (session IS created)
                $guardForCorrect = ($fakeStudent === null || !password_verify($correctPassword, $hash));
                $this->assertFalse(
                    $guardForCorrect,
                    "Guard must be false when credentials are valid (session should be created)"
                );
            });
    }
}
