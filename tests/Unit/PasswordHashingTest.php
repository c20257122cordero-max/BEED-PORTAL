<?php

// Feature: beed-student-portal, Property 1: Password hashing round-trip

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use Eris\TestTrait;
use Eris\Generators;

/**
 * Property-based test for password hashing round-trip.
 *
 * **Validates: Requirements 1.4**
 *
 * Property 1: Password hashing round-trip
 *
 * For any plaintext password, the bcrypt hash produced by password_hash()
 * must differ from the plaintext, and password_verify() must confirm the
 * original password against that hash.
 */
class PasswordHashingTest extends TestCase
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
     * Property 1: Password hashing round-trip.
     *
     * For any non-empty plaintext password:
     *   - password_hash($p, PASSWORD_BCRYPT) must NOT equal $p
     *   - password_verify($p, $hash) must return true
     *
     * @eris-repeat 100
     */
    public function testPasswordHashIsNotPlaintext(): void
    {
        $this
            ->forAll(
                Generators::string()
            )
            ->when(fn(string $password) => strlen($password) >= 1 && strlen($password) <= 72)
            ->then(function (string $password) {
                $hash = password_hash($password, PASSWORD_BCRYPT);

                // The hash must not equal the plaintext
                $this->assertNotEquals(
                    $password,
                    $hash,
                    "Hash should not equal plaintext password"
                );

                // password_verify must confirm the original password
                $this->assertTrue(
                    password_verify($password, $hash),
                    "password_verify() should return true for the correct password"
                );
            });
    }

    /**
     * Property: Different passwords produce different hashes.
     *
     * Bcrypt incorporates a random salt, so even the same password hashed
     * twice produces different hashes. Two distinct passwords must therefore
     * also produce different hashes.
     *
     * @eris-repeat 100
     */
    public function testDifferentPasswordsProduceDifferentHashes(): void
    {
        $this
            ->forAll(
                Generators::string(),
                Generators::string()
            )
            ->when(function (string $a, string $b) {
                return strlen($a) >= 1
                    && strlen($b) >= 1
                    && strlen($a) <= 72
                    && strlen($b) <= 72
                    && $a !== $b;
            })
            ->then(function (string $passwordA, string $passwordB) {
                $hashA = password_hash($passwordA, PASSWORD_BCRYPT);
                $hashB = password_hash($passwordB, PASSWORD_BCRYPT);

                // Different passwords must produce different hashes
                $this->assertNotEquals(
                    $hashA,
                    $hashB,
                    "Different passwords should produce different hashes"
                );
            });
    }

    /**
     * Property: Wrong password fails verification.
     *
     * For any pair of distinct passwords, verifying the wrong password against
     * a hash of the correct password must return false.
     *
     * @eris-repeat 100
     */
    public function testWrongPasswordFailsVerification(): void
    {
        $this
            ->forAll(
                Generators::string(),
                Generators::string()
            )
            ->when(function (string $correct, string $wrong) {
                return strlen($correct) >= 1
                    && strlen($wrong) >= 1
                    && strlen($correct) <= 72
                    && strlen($wrong) <= 72
                    && $correct !== $wrong;
            })
            ->then(function (string $correctPassword, string $wrongPassword) {
                $hash = password_hash($correctPassword, PASSWORD_BCRYPT);

                // Verifying the wrong password must return false
                $this->assertFalse(
                    password_verify($wrongPassword, $hash),
                    "password_verify() should return false for an incorrect password"
                );
            });
    }
}
