-- =============================================================================
-- BEED Student Portal — Database Schema
-- =============================================================================
-- This file creates the beed_portal database and all required tables for the
-- BEED Student Portal application. It defines the students, demos, demo_steps,
-- lesson_plans, and lesson_objectives tables with appropriate constraints,
-- foreign keys (ON DELETE CASCADE), and indexes for search and ordered retrieval.
--
-- Run this script once to initialise a fresh database, or re-run safely because
-- all statements use IF NOT EXISTS guards.
-- =============================================================================

CREATE DATABASE IF NOT EXISTS beed_portal
    DEFAULT CHARACTER SET utf8mb4
    DEFAULT COLLATE utf8mb4_unicode_ci;

USE beed_portal;

-- -----------------------------------------------------------------------------
-- Table: students
-- Stores registered BEED student accounts.
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS students (
    id            INT UNSIGNED   NOT NULL AUTO_INCREMENT,
    full_name     VARCHAR(255)   NOT NULL,
    email         VARCHAR(255)   NOT NULL,
    password_hash VARCHAR(255)   NOT NULL,
    created_at    TIMESTAMP      NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (id),
    UNIQUE KEY uq_students_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------------------------
-- Table: demos
-- Stores teaching demonstration plans created by students.
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS demos (
    id                  INT UNSIGNED     NOT NULL AUTO_INCREMENT,
    student_id          INT UNSIGNED     NOT NULL,
    title               VARCHAR(255)     NOT NULL,
    subject             VARCHAR(255)     NULL,
    grade_level         VARCHAR(50)      NULL,
    duration_minutes    SMALLINT UNSIGNED NULL,
    learning_objectives TEXT             NOT NULL,
    materials_needed    TEXT             NULL,
    introduction        TEXT             NULL,
    generalization      TEXT             NULL,
    application         TEXT             NULL,
    assessment          TEXT             NULL,
    created_at          TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at          TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (id),
    CONSTRAINT fk_demos_student_id
        FOREIGN KEY (student_id) REFERENCES students (id) ON DELETE CASCADE,
    INDEX idx_demos_title (title)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- FULLTEXT index for demo search (title + subject) — supports MATCH ... AGAINST queries.
ALTER TABLE demos ADD FULLTEXT INDEX ft_demo_search (title, subject);

-- -----------------------------------------------------------------------------
-- Table: demo_steps
-- Stores the individual steps of the Lesson Proper section for each demo.
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS demo_steps (
    id          INT UNSIGNED     NOT NULL AUTO_INCREMENT,
    demo_id     INT UNSIGNED     NOT NULL,
    step_number SMALLINT UNSIGNED NOT NULL,
    description TEXT             NOT NULL,

    PRIMARY KEY (id),
    CONSTRAINT fk_demo_steps_demo_id
        FOREIGN KEY (demo_id) REFERENCES demos (id) ON DELETE CASCADE,
    INDEX idx_demo_steps_order (demo_id, step_number)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------------------------
-- Table: lesson_plans
-- Stores full lesson plans created by students.
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS lesson_plans (
    id                       INT UNSIGNED      NOT NULL AUTO_INCREMENT,
    student_id               INT UNSIGNED      NOT NULL,
    title                    VARCHAR(255)      NOT NULL,
    subject                  VARCHAR(255)      NULL,
    grade_level              VARCHAR(50)       NULL,
    date                     DATE              NULL,
    time_allotment_minutes   SMALLINT UNSIGNED NULL,
    learning_competency      VARCHAR(500)      NOT NULL,
    subject_matter_topic     VARCHAR(255)      NULL,
    subject_matter_references TEXT             NULL,
    subject_matter_materials  TEXT             NULL,
    proc_review_drill        TEXT              NULL,
    proc_motivation          TEXT              NULL,
    proc_presentation        TEXT              NULL,
    proc_discussion          TEXT              NULL,
    proc_generalization      TEXT              NULL,
    proc_application         TEXT              NULL,
    evaluation               TEXT              NULL,
    assignment               TEXT              NULL,
    created_at               TIMESTAMP         NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at               TIMESTAMP         NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (id),
    CONSTRAINT fk_lesson_plans_student_id
        FOREIGN KEY (student_id) REFERENCES students (id) ON DELETE CASCADE,
    INDEX idx_lesson_plans_title (title)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- FULLTEXT index for lesson plan search (title + subject + learning_competency).
ALTER TABLE lesson_plans ADD FULLTEXT INDEX ft_lp_search (title, subject, learning_competency);

-- -----------------------------------------------------------------------------
-- Table: lesson_objectives
-- Stores individual learning objectives linked to a lesson plan.
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS lesson_objectives (
    id             INT UNSIGNED      NOT NULL AUTO_INCREMENT,
    lesson_plan_id INT UNSIGNED      NOT NULL,
    objective_text TEXT              NOT NULL,
    sort_order     SMALLINT UNSIGNED NOT NULL DEFAULT 0,

    PRIMARY KEY (id),
    CONSTRAINT fk_lesson_objectives_lesson_plan_id
        FOREIGN KEY (lesson_plan_id) REFERENCES lesson_plans (id) ON DELETE CASCADE,
    INDEX idx_lesson_objectives_order (lesson_plan_id, sort_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
