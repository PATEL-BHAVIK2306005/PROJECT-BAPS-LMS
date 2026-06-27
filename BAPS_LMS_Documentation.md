# BAPS-e-learning-LMS: Comprehensive System Documentation (Phases 1.0 - 3.4)

**Version:** 3.4.0  
**Stack:** PHP 8.5.0, Laravel 13.1.1, MySQL (XAMPP), Bootstrap 5.3, jQuery 3.7.  
**Design System:** Custom CSS Glassmorphism & High-Fidelity Interactive UI.

---

## Chronological Release Modules

### Phase 1: Core Foundation & Identity (V1.0)
The absolute framework of the system.
*   **Laravel 13 Scaffold:** Initialized the baseline architecture with Bootstrap 5.3 & jQuery 3.7.
*   **Role-Based Access Control (RBAC):** Established the base auth array and the core fractional `RoleMiddleware` engine protecting restricted routes based on hierarchy levels (Admin, Dean, HOD, Faculty, Student).
*   **Database Initialisations:** Generated critical foundational tables (`users`, `departments`, `staff`).
*   **Profile Module:** Basic unauthenticated/authenticated session tracking matrices.

### Phase 2: Academic Pillars & Content Delivery (V2.0)
Establishing the educational backbone, replacing the blank architecture with interactive courses.
*   **Department & Staff Registry:** Admins and Deans enabled to create academic departments and distribute secure Staff credentials.
*   **Course Builder System:** Faculties empowered to create courses mapped to specific Programs (Bachelors, Masters, PhD) and specific calendar Semesters.
*   **Multimedia Curriculum:** Implemented upload limits and validation for raw `.mp4` video files, mapped external YouTube URLs, and integrated downloadable PDF textbook resources.
*   **Evaluations Base:** Added modular Quizzes mapping pass-/fail thresholds to the Progress Tracker.
*   **Basic Enrollments:** Administrative enrollment capabilities for adding students to courses.

### Phase 3.0: Premium UI, Analytics & Certification Base (V3.0)
The platform undergoes a massive transition towards user experience and digital credentials.
*   **Glassmorphism Engine:** Integrated backdrop-filter CSS blurs, floating interactive shadows, and dynamic hover animations to completely revamp the visual identity into a premium academic space.
*   **Demo Student Mode:** Built an impersonation engine. Admins can click a button to hijack a student's token, view the exact dashboard through their eyes, and securely exit without session corruption.
*   **Base Certification Engine:** Implemented `barryvdh/laravel-dompdf`. The system now dynamically generates beautiful A4 Landscape BAPS-branded diplomas containing unique verification tags (e.g. `MAN-XYZ789`).
*   **Course Ratings & Announcements:** Students can score 1-5 stars; Faculty can push mass notifications directly to enrolled classes.

### Phase 3.02: Extended Hierarchy & Class Management (V3.02)
*   **Class Representative (CR) Role:** Created a new dynamic role (Level 1.5) possessing hybrid student/staff capabilities. Designed to assist Faculty by bridging communication and administrative tasks.
*   **Attendance Registry Engine:** Deployed the `Attendances` database arrays. Faculty & CRs can access interactive digital rosters to bulk-mark Present/Absent flags for active courses on distinct dates.
*   **Timetables MVP:** Base migrations linking PDF schedule layouts to classes.

### Phase 3.03: The Intelligent Timetable Builder (V3.03)
*   **Manual Mapping Engine:** Admins can construct timetables cell-by-cell (mapping specific days to specific times and courses).
*   **Dynamic Visual Viewer:** Programmed a smart Blade loop. If a student has a "Lab" that spans multiple consecutive time-blocks (e.g., 2+ hours), the frontend calculates it seamlessly and uses HTML `rowspan` to merge the scheduling visually on their interactive grid.

### Phase 3.04: Dashboard Revamps (V3.04)
*   Refactored the static, boring visual dashboard bars inside `student/dashboard.blade.php`.
*   Mapped live analytic counts securely.

### Phase 3.05: Smart Administrative Control (V3.05)
*   Disconnected the auto-randomised 8-digit enrollment generation when administrators add users manually. 
*   Allowed exact manual typing of Enrollment IDs directly onto the admin configuration to match physical university ledgers.

### Phase 3.2: Secure Passcode Matrix & Approvals (V3.2)
Revolutionised the entire entry flow, discarding traditional passwords.
*   **12-Point Public Check-in:** The `/register` portal was rebuilt to capture 12 critical bio-metrics (Aadhar, Blood Group, ABC ID, Guardian Name, Semester).
*   **Pending Queue Ecosystem:** Users default to a strict `pending` status logic blocking them from logging in.
*   **Insta-Approval Engine:** If a CR/Faculty/HOD triggers the registration, it instantly clears the queue and visually displays their credentials.
*   **5-Digit Passcodes:** When an official approves a pending status via the shiny new `/admin/approvals` queue, an internal encrypted 5-digit code is mapped to that user.
*   **Hardened Portal:** The `/login` form only accepts authorized 5-digit sequences.

### Phase 3.3: Master Certificate Bundling (V3.3)
Addressed the massive administrative drag of downloading individual diplomas for large graduating cohorts.
*   **Bulk Generation HUD:** Embed a Course-Selection UI at the top of the Enrollment index.
*   **Continuous PDF Array Loops:** The system fetches every enrolled individual, auto-creates blind dummy identities if their email is blank, loops them over DomPDF mapping `page-break-after: always;` and discharges one massive Master PDF bundle to the Faculty/CR.

### Phase 3.4: Dynamic Progress Tracking Enhancements (V3.4)
Fixed analytical bugs in the visual administrative tracker ensuring precise cohort overview.
*   **True Completion Ratios:** Removed hardcoded demo variables inside the arrays. Evaluating accurate mapping of `Completed Lessons == Total Lessons`.
*   **Status Color Indexing:** Automatically transitions the progress flag into "Completed" rather than "In Progress".
*   **1-Click CR Issuance Module:** The moment the system evaluates "Completed", a targeted "Issue Cert" button spawns exclusively underneath that user's name, allowing the CR an effortless 1-click credential deployment point.

---
*(Documentation accurately captures system footprint up to v3.4.0)*

## Phase 3.5 & 3.6 Updates: Advanced Qualification & Auto-Certification Engine
- **Relational Quiz Arrays:** Integrated `questions`, `options`, and `quiz_attempts` structures dynamically linking into a primary `Quiz` node.
- **Administrative Faculty Builder:** Authorized Instructors can navigate via the central Course Hub to generate specialized multi-choice question arrays linked to distinct point values and a passing `min_score`.
- **Student Kiosks:** Appended an interactive glassmorphic UI onto the existing `course.blade` enabling logged authorizations to interact dynamically with the test grid.
- **Automated Grading Engine:** Deployed server-side grading evaluation mapped seamlessly against the `is_correct` binary option tables.
- **Auto-Certification Deployment:** Directly integrated instantaneous Certificate creation upon successful Quiz evaluations (`$passed >= $quiz->min_score`), allowing automatic `PDF` downloads immediately succeeding test submission blockages.
