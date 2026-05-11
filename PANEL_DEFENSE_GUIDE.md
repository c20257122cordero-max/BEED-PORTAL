# BEED Student Portal — Panel Defense Guide

---

## OPENING STATEMENT (30–60 seconds)

> *"Good [morning/afternoon], panel. Our capstone project is the **BEED Student Portal** — a web-based system designed specifically for Bachelor of Elementary Education students to create, manage, and export their teaching demonstration plans and detailed lesson plans. The system addresses a real problem: BEED students spend significant time manually formatting these documents, often from scratch, for every practicum requirement. Our portal streamlines that process while ensuring alignment with DepEd standards."*

---

## PART 1: WHAT THE SYSTEM IS

### One-sentence definition
> *"The BEED Student Portal is a secure, web-based application that helps BEED students create structured Demo Plans and Detailed Lesson Plans, organize them by quarter and week, and export them in a print-ready DepEd format."*

### Core tools

| Tool | What it does |
|---|---|
| **Demo Maker** | Creates step-by-step teaching demonstration plans |
| **Lesson Plan Planner** | Creates full DepEd-aligned Detailed Lesson Plans (DLP) |
| **Templates** | Saves and reuses lesson structures |
| **Export** | Generates a printable, submission-ready document |

---

## PART 2: THE PROBLEM IT SOLVES

> *"BEED students face three recurring challenges during practicum:"*

1. **Time** — Writing a full DLP from scratch takes 2–4 hours. Our system reduces that to under 30 minutes using templates and pre-filled structures.
2. **Format** — Students are unsure of the correct DepEd format. Our export follows the standard DLP layout with all required sections.
3. **Organization** — Students lose track of which plans are submitted, which are drafts, and which quarter they belong to. Our system tracks status and organizes by quarter and week.

---

## PART 3: SYSTEM WALKTHROUGH (for live demo)

Walk the panel through this sequence:

### Step 1 — Register and Login
> *"A student registers with their name, email, and password. Passwords are hashed using bcrypt — they are never stored in plain text. After login, the student is taken to their personal dashboard."*

### Step 2 — Dashboard
> *"The dashboard shows their 5 most recent demos and lesson plans, with status badges — Draft, For Review, or Submitted. Quick-action buttons let them create new documents immediately."*

### Step 3 — Create a Demo Plan
> *"In the Demo Maker, the student fills in the Basic Information: title, subject, grade level, quarter, week, duration, and status. Each field has helper text explaining what to enter. The Objectives & Materials section has a Load Template button — the student picks their subject and it pre-fills the learning objectives and materials list. The Lesson Flow section has 4A's and 5E's template buttons that auto-populate the step-by-step procedure."*

### Step 4 — Create a Lesson Plan
> *"The Lesson Plan Planner follows the standard DepEd DLP format: Basic Information, Learning Objectives, Subject Matter, Procedure (Review/Drill, Motivation, Presentation, Discussion, Generalization, Application), Evaluation, and Assignment. Each procedure section has a Load Template button with subject-specific content for Mathematics, English, Filipino, Science, AP, MAPEH, and EsP."*

### Step 5 — Save as Template
> *"Once a student creates a good lesson plan, they can save it as a personal template. Next time, they click 'My Templates', choose their saved template, and all fields are pre-filled. They only need to adjust the specific details."*

### Step 6 — Export
> *"The export generates a formal DepEd-formatted document with the school name, teacher name, grade and section, quarter, week, and date at the top — pulled from the student's profile. Signature lines for the student teacher and cooperating teacher are included at the bottom. The student clicks Print and submits it."*

---

## PART 4: TECHNICAL ARCHITECTURE

> *"The system is built using:"*

| Layer | Technology | Why |
|---|---|---|
| **Backend** | PHP 8 (vanilla, no framework) | Lightweight, runs on any XAMPP/Apache server |
| **Database** | MySQL with PDO | Relational data, foreign keys, cascade deletes |
| **Frontend** | Tailwind CSS | Responsive, mobile-friendly, no build step needed |
| **Security** | bcrypt, prepared statements, session auth | Prevents SQL injection, password exposure, unauthorized access |

> *"The architecture follows a Front Controller + MVC-inspired pattern. A single `index.php` routes all requests to the appropriate controller. Models handle all database operations using PDO prepared statements — never string-interpolated SQL."*

---

## PART 5: SECURITY FEATURES

Be ready to explain these:

- **Password hashing** — `password_hash($password, PASSWORD_BCRYPT)` — passwords are never stored in plain text
- **SQL injection prevention** — all queries use PDO prepared statements with bound parameters
- **Session-based authentication** — every protected page calls `AuthMiddleware::requireAuth()` which checks `$_SESSION['student_id']`
- **Per-student data isolation** — every database query includes `WHERE student_id = :student_id` — a student can never access another student's data
- **Session regeneration** — `session_regenerate_id(true)` is called on login to prevent session fixation attacks

---

## PART 6: DATABASE DESIGN

> *"The database has 7 tables:"*

```
students
  ├── demos              (student_id → students.id, CASCADE DELETE)
  │     └── demo_steps   (demo_id → demos.id, CASCADE DELETE)
  ├── lesson_plans       (student_id → students.id, CASCADE DELETE)
  │     └── lesson_objectives (lesson_plan_id → lesson_plans.id, CASCADE DELETE)
  ├── lesson_plan_templates (student_id → students.id, CASCADE DELETE)
  └── demo_templates     (student_id → students.id, CASCADE DELETE)
```

