-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: beed_portal
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `demo_steps`
--

DROP TABLE IF EXISTS `demo_steps`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `demo_steps` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `demo_id` int(10) unsigned NOT NULL,
  `step_number` smallint(5) unsigned NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_demo_steps_order` (`demo_id`,`step_number`),
  CONSTRAINT `fk_demo_steps_demo_id` FOREIGN KEY (`demo_id`) REFERENCES `demos` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `demo_steps`
--

LOCK TABLES `demo_steps` WRITE;
/*!40000 ALTER TABLE `demo_steps` DISABLE KEYS */;
/*!40000 ALTER TABLE `demo_steps` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `demo_templates`
--

DROP TABLE IF EXISTS `demo_templates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `demo_templates` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `student_id` int(10) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(500) DEFAULT NULL,
  `subject_tpl` varchar(255) DEFAULT NULL,
  `grade_level_tpl` varchar(50) DEFAULT NULL,
  `duration_minutes_tpl` smallint(5) unsigned DEFAULT NULL,
  `learning_objectives_tpl` text DEFAULT NULL,
  `materials_needed_tpl` text DEFAULT NULL,
  `introduction_tpl` text DEFAULT NULL,
  `generalization_tpl` text DEFAULT NULL,
  `application_tpl` text DEFAULT NULL,
  `assessment_tpl` text DEFAULT NULL,
  `steps_tpl` text DEFAULT NULL COMMENT 'JSON array of step strings',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_dt_student` (`student_id`),
  CONSTRAINT `fk_dt_student_id` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `demo_templates`
--

LOCK TABLES `demo_templates` WRITE;
/*!40000 ALTER TABLE `demo_templates` DISABLE KEYS */;
/*!40000 ALTER TABLE `demo_templates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `demos`
--

DROP TABLE IF EXISTS `demos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `demos` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `student_id` int(10) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `grade_level` varchar(50) DEFAULT NULL,
  `quarter` tinyint(3) unsigned DEFAULT NULL COMMENT '1-4',
  `week` tinyint(3) unsigned DEFAULT NULL COMMENT '1-10',
  `status` enum('draft','for_review','submitted') NOT NULL DEFAULT 'draft',
  `duration_minutes` smallint(5) unsigned DEFAULT NULL,
  `learning_objectives` text NOT NULL,
  `materials_needed` text DEFAULT NULL,
  `introduction` text DEFAULT NULL,
  `generalization` text DEFAULT NULL,
  `application` text DEFAULT NULL,
  `assessment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_demos_student_id` (`student_id`),
  KEY `idx_demos_title` (`title`),
  FULLTEXT KEY `ft_demo_search` (`title`,`subject`),
  CONSTRAINT `fk_demos_student_id` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `demos`
--

LOCK TABLES `demos` WRITE;
/*!40000 ALTER TABLE `demos` DISABLE KEYS */;
/*!40000 ALTER TABLE `demos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lesson_objectives`
--

DROP TABLE IF EXISTS `lesson_objectives`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lesson_objectives` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `lesson_plan_id` int(10) unsigned NOT NULL,
  `objective_text` text NOT NULL,
  `sort_order` smallint(5) unsigned NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `idx_lesson_objectives_order` (`lesson_plan_id`,`sort_order`),
  CONSTRAINT `fk_lesson_objectives_lesson_plan_id` FOREIGN KEY (`lesson_plan_id`) REFERENCES `lesson_plans` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lesson_objectives`
--

LOCK TABLES `lesson_objectives` WRITE;
/*!40000 ALTER TABLE `lesson_objectives` DISABLE KEYS */;
/*!40000 ALTER TABLE `lesson_objectives` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lesson_plan_templates`
--

