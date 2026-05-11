-- =============================================================================
-- BEED Student Portal — Database Schema (v4)
-- =============================================================================
-- Run this script to initialise a fresh database with all tables.
-- All statements use IF NOT EXISTS / IF NOT EXISTS guards for safe re-runs.
-- =============================================================================

CREATE DATABASE IF NOT EXISTS beed_portal
    DEFAULT CHARACTER SET utf8mb4
    DEFAULT COLLATE utf8mb4_unicode_ci;

USE beed_portal;

-- -----------------------------------------------------------------------------
-- Table: students
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS students (
    id                   INT UNSIGNED  NOT NULL AUTO_INCREMENT,
    full_name            VARCHAR(255)  NOT NULL,
    school_name          VARCHAR(255)  NULL,
    section              VARCHAR(100)  NULL,
    year_level           VARCHAR(50)   NULL,
    cooperating_teacher  VARCHAR(255)  NULL,
    email                VARCHAR(255)  NOT NULL,
    password_hash        VARCHAR(255)  NOT NULL,
    created_at           TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,

    PRIMARY KEY (id),
    UNIQUE KEY uq_students_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------------------------
-- Table: demos
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS demos (
    id                  INT UNSIGNED      NOT NULL AUTO_INCREMENT,
    student_id          INT UNSIGNED      NOT NULL,
    title               VARCHAR(255)      NOT NULL,
    subject             VARCHAR(255)      NULL,
    grade_level         VARCHAR(50)       NULL,
    quarter             TINYINT UNSIGNED  NULL COMMENT '1-4',
    week                TINYINT UNSIGNED  NULL COMMENT '1-10',
    status              ENUM('draft','for_review','submitted') NOT NULL DEFAULT 'draft',
    duration_minutes    SMALLINT UNSIGNED NULL,
    learning_objectives TEXT              NOT NULL,
    materials_needed    TEXT              NULL,
    introduction        TEXT              NULL,
    generalization      TEXT              NULL,
    application         TEXT              NULL,
    assessment          TEXT              NULL,
    created_at          TIMESTAMP         NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at          TIMESTAMP         NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (id),
    CONSTRAINT fk_demos_student_id
        FOREIGN KEY (student_id) REFERENCES students (id) ON DELETE CASCADE,
    INDEX idx_demos_title (title)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE demos ADD FULLTEXT INDEX ft_demo_search (title, subject);

-- -----------------------------------------------------------------------------
-- Table: demo_steps
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS demo_steps (
    id          INT UNSIGNED      NOT NULL AUTO_INCREMENT,
    demo_id     INT UNSIGNED      NOT NULL,
    step_number SMALLINT UNSIGNED NOT NULL,
    description TEXT              NOT NULL,

    PRIMARY KEY (id),
    CONSTRAINT fk_demo_steps_demo_id
        FOREIGN KEY (demo_id) REFERENCES demos (id) ON DELETE CASCADE,
    INDEX idx_demo_steps_order (demo_id, step_number)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------------------------
-- Table: lesson_plans
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS lesson_plans (
    id                       INT UNSIGNED      NOT NULL AUTO_INCREMENT,
    student_id               INT UNSIGNED      NOT NULL,
    title                    VARCHAR(255)      NOT NULL,
    subject                  VARCHAR(255)      NULL,
    grade_level              VARCHAR(50)       NULL,
    quarter                  TINYINT UNSIGNED  NULL COMMENT '1-4',
    week                     TINYINT UNSIGNED  NULL COMMENT '1-10',
    status                   ENUM('draft','for_review','submitted') NOT NULL DEFAULT 'draft',
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

ALTER TABLE lesson_plans ADD FULLTEXT INDEX ft_lp_search (title, subject, learning_competency);

-- -----------------------------------------------------------------------------
-- Table: lesson_objectives
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

-- -----------------------------------------------------------------------------
-- Table: lesson_plan_templates
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS lesson_plan_templates (
    id          INT UNSIGNED NOT NULL AUTO_INCREMENT,
    student_id  INT UNSIGNED NOT NULL,
    name        VARCHAR(255) NOT NULL,
    description VARCHAR(500) NULL,
    subject_tpl                     VARCHAR(255)      NULL,
    grade_level_tpl                 VARCHAR(50)       NULL,
    time_allotment_tpl              SMALLINT UNSIGNED NULL,
    learning_competency_tpl         VARCHAR(500)      NULL,
    subject_matter_topic_tpl        VARCHAR(255)      NULL,
    subject_matter_references_tpl   TEXT              NULL,
    subject_matter_materials_tpl    TEXT              NULL,
    objectives_tpl                  TEXT              NULL COMMENT 'JSON array of objective strings',
    proc_review_drill_tpl           TEXT              NULL,
    proc_motivation_tpl             TEXT              NULL,
    proc_presentation_tpl           TEXT              NULL,
    proc_discussion_tpl             TEXT              NULL,
    proc_generalization_tpl         TEXT              NULL,
    proc_application_tpl            TEXT              NULL,
    evaluation_tpl                  TEXT              NULL,
    assignment_tpl                  TEXT              NULL,
    created_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (id),
    CONSTRAINT fk_lpt_student_id FOREIGN KEY (student_id) REFERENCES students (id) ON DELETE CASCADE,
    INDEX idx_lpt_student (student_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- -----------------------------------------------------------------------------
-- Table: demo_templates
-- -----------------------------------------------------------------------------
CREATE TABLE IF NOT EXISTS demo_templates (
    id          INT UNSIGNED NOT NULL AUTO_INCREMENT,
    student_id  INT UNSIGNED NOT NULL,
    name        VARCHAR(255) NOT NULL,
    description VARCHAR(500) NULL,
    subject_tpl             VARCHAR(255)      NULL,
    grade_level_tpl         VARCHAR(50)       NULL,
    duration_minutes_tpl    SMALLINT UNSIGNED NULL,
    learning_objectives_tpl TEXT              NULL,
    materials_needed_tpl    TEXT              NULL,
    introduction_tpl        TEXT              NULL,
    generalization_tpl      TEXT              NULL,
    application_tpl         TEXT              NULL,
    assessment_tpl          TEXT              NULL,
    steps_tpl               TEXT              NULL COMMENT 'JSON array of step strings',
    created_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    PRIMARY KEY (id),
    CONSTRAINT fk_dt_student_id FOREIGN KEY (student_id) REFERENCES students (id) ON DELETE CASCADE,
    INDEX idx_dt_student (student_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