> *"All child records are automatically deleted when a student account is deleted, thanks to ON DELETE CASCADE foreign key constraints. This ensures no orphaned data remains in the database."*

---

## PART 7: ANTICIPATED PANEL QUESTIONS

### Q: "Why PHP and not a modern framework like Laravel?"
> *"We chose vanilla PHP to keep the system lightweight and deployable on any standard XAMPP setup without additional configuration. BEED students and their schools typically have basic hosting environments. The system follows MVC principles without the overhead of a full framework."*

### Q: "How do you ensure data security?"
> *"Three layers: (1) Authentication — every protected route requires a valid session. (2) Authorization — every database query filters by the logged-in student's ID, so cross-student data access is impossible at the query level. (3) Input sanitization — all user input is processed through PDO prepared statements, and all output is escaped with `htmlspecialchars()` to prevent XSS."*

### Q: "What happens if two students register with the same email?"
> *"The database has a UNIQUE constraint on the email column. If a duplicate is attempted, MySQL throws a SQLSTATE 23000 error, which the controller catches and displays as 'This email address is already registered.' — no duplicate accounts are created."*

### Q: "How is the export formatted?"
> *"The export view renders a standalone HTML page styled to match the standard DepEd DLP format. It includes the school name, teacher name, grade and section, quarter, week, date, and all lesson sections. Navigation and buttons are hidden on print using Tailwind's `print:hidden` utility. The student uses the browser's Print function to save as PDF or print directly."*

### Q: "What if the student loses their work?"
> *"All data is saved to the MySQL database on every form submission. The system also has a Duplicate feature — students can copy any demo or lesson plan with one click, creating a 'Copy of' version they can modify without affecting the original."*

### Q: "Can this be used on mobile?"
> *"Yes. The entire system uses Tailwind CSS with responsive breakpoints. Forms display in a single column on mobile and multi-column on desktop. The navigation has a hamburger menu for small screens."*

### Q: "What are the limitations of the system?"
> *"Currently the system runs locally on XAMPP. For production deployment, it would need a web host with PHP 8 and MySQL. We also do not have real-time collaboration — each student has their own private account. Future improvements could include a cooperating teacher account that can review and approve submitted plans directly in the system."*

### Q: "How does the template system work?"
> *"Students can save any completed lesson plan or demo as a personal template. Templates store all field values including objectives, procedure sections, and materials. When creating a new plan, the student clicks 'My Templates', selects a saved template, and all fields are pre-filled via a JavaScript fetch call to the `/templates/{id}/apply` endpoint. The student then edits only what needs to change."*

### Q: "What technology stack did you use and why?"
> *"PHP 8 for the backend because it is widely supported and runs on XAMPP which is standard in Philippine schools and universities. MySQL for the database because it is relational, supports foreign keys, and integrates seamlessly with PHP via PDO. Tailwind CSS for the frontend because it produces a clean, responsive UI without requiring a build process. We deliberately avoided heavy frameworks to keep the system simple to deploy and maintain."*

### Q: "How did you test the system?"
> *"We used PHPUnit for automated testing. We wrote integration tests for the database schema and cascade delete behavior. We also performed manual testing of all user flows: registration, login, creating and editing demos and lesson plans, searching, exporting, and using templates. All critical paths were verified to work correctly."*

### Q: "What is the difference between a Demo Plan and a Lesson Plan?"
> *"A Demo Plan is a teaching demonstration plan used for practicum observations — it focuses on the step-by-step procedure of a single demonstration lesson. A Detailed Lesson Plan (DLP) is the full DepEd-standard document that includes all procedure sections (Review/Drill, Motivation, Presentation, Discussion, Generalization, Application), subject matter references, learning objectives, evaluation, and assignment. Both are required documents for BEED students during their practicum."*

---

## PART 8: CLOSING STATEMENT

> *"In summary, the BEED Student Portal directly addresses the time, format, and organization challenges that BEED students face during practicum. It is secure, aligned with DepEd standards, and designed to be practical for students with varying levels of technical experience. We believe this system can genuinely reduce the burden of documentation and allow BEED students to focus more on the quality of their teaching rather than the paperwork. We are open to any questions."*

---

## QUICK REFERENCE CARD

| Feature | URL to show |
|---|---|
| Login / Register | `/login`, `/register` |
| Dashboard with status badges | `/dashboard` |
| Demo Maker with templates | `/demos/create` |
| Lesson Plan with procedure templates | `/lesson-plans/create` |
| My Templates page | `/templates` |
| Export (print-ready) | Any demo/plan → Export button |
| Profile (auto-fills export) | `/profile` |
| Demo Templates | `/demo-templates` |

---

## KEY TERMS TO KNOW

| Term | Meaning |
|---|---|
| **DLP** | Detailed Lesson Plan — the standard DepEd lesson plan format |
| **Demo Plan** | Teaching demonstration plan used for practicum observations |
| **PDO** | PHP Data Objects — PHP's database abstraction layer |
| **bcrypt** | A password hashing algorithm — secure, slow by design |
| **Prepared statements** | SQL queries with placeholders — prevents SQL injection |
| **Session** | Server-side storage of login state — `$_SESSION['student_id']` |
| **CASCADE DELETE** | Database rule that auto-deletes child records when parent is deleted |
| **MVC** | Model-View-Controller — architectural pattern separating data, logic, and display |
| **Tailwind CSS** | Utility-first CSS framework for responsive design |
| **FULLTEXT index** | MySQL index that enables fast keyword search across text columns |
