# School Management System (APSA-SMS)

A comprehensive, web-based School Management System designed to streamline administrative tasks, manage student records, and facilitate academic reporting.

## Features

### 🎓 Academic Management
- **Academic Years & Terms**: Flexible structure for managing academic calendars.
- **Class & Subject Management**: Organize classes and assign subjects efficiently.
- **Grading & Assessments**: Robust system for recording marks and generating grade reports.
- **Promotion System**: Automated and manual student promotion workflows.

### 👥 User Management
- **Role-Based Access Control (RBAC)**: Secure access for Admins, Staff, and Students.
- **Student Profiles**: Detailed records including bio-data, admission details, and academic history.
- **Staff Management**: Manage teaching and non-teaching staff records.

### 💰 Finance Module
- **Fee Management**: Create fee structures and track student payments.
- **Billing**: Generate bills and manage financial records.
- **Reporting**: Financial summaries and payment tracking.

### 📊 Reporting & Analytics
- **Report Cards**: Automated generation of student report cards with ranking and remarks.
- **Analytics**: Visual dashboards for student performance and school metrics.
- **Export Options**: Download reports in PDF and other formats.

### 🛠 System Features
- **Audit Logs**: Track system usage and critical actions.
- **Backup Manager**: Database backup and restore functionality.
- **Responsive UI**: Modern, glassmorphism-inspired interface optimized for desktop and mobile.

## Installation

1.  **Clone the Repository**
    ```bash
    git clone [repository-url]
    ```

2.  **Database Setup**
    - Import the provided SQL schema into your MySQL database.
    - Configure the database connection in `app/Config/Database.php`.

3.  **Configuration**
    - Update base URL and path settings in `app/Config/config.php` (or equivalent).
    - Ensure writable permissions for `storage/` and `logs/` directories.

4.  **Run Application**
    - Serve the application using a web server (Apache/Nginx) or PHP built-in server:
    ```bash
    php -S localhost:8000
    ```

## Folder Structure

```
/
├── app/                # Core application logic (Controllers, Models, Helpers)
├── public/             # Publicly accessible assets (CSS, JS, Images)
├── resources/          # Views and Layouts
│   ├── layouts/        # Master templates (app.php, etc.)
│   └── views/          # Module-specific views
├── routes/             # Route definitions
└── vendor/             # Composer dependencies
```

## Changelog

### [Latest] - 2026-01-25
- **Navigation Optimization**:
    - Implemented horizontal scrolling for the top navigation bar to accommodate more modules.
    - Added responsive Left/Right scroll buttons for better overflow handling.
    - Removed the "About" page link to save space.
- **Logout Improvements**:
    - Removed the redundant logout button from the top navigation.
    - Added a "Logout Confirmation" modal to prevent accidental sign-outs.
- **Login UI Overhaul**:
    - Redesigned login page with a modern **Glassmorphism** aesthetic.
    - Added "Shake" animation for invalid credentials.
    - Added a smooth "Success" overlay animation upon login submission.
- **Report Cards**:
    - Fixed rank calculation and display issues in printed reports.
    - Added individual print functionality from the exam details modal.
- **Modern Flash Messages**:
    - Replaced static flash messages with animated **Toast Notifications**.
    - Notifications appear in the top-right corner and auto-dismiss after 5 seconds.
    - Glassmorphism design with distinct styles for Success and Error messages.

---
*Developed by APSA SYSTEMS(Sammy k jnr).*
