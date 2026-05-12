# ACCOMPLISHMENT REPORT
## BEED Student Portal — Capstone Project

---

## GROUP LEADER & MEMBERS

| Role | Name |
|---|---|
| Group Leader | *(fill in)* |
| Member | *(fill in)* |
| Member | *(fill in)* |
| Member | *(fill in)* |

---

## TAGLINE

> **"Plan Smart. Teach Better."**

*Alternative taglines:*
- *"Your DepEd-Ready Teaching Toolkit"*
- *"From Draft to Demo — All in One Place"*
- *"Built for BEED. Designed for the Classroom."*

---

## COURSE

**Bachelor of Elementary Education (BEED)**
Specialization: General Education / Early Childhood Education

**Subject:** Capstone Project / Systems Development
**School Year:** 2025–2026

---

## SCHEDULE / PLAN

| Phase | Activity | Target Date | Status |
|---|---|---|---|
| **Phase 1** | Project Proposal & Title Approval | Week 1–2 | ✅ Done |
| **Phase 2** | Requirements Gathering & Analysis | Week 3–4 | ✅ Done |
| **Phase 3** | System Design (Wireframes, ERD, Architecture) | Week 5–6 | ✅ Done |
| **Phase 4** | Database Design & Schema Creation | Week 7 | ✅ Done |
| **Phase 5** | Backend Development (Auth, Models, Controllers) | Week 8–10 | ✅ Done |
| **Phase 6** | Frontend Development (Views, UI, Tailwind CSS) | Week 11–12 | ✅ Done |
| **Phase 7** | Feature Implementation (Templates, Export, Profile) | Week 13–14 | ✅ Done |
| **Phase 8** | Testing & Bug Fixing | Week 15 | ✅ Done |
| **Phase 9** | Documentation & Panel Defense Preparation | Week 16 | ✅ Done |
| **Phase 10** | Final Presentation / Panel Defense | *(defense date)* | 🔄 Ongoing |

---

## GUIDE / PLAN

### Project Overview
The **BEED Student Portal** is a web-based system that helps Bachelor of Elementary Education (BEED) students create, manage, and export their teaching demonstration plans and detailed lesson plans (DLP) in a DepEd-aligned format.

---

### Problem Statement
BEED students spend 2–4 hours manually writing a single Detailed Lesson Plan from scratch. They struggle with:
1. Correct DepEd format and structure
2. Organizing plans by quarter, week, and subject
3. Tracking which plans are submitted or still in draft
4. Reusing content across similar lessons

---

### Objectives

**General Objective:**
To develop a web-based portal that streamlines the creation, management, and export of teaching demonstration plans and detailed lesson plans for BEED students.

**Specific Objectives:**
1. To provide a structured Demo Maker tool for creating step-by-step teaching demonstration plans
2. To provide a Lesson Plan Planner tool following the standard DepEd DLP format
3. To implement a template system that allows students to save and reuse lesson structures
4. To generate print-ready, DepEd-formatted export documents
5. To implement secure user authentication with per-student data isolation
6. To organize plans by quarter, week, subject, and status (Draft / For Review / Submitted)

---

### Scope and Limitations

**Scope:**
- User registration and login (BEED students only)
- Demo Plan creation, editing, searching, and export
- Detailed Lesson Plan creation, editing, searching, and export
- Personal template management (save and reuse)
- Student profile (school name, section, cooperating teacher)
- Status tracking (Draft, For Review, Submitted)
- Responsive design (desktop and mobile)

**Limitations:**
- The system runs locally on XAMPP (not yet deployed to a live server)
- No real-time collaboration between students
- No cooperating teacher account for direct approval
- No automated DepEd curriculum guide integration

---

### Technology Stack

| Component | Technology |
|---|---|
| Backend | PHP 8 (vanilla, no framework) |
| Database | MySQL / MariaDB |
| Frontend | Tailwind CSS |
| Server | Apache (XAMPP) |
| Version Control | Git / GitHub |

---

### System Features

| Feature | Description |
|---|---|
| **Authentication** | Secure login/register with bcrypt password hashing |
| **Dashboard** | Overview of recent demos and lesson plans with status badges |
| **Demo Maker** | Create structured teaching demonstration plans with step-by-step procedure |
| **Lesson Plan Planner** | Full DepEd DLP format with all required sections |
| **Templates** | Save and reuse lesson plan and demo structures |
| **Export** | Print-ready DepEd-formatted document with school header and signature lines |
| **Profile** | Student profile auto-fills export documents |
| **Status Tracking** | Draft → For Review → Submitted workflow |
| **Search** | Full-text search across demos and lesson plans |
| **Duplicate** | Copy any demo or lesson plan with one click |

---

### Development Methodology
**Agile / Iterative Development**
- Requirements were gathered first, then design, then implementation
- Features were built and tested incrementally
- Feedback was incorporated at each phase

---

### Expected Output
A fully functional web-based BEED Student Portal accessible at:
`http://localhost/DEMO MAKER AND LESSON PLAN MAKER/login`

GitHub Repository:
`https://github.com/c20257122cordero-max/BEED-PORTAL`

---

### Accomplishments to Date

- [x] Project proposal approved
- [x] System requirements documented
- [x] Database schema designed and implemented (7 tables)
- [x] User authentication system (register, login, logout)
- [x] Demo Maker — full CRUD with step management
- [x] Lesson Plan Planner — full CRUD with DepEd format
- [x] Template system (save, load, manage)
- [x] DepEd-formatted export with school header and signature lines
- [x] Student profile page
- [x] Status tracking (Draft / For Review / Submitted)
- [x] Search and filter functionality
- [x] Duplicate feature
- [x] Responsive UI (desktop and mobile)
- [x] Subject-specific procedure templates (Math, English, Filipino, Science, AP, MAPEH, EsP)
- [x] Panel defense guide prepared
- [x] Code pushed to GitHub

---

*Document prepared by: ________________*
*Date: ________________*
