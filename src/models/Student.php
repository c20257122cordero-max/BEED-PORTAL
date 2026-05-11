<?php
declare(strict_types=1);

/**
 * Student model.
 *
 * Handles all database operations for the `students` table using PDO prepared
 * statements — never string-interpolated SQL (Requirements 7.3, 7.4).
 */
class Student
{
    /**
     * Find a student record by email address.
     *
     * Returns the matching row as an associative array, or null if no student
     * with that email exists.
     *
     * Used by AuthController to look up a student during login (Requirement 1.1).
     *
     * @param PDO    $db    PDO connection (ERRMODE_EXCEPTION, FETCH_ASSOC).
     * @param string $email The email address to search for.
     *
     * @return array|null Associative array of the student row, or null.
     */
    public static function findByEmail(PDO $db, string $email): ?array
    {
        $stmt = $db->prepare(
            'SELECT id, full_name, email, password_hash, created_at
               FROM students
              WHERE email = :email
              LIMIT 1'
        );
        $stmt->execute([':email' => $email]);

        $row = $stmt->fetch();

        return $row !== false ? $row : null;
    }

    /**
     * Find a student record by primary key.
     *
     * Returns the matching row (including profile fields) or null.
     *
     * @param PDO $db PDO connection (ERRMODE_EXCEPTION, FETCH_ASSOC).
     * @param int $id The student's primary key.
     *
     * @return array|null Associative array of the student row, or null.
     */
    public static function findById(PDO $db, int $id): ?array
    {
        $stmt = $db->prepare(
            'SELECT id, full_name, email, school_name, section, year_level,
                    cooperating_teacher, created_at
               FROM students
              WHERE id = :id
              LIMIT 1'
        );
        $stmt->execute([':id' => $id]);

        $row = $stmt->fetch();

        return $row !== false ? $row : null;
    }

    /**
     * Update a student's profile fields.
     *
     * Updates school_name, section, year_level, and cooperating_teacher for
     * the given student ID.
     *
     * @param PDO   $db   PDO connection (ERRMODE_EXCEPTION, FETCH_ASSOC).
     * @param int   $id   The student's primary key.
     * @param array $data Associative array with keys: school_name, section,
     *                    year_level, cooperating_teacher.
     *
     * @return bool True if the record was updated; false otherwise.
     */
    public static function updateProfile(PDO $db, int $id, array $data): bool
    {
        $stmt = $db->prepare(
            'UPDATE students
                SET school_name          = :school_name,
                    section              = :section,
                    year_level           = :year_level,
                    cooperating_teacher  = :cooperating_teacher
              WHERE id = :id'
        );
        $stmt->execute([
            ':id'                   => $id,
            ':school_name'          => $data['school_name']         ?? null,
            ':section'              => $data['section']             ?? null,
            ':year_level'           => $data['year_level']          ?? null,
            ':cooperating_teacher'  => $data['cooperating_teacher'] ?? null,
        ]);

        return $stmt->rowCount() > 0;
    }

    /**
     * Create a new student account.
     *
     * Inserts a row into the `students` table and returns the auto-generated
     * primary key of the new record.
     *
     * The caller is responsible for hashing the password with bcrypt before
     * passing it here (Requirement 1.4).  Email uniqueness is enforced by the
     * database UNIQUE constraint; the caller should catch PDOException with
     * SQLSTATE 23000 to handle duplicate-email errors (Requirements 1.2, 1.3).
     *
     * @param PDO    $db           PDO connection (ERRMODE_EXCEPTION, FETCH_ASSOC).
     * @param string $fullName     Student's full name.
     * @param string $email        Student's email address (must be unique).
     * @param string $passwordHash bcrypt hash of the student's password.
     *
     * @return int The new student's auto-incremented ID.
     */
    public static function create(
        PDO $db,
        string $fullName,
        string $email,
        string $passwordHash
    ): int {
        $stmt = $db->prepare(
            'INSERT INTO students (full_name, email, password_hash)
             VALUES (:full_name, :email, :password_hash)'
        );
        $stmt->execute([
            ':full_name'     => $fullName,
            ':email'         => $email,
            ':password_hash' => $passwordHash,
        ]);

        return (int) $db->lastInsertId();
    }
}
