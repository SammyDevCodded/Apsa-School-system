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

### [Current] - 2026-02-03
- **Staff Management**:
    - **Enhanced Staff Modal**: Now displays assigned subjects along with their corresponding classes in the teacher details view.
    - **Data Integrity**: Verified and fixed `StaffModel` queries to correctly join class data.
- **Academic Year Management**:
    - **Status Workflow**: Implemented "Mark as Completed" functionality for Academic Years.
    - **Database Schema**: Global schema update to support `completed` status for academic years.
    - **UI/UX**: added completion confirmation and visual status indicators.
- **Deployment**:
    - **GitHub Integration**: Project successfully initialized and pushed to remote repository (`school-erp-system`).
    - **Git Configuration**: proper `.gitignore` setup for environment security.

### [Major Update] - 2026-02-02
- **System Infrastructure**:
    - **Built-in Server Support**: Configured PHP built-in server alternatives for WAMP (router.php, start scripts).
    - **Database Logic**: Fixed core `Model` constructor issues preventing database connections.
- **Settings & Customization**:
    - **School Identity**: Fixed School Logo display on Login Page.
    - **Watermark System**: Implemented document watermarking (Logo/Text/Position/Transparency) for exports.
    - **Currency Logic**:
        - Fixed currency update failures.
        - Implemented "Currency Dependency" (Code automatically updates based on Symbol).
        - Enforced system-wide consistency for currency symbols.
- **Student Management**:
    - **Categories**: Added properties for "International", "Regular Day", and "Regular Boarding" students.
    - **UI Enhancements**: Added badges and filters for student categories.

### [Fixes & Refactoring] - 2026-01-30
- **Critical Bug Fixes**:
    - **Exam Results**: Fixed "Class Dropdown" missing issues in Bulk Result entry.
    - **AJAX Responses**: Standardized JSON headers and error handling in `ExamResultController`.
    - **Session Handling**: Fixed undefined index errors in Session Management.
    - **Settings Update**: Resolved false "Update Failed" error messages.

### [2026-01-25]
- **Navigation Optimization**:
    - Implemented horizontal scrolling for the top navigation bar.
    - Added responsive Left/Right scroll buttons.
- **Authentication**:
    - **Login UI**: Redesigned with Glassmorphism and animations.
    - **Logout**: Added confirmation modal and optimized button placement.
- **Reporting**:
    - **Rank Calculation**: Fixed class position logic in printed report cards.
    - **Printing**: Added individual print option from Exam Details.

---
*Developed by APSA SYSTEMS(Sammy k jnr).*
