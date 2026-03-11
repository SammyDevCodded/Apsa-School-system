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
- **Fee Management**: Create standard fee structures and track student payments.
- **Recurring Fees**: Manage subscription-like fees with automated billing cycles and comprehensive historical ledgers.
- **Billing**: Generate standard bills, set up custom partial student billing, and manage financial records.
- **Reporting**: Detailed financial summaries tracking income, expenses, and cash book overviews.

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
### [Current] - 2026-03-11
- **Recurring Fees Module**:
    - **Management System**: Completely new system for managing recurring subscription-like fees, handling enrollment, automated bill generation, and waivers.
    - **Historical Ledger**: Brought a deeply detailed historical ledger modal to the Pay tab, displaying generated bills, corrections, payments, running balances, and print capabilities.
- **Student Information**:
    - **Custom Billing**: Added an interface during student creation/editing to set custom partial billing on specific fee categories.
- **Financial Reports Fix**:
    - **System Stability**: Fixed "Undefined property db" error during financial report generation ensuring reliable access to cash book and expense data.

### [2.0.0] - 2026-03-08
- **Settings Enhancements**:
    - **UI Revamp**: Upgraded the settings page layout from a collapsible style to a more intuitive, tabbed side-panel interface.
- **Financial Reports Upgrades**:
    - **Navigation**: Added a robust "Back to General Reports" button for smoother workflow.
    - **Comprehensive Data**: Integrated Expense and Cash Book modules directly into the Financial Reports page for a unified, data-driven financial overview.


### [Current] - 2026-02-26
- **Finance Module Enhancements**:
    - **Cash Book Overhaul**: Added dynamic "Totals (Filtered)" row, precisely rebalanced column widths for A4 landscape printing, and integrated a dedicated `@media print` layout that auto-strips UI elements. Added a Credit/Debit "Transaction Type" isolate filter.
    - **Expense Log Upgrades**: Ported the Cash Book's robust data table layout directly into the main `expenses_list.php` view. Added Title/Code search, Category dropdown, Date range limits, and dynamic server-side pagination to the `Expense.php` model queries.
    - **Print Layout Compliance**: Standardized all finance tables (Cash Book, Expense Log, Fee Overviews) to strictly enforce 100% bleeding, table line rigidity, and proper column distribution on physical prints.
    - **Bug Fix**: Resolved an undefined property `db` error inside the `ExpenseController->savePaymentRequest()` workflow ensuring staff expense requests submit correctly.

### [2026-02-23]
- **Class Information**:
    - **Academic Performance**: Redesigned the "Academic Performance" tab to deeply group historical exam configurations by Academic Year and Term.
    - **Financial Data**: Refactored class financial statistics to accurately track bills, payments, and balances per fee across academic terms, including historical `original_classes` for promoted students.
    - **Termly Breakdown**: Added a new "Termly Financial Breakdown" section that visualizes grouped fee summaries by Academic Year and Term.
- **Student Profile**:
    - **Financial Details**: Added a "Details" button to the "Financial History" tab, triggering a modal with complete fee data, payment histories, and "Print/Export" options.
- **Fee Management**:
    - **Auto-Assignment**: Implemented automatic fee assignment logic when new students are created or switched between classes.
    - **Assignment Ratios**: Enhanced the fee structure UI to accurately display fractional "(Assigned/Total)" ratios alongside class names.
    - **Print Functionality**: Integrated a native print output view into the Fee Structure Details modal.
    - **Bug Fix**: Resolved an asynchronous loading error within the Fee Structure View modal.
- **System Administration**:
    - **System Wipe Security**: Escalated the "System Wipe" operation to be exclusively accessible by Super Admin accounts.
    - **Comprehensive Wipe Expansion**: Expanded the database clearing payload to include portal sessions, portal notifications, transaction receipts, and legacy archives via the wipe UX.
- **General Enhancements**:
    - **Notifications Standardized**: Replaced obtrusive JS alerts and static HTML banners with a globally consistent toast notification system across class addition, student registration, and bulk promotion workflows.

### [2026-02-16]
- **Attendance Management**:
    - **Enhanced History**: Renamed "Recent History" to "History" with robust filtering options.
    - **Filters**: Added capability to filter attendance records by Class, Student Name/ID, and Period (Daily, Weekly, Monthly, Yearly, Custom Range).
    - **Backend Logic**: Implemented dynamic `getHistory` method in `Attendance` model to support complex queries.
- **Report Cards**:
    - **Digital Signatures**: Added functionality to upload and display Headteacher and Class Teacher signatures.
    - **Signature Settings**: New options in Report Card Settings to toggle signature visibility.
    - **Print & PDF**: Updated print and PDF templates to include signatures when enabled.
- **Staff Portal**:
    - **Dashboard**: Added "My Subjects" and "Recent Exams" sections for quick access.
    - **Timetable**: Implemented teacher-specific timetable view.
    - **Academics**: Added Student Performance tracking view for subject teachers.
    - **Context Awareness**: Improved role detection logic for smoother portal navigation.
- **Parent Portal**:
    - **Profile Support**: Implemented extensive profile data retrieval for parents.
- **General Enhancements**:
    - **Security**: Added "Show Password" toggle for easier credential management.
    - **Reporting**: Enhanced Exam Dropdown in reports to show Exam Date and Description.
    - **UI/UX**: Added collapsible accordion for Academic Records in Student Profiles.
- **Bug Fixes**:
    - **Reports**: Fixed non-numeric value warning in `AcademicReportController` during export.
    - **Academic Records**: Fixed missing remarks in student profile academic history.
    - **Timetable**: Resolved case sensitivity issue with day names (monday vs Monday).
    - **Staff Dashboard**: Fixed "View Subjects" modal button functionality.

### [Previous] - 2026-02-03
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
