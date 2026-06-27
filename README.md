# 🎓 Deela LMS: Comprehensive Academic Learning Management System

[![Laravel Version](https://img.shields.io/badge/Laravel-13.1.1-red.svg)](https://laravel.com)
[![PHP Version](https://img.shields.io/badge/PHP-%3E%3D_8.5.0-blue.svg)](https://php.net)
[![CustomTkinter](https://img.shields.io/badge/Desktop_GUI-CustomTkinter-orange.svg)](https://github.com/tomschimansky/CustomTkinter)
[![Database](https://img.shields.io/badge/Database-MySQL_/_TiDB_Cloud-teal.svg)](https://tidbcloud.com)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

**Deela LMS (BAPS-e-Learning)** is a premium, high-fidelity Learning Management System designed to streamline academic operations, curriculum distribution, time-table scheduling, student enrollment validation, evaluations, and bulk credentials certification. 

Equipped with an advanced **Python Desktop Control Center** (built on CustomTkinter), the application offers a unified developer & operator launchpad supporting local offline environments (XAMPP) as well as direct remote internet cloud services (TiDB Cloud) with built-in secure public tunnels.

---

## 🚀 Core Features Matrix

### 1. Hybrid Authentication & 5-Digit Passcodes
- **12-Point Biodata Check-in**: The `/register` portal captures key metrics (Enrollment ID, ABC ID, Blood Group, Aadhar number, Guardian info, etc.).
- **Verification Queue**: Registrations default to a strict `pending` state blocking unauthorized access.
- **Passcode Allocation**: HOD/Admin approvals automatically generate a secure, encrypted **5-digit login passcode**, replacing weak traditional passwords.
- **Insta-Approval Checkpoints**: Authorized personnel (CRs, Faculty, HODs) submitting registrations instantly bypass the queue with immediate passcode generation.

### 2. Modern Interactive Curriculum Builder
- **Multimedia Delivery**: Direct upload support for raw `.mp4` video lectures (with server-side validation & size limits), integrated YouTube streams, and downloadable PDF academic resources.
- **Progress Tracking Engine**: Seamless completion matrix tracking (`Completed Lessons == Total Lessons`) transitioning student progress status dynamically.

### 3. Smart Timetable merging
- **Consecutive Hour Detection**: The builder calculates timetables cell-by-cell. For longer classes (like consecutive 2+ hour labs), the system calculates coordinates and uses HTML `rowspan` to merge blocks visually on a dynamic student grid.

### 4. Interactive Quiz & Auto-Certification Engine
- **Relational Assessment Arrays**: Evaluates student knowledge using custom multi-choice questionnaires connected dynamically to course nodes.
- **Auto-Grade Evaluation**: Grade attempts server-side against correct answer keys.
- **Instant Certificate Issuance**: Generates A4 landscape academic diplomas dynamically with Barryvdh DomPDF on passing grades. Includes a unique verification stamp (e.g. `MAN-XYZ789`) to prevent forgery.
- **1-Click CR Issuance**: Empowers Class Representatives (CRs) to issue pending certificates with one click once progress reaches 100%.
- **Bulk Certificate Bundler**: Simplifies graduation management by looping over entire cohorts, handling blank-email placeholders, and outputting a single merged PDF document with programmatic page breaks.

---

## 🖥️ Python LMS Control Center Launcher

The desktop app [run_app.py](run_app.py) provides a modern dashboard for running the LMS locally and configuring connections:

- **Launch Mode 1: With XAMPP (Local Offline)** — Launches local Apache Web Server and MySQL database, routing the application to `http://localhost/deela/public/`.
- **Launch Mode 2: Without Apache (Artisan + Local MySQL)** — Shuts down Apache and runs the built-in PHP development server (`php artisan serve`) mapping local MySQL.
- **Launch Mode 3: Online Cloud Mode (Artisan - No XAMPP)** — Bypasses all local server binaries. Runs the Artisan server connecting directly to the **TiDB Cloud Database** (configured in `.env`). Great for developer testing without local database stacks.
- **Sleek CustomTkinter UI**: Features rounded cards, high-contrast console log terminals, and a live light/dark appearance theme switcher.
- **Customized Public Sharing**: Integrated Ngrok tunnel launching supporting customized subdomain configurations.

---

## 📶 Customized Public Tunnel (Ngrok)
The Laravel built-in dev server has been extended via a custom command `php artisan serve` to spin up a public secure tunnel dynamically:
- **Custom Domain**: Configured to serve the portal on the custom address: **`baps-lms-seva.ngrok-free.app`**
- **Configuration**: Easily configurable via the `NGROK_DOMAIN` environment variable in your `.env`.

---

## 🛠️ Technology Stack

*   **Backend Framework**: Laravel 13.1.1 (PHP >= 8.5.0)
*   **Database**: Dual support for Local MySQL (XAMPP) & TiDB Cloud (AWS Cloud Relational MySQL Gateway)
*   **Frontend UI Engine**: Custom CSS Glassmorphism, Bootstrap 5.3, jQuery 3.7
*   **PDF Compiler**: Barryvdh Laravel DomPDF
*   **Desktop App Frame**: Python 3.12, CustomTkinter 6.0, PyWebview (with standard Tkinter UI fallback)

---

## 📦 Local Installation & Setup

1.  **Clone the Repository**:
    ```bash
    git clone https://github.com/PATEL-BHAVIK2306005/PROJECT-BAPS-LMS.git
    cd PROJECT-BAPS-LMS
    ```

2.  **Environment Setup**:
    - Copy `.env.example` to `.env`
    - Configure your TiDB Cloud database coordinates or local MySQL credentials.
    - Set the customized public domain:
      ```ini
      NGROK_DOMAIN=baps-lms-seva.ngrok-free.app
      ```

3.  **Install PHP & JS Dependencies**:
    ```bash
    composer install
    npm install && npm run dev
    ```

4.  **Setup Keys and DB**:
    ```bash
    php artisan key:generate
    php artisan migrate
    ```

5.  **Run the Desktop Launcher**:
    Ensure `customtkinter` is installed:
    ```bash
    pip install customtkinter pillow
    ```
    Launch the GUI Control Center:
    ```bash
    python run_app.py --gui
    ```

---

## 🔒 Production Deployments
When hosting the application live on a VPS or cloud service:
- Set `APP_DEBUG=false` in your `.env`.
- Point the virtual host document root directly to the `/public` directory.
- Run optimizations:
  ```bash
  php artisan config:cache
  php artisan route:cache
  php artisan view:cache
  ```
