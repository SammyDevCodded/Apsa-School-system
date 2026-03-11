# Changelog
All notable changes to the Apsa School Management System will be documented in this file.

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
