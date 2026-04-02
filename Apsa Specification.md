# Apsa School Management System — Feature Specification

**Version**: 2.0.0  
**Last Updated**: 2026-03-08

This document details all functions and features the Apsa School Management System can perform.

---

## 1. Academic Management

### 1.1 Academic Year Management
- Create, edit, and delete academic years.
- Set an academic year as the "current/active" year.
- Assign terms (1st Term, 2nd Term, 3rd Term) to academic years.

### 1.2 Class Management
- Create and manage classes with name and level fields.
- Assign students to classes.

### 1.3 Subject Management
- Create and manage subjects taught across classes.

---

## 2. Student Management

### 2.1 Student Records
- Add new students with profile information (names, DOB, gender, admission number, guardian details).
- Edit existing student profiles (Core fields like Admission Number, Date, Class, and Academic Year are restricted to Super Admins).
- Delete student records.
- Upload and display student profile photos.
- Search and filter students by name, admission number, and class.
- Paginated student listing.

### 2.2 Student Categories
- Assign students to categories for additional classification.

### 2.3 Student Financial History
- View a student's fee payment history.
- See detailed fee breakdown via a modal with print and CSV export options.

---

## 3. Staff Management

- Add, edit, and delete staff profiles.
- View staff listing with search capability.
- Assign roles to staff members.

---

## 4. Attendance Management

### 4.1 Recording Attendance
- Mark daily attendance (Present, Absent, Late) for students per class.

### 4.2 Attendance History
- View attendance records with filters by class, student, academic year, term, weekly, and daily views.
- Paginated attendance history.

---

## 5. Fee & Payment Management

### 5.1 Fee Structures
- Define fee structures (types and amounts) per academic year.
- Restrict editing of existing Fee Structures to Super Admins only.

### 5.2 Fee Payments
- Record fee payments for individual students.
- Pay Amount field auto-calculates based on selected structures.
- Support for multiple payment methods (cash, bank transfer, mobile money, etc.).
- Automatic Cash Book credit logging on payment recording.

### 5.3 Payment Management
## 5. Finance Management
*   **Income & Expense Tracking:** Log and categorize daily operational income and expenses.
*   **Daily Cash Book:** Real-time generation of cash book records to monitor daily cash flow.
*   **Fee Master System:** Create and manage dynamic fee structures (Tuition, Transport, Feeding, Excursions).
*   **Payment Tracking:** Monitor complete, partial, and pending payments for all assigned fees.
*   **Reports:** Daily, weekly, monthly, termly, and yearly financial reporting for decision-making.
*   **Student Bill Tracking:** Monitor students' outstanding balances including dedicated guardian details (Name and Phone) directly from the bills table to simplify communications.
- Filter by date range and transaction type (credit/debit).
- View current cash balance.

---


### 8.1 General Reports Hub
- Central page with navigation to all report types.

### 8.2 Financial Reports *(Enhanced 2026-03-08)*
- Summary cards: Total Income (Fees), Total Expenses, Net Flow.
- Cash Book Overview showing total credits and debits for the selected period.
- Monthly Fee Payments table with per-month details modal (Print & CSV export).
- Expenses Breakdown table showing all expenses for the period.
- Filter by academic year.
- **Back to Reports** navigation button.

### 8.3 Student Reports
- Paginated, filterable student listing.
- Export as CSV or Print.

### 8.4 Attendance Reports
- Attendance summary by class with per-student figures.
- Filter by date range.
- Export as CSV or Print.

### 8.5 Academic (Exam) Reports
- View and filter ranked student performance by academic year, term, class, subject, and exam.
- Performance trends by exam or time dimension.
- Export ranking as CSV or Print.

### 8.6 Analytics Dashboard
- Key metrics summary (total students, staff, classes).
- Charts for recent payments and attendance (last 30 days).
- Exam statistics.

---

## 9. Exam & Results Management

- Record exam results per student, subject, and academic year.
- Compute and rank students by total and average score.
- Score-sheet printing and individual student report card generation.

---

## 10. Settings

### 10.1 Settings Page *(Revamped 2026-03-08)*
- **Side-panel (tabbed) layout** for easy navigation — replaced old collapsible layout.
- Sections accessible from a sidebar:
  - **School Information** — School name, address, email, phone.
  - **Currency & Locale** — Set currency symbol and format.
  - **Academic Settings** — Default academic year settings.
  - **Watermark Settings** — Watermark type (name, logo, or both) and transparency for prints.
  - **System Wipe** — Danger zone to wipe all system data (highlighted in red).

### 10.2 System Preferences
- Persistent active tab via `localStorage`.

---

## 11. User & Role Management

- Create, edit, and delete system user accounts.
- Role-based access (Admin, Super Admin, Staff).
- Password management.

---

## 12. Print & Export

- Print-ready pages for attendance, student, financial, and exam reports.
- School watermark applied to printouts (configurable type and transparency).
- School logo and name in report headers.
- CSV export available for students, attendance, payments, and exam rankings.

---

*This specification is updated whenever new features are added to the system.*