DROP TABLE IF EXISTS `lesson_plan_templates`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lesson_plan_templates` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `student_id` int(10) unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(500) DEFAULT NULL,
  `subject_tpl` varchar(255) DEFAULT NULL,
  `grade_level_tpl` varchar(50) DEFAULT NULL,
  `time_allotment_tpl` smallint(5) unsigned DEFAULT NULL,
  `learning_competency_tpl` varchar(500) DEFAULT NULL,
  `subject_matter_topic_tpl` varchar(255) DEFAULT NULL,
  `subject_matter_references_tpl` text DEFAULT NULL,
  `subject_matter_materials_tpl` text DEFAULT NULL,
  `objectives_tpl` text DEFAULT NULL COMMENT 'JSON array of objective strings',
  `proc_review_drill_tpl` text DEFAULT NULL,
  `proc_motivation_tpl` text DEFAULT NULL,
  `proc_presentation_tpl` text DEFAULT NULL,
  `proc_discussion_tpl` text DEFAULT NULL,
  `proc_generalization_tpl` text DEFAULT NULL,
  `proc_application_tpl` text DEFAULT NULL,
  `evaluation_tpl` text DEFAULT NULL,
  `assignment_tpl` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_lpt_student` (`student_id`),
  CONSTRAINT `fk_lpt_student_id` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lesson_plan_templates`
--

LOCK TABLES `lesson_plan_templates` WRITE;
/*!40000 ALTER TABLE `lesson_plan_templates` DISABLE KEYS */;
INSERT INTO `lesson_plan_templates` VALUES (1,1,'SAMPLE TEMPLATE','','','',NULL,'','','','',NULL,'','','','','','','','','2026-05-08 12:50:09','2026-05-08 12:50:09');
/*!40000 ALTER TABLE `lesson_plan_templates` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `lesson_plans`
--

DROP TABLE IF EXISTS `lesson_plans`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `lesson_plans` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `student_id` int(10) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `grade_level` varchar(50) DEFAULT NULL,
  `quarter` tinyint(3) unsigned DEFAULT NULL COMMENT '1-4',
  `week` tinyint(3) unsigned DEFAULT NULL COMMENT '1-10',
  `status` enum('draft','for_review','submitted') NOT NULL DEFAULT 'draft',
  `date` date DEFAULT NULL,
  `time_allotment_minutes` smallint(5) unsigned DEFAULT NULL,
  `learning_competency` varchar(500) NOT NULL,
  `subject_matter_topic` varchar(255) DEFAULT NULL,
  `subject_matter_references` text DEFAULT NULL,
  `subject_matter_materials` text DEFAULT NULL,
  `proc_review_drill` text DEFAULT NULL,
  `proc_motivation` text DEFAULT NULL,
  `proc_presentation` text DEFAULT NULL,
  `proc_discussion` text DEFAULT NULL,
  `proc_generalization` text DEFAULT NULL,
  `proc_application` text DEFAULT NULL,
  `evaluation` text DEFAULT NULL,
  `assignment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_lesson_plans_student_id` (`student_id`),
  KEY `idx_lesson_plans_title` (`title`),
  FULLTEXT KEY `ft_lp_search` (`title`,`subject`,`learning_competency`),
  CONSTRAINT `fk_lesson_plans_student_id` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `lesson_plans`
--

LOCK TABLES `lesson_plans` WRITE;
/*!40000 ALTER TABLE `lesson_plans` DISABLE KEYS */;
INSERT INTO `lesson_plans` VALUES (2,1,'Copy of Understanding Fractions Less Than One','Mathematics','Grade 2',NULL,NULL,'draft','2026-09-18',20,'Perform basic operations on whole numbers.  Addition, subtraction, multiplication, and division','Fractions Less Than One with Denominators 2, 3, 4, 5, 6, 8, and 10','Mathematics 3 LearnerΓÇÖs Material, pp. 68ΓÇô72\r\nK to 12 Curriculum Guide in Mathematics (Grade 3)\r\nTeacherΓÇÖs Guide in Mathematics 3, pp. 45ΓÇô50','Activity worksheets','Show flash cards with fractions like:\r\n\r\n2\r\n1\r\n	ΓÇï\r\n\r\n,\r\n3\r\n1\r\n	ΓÇï\r\n\r\n,\r\n4\r\n1\r\n	ΓÇï','Show a picture of a cake divided into 8 equal slices. Ask:\r\n\r\nΓÇ£If you eat 3 slices, what part of the cake did you eat?ΓÇ¥','the numerator tells the number of parts considered\r\nthe denominator tells the total equal partsΓÇï','What does the numerator tell us?\r\nWhat does the denominator tell us?','A fraction represents equal parts of a whole. The numerator tells the number of parts taken while the denominator tells the total equal parts.','Group the pupils and let them arrange fractions on a number line.','Shade the correct fraction in figures.\r\nIdentify numerator and denominator.\r\nAnswer simple fraction problems.','Draw 3 objects divided into equal parts and write the fraction for the shaded part.','2026-05-08 12:49:57','2026-05-08 12:49:57');
/*!40000 ALTER TABLE `lesson_plans` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `students`
--

DROP TABLE IF EXISTS `students`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `students` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `full_name` varchar(255) NOT NULL,
  `school_name` varchar(255) DEFAULT NULL,
  `section` varchar(100) DEFAULT NULL,
  `year_level` varchar(50) DEFAULT NULL,
  `cooperating_teacher` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_students_email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `students`
--

LOCK TABLES `students` WRITE;
/*!40000 ALTER TABLE `students` DISABLE KEYS */;
INSERT INTO `students` VALUES (1,'John Myrho Cordero',NULL,NULL,NULL,NULL,'c20257122.cordero@csav.edu.ph','$2y$10$4axUd/3d6OrQ/QConvglzu.yxwtLokuxfCE4zvP4avZNxPsddSP/2','2026-05-08 10:33:55');
/*!40000 ALTER TABLE `students` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-05-11  9:26:11
