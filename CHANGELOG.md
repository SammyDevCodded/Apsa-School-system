# Changelog
All notable changes to the Apsa School Management System will be documented in this file.

## [2.1.1] - 2026-03-15
### Added
- Added "Finance & Ledger" and "Recurring Fees" data clear options to the System Wipe functionality for a more comprehensive factory reset.
- Added a new structured "Guardian" column (displaying Name and Phone Number) to the Fee Details modal on the Bills tab in the Finance Records module.
- Enhanced the "Student Bills" table and the "Fee Details" modal to display Guardian Name and Phone alongside the student, and added a soft red highlight to rows with outstanding balances to quickly identify pending payments.
- **Role-Based Access Control**: Restricted the ability to edit Fee Structures strictly to Super Admins.
- **Payment Request On Behalf**: Enabled Super Admins and Accountants to file payment requests directly on behalf of other staff members via a newly implemented dynamic custom searchable staff dropdown.
- **Student Profile Restrictions**: Made core academic fields (Admission Number, Admission Date, Academic Year, and Class) immutable on the Edit Student page for users who are not Super Admins.
- **Recurring Fees Reports**: Added a new 'Reports' tab to the Recurring Fees module. This tab allows generating, viewing, and printing reports for Payments, Waived entries, and Advances/Overpayments. Users can filter by Student, Fee Service, and Date Range. Added an "All" (Grand Ledger) report type to balance Billed, Waived, Paid, Advances, and Outstanding balances, and upgraded the Print functionality to include the dynamically fetched official School Name and Logo.

## [2.1.0] - 2026-03-11
### Added
- **Recurring Fees Module**: A comprehensive system for managing recurring fees, including student enrollment, automatic bill generation based on cycles, and payment tracking with waiver support.
- **Pay Tab Ledger**: Enhanced the Recurring Fees Pay tab with a detailed historical ledger modal, showing bills, corrections, payments, running balances, and days remaining, along with print functionality.
- **Custom Student Billing**: Added functionality in the Student creation/edit flow to assign custom partial fee billing for students with special fee structures.

### Fixed
- **Financial Reports**: Resolved a critical database connection error ("Undefined property db") in `ReportsController` that prevented report generation.

## [2.0.0] - 2026-03-08
### Added
- **Expense Reporting** in Financial Reports page: shows a full expense breakdown table including title, code, category, amount, and date for the selected academic year.
- **Cash Book Overview** in Financial Reports page: displays total credits and debits for the selected period.
- **Net Flow summary card** in Financial Reports page (Income minus Expenses).
- **Back to Reports** button on the Financial Reports page for easier navigation.

### Changed
- **Settings page layout**: changed from collapsible `<details>` sections to a modern side-panel (tabbed) layout with a persistent active tab (saved via `localStorage`). Icons added to sidebar navigation items.
- **Financial Reports** `ReportsController`: now fetches expense and cash book data filtered by the selected academic year's start/end dates and passes them to the view.
