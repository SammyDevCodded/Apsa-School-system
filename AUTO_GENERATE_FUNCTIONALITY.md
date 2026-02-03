# Auto-Generate Functionality

This document explains the new auto-generate feature for student admission numbers and staff employee IDs.

## Overview

The auto-generate feature allows administrators to automatically generate unique identifiers for students and staff members using a predefined format. The format follows the pattern: `[Prefix][HHMMSS]` where:

- **Prefix**: A customizable prefix defined in the settings
- **HHMMSS**: Current time in hours, minutes, and seconds

## Features

### 1. Customizable Prefixes
- Student Admission Number Prefix (default: "EPI")
- Staff Employee ID Prefix (default: "StID")

### 2. Automatic Generation
- New student admission numbers are automatically generated when adding a student
- New staff employee IDs are automatically generated when adding a staff member
- Format: [Prefix]-[HHMMSS] (e.g., EPI-143025, StID-123455)

### 3. Settings Configuration
- Only super administrators can configure the prefixes
- Preview of the next generated ID is shown in real-time
- Settings are stored in the database and persist between sessions

## Implementation Details

### Database Changes
Added two new columns to the `settings` table:
- `student_admission_prefix` (VARCHAR(10), default: "EPI")
- `staff_employee_prefix` (VARCHAR(10), default: "StID")

### Code Changes
1. **Migration**: Added new columns to settings table
2. **Settings Model**: Updated fillable fields to include new columns
3. **Settings Controller**: 
   - Updated index method to include default values
   - Updated update method to handle new fields
   - Added generateAdmissionNumber and generateEmployeeId methods
4. **Settings View**: Added new form section for auto-generate settings
5. **Routes**: Added new endpoints for generating IDs
6. **ID Generator Helper**: Created helper class for consistent ID generation
7. **Student Controller**: Updated to use default admission number
8. **Staff Controller**: Updated to use default employee ID
9. **Student Create View**: Updated to show default admission number
10. **Staff Create View**: Updated to show default employee ID

### File Locations
- Migration: `database/migrations/013_add_auto_generate_prefixes.php`
- Helper: `app/Helpers/IdGeneratorHelper.php`
- Updated Controllers: `app/Controllers/StudentController.php`, `app/Controllers/StaffController.php`, `app/Controllers/SettingsController.php`
- Updated Views: `resources/views/students/create.php`, `resources/views/staff/create.php`, `resources/views/settings/index.php`
- Routes: `routes/web.php`

## Usage Instructions

### Configuring Prefixes
1. Log in as a super administrator
2. Navigate to the Settings page
3. Scroll to the "Auto-Generate Settings" section
4. Modify the prefixes as needed
5. Click "Update Auto-Generate Settings"

### Adding Students with Auto-Generated Admission Numbers
1. Navigate to Students > Add Student
2. The admission number field will be pre-filled with an auto-generated value
3. Modify if needed or leave as is
4. Complete the rest of the form
5. Click "Save Student"

### Adding Staff with Auto-Generated Employee IDs
1. Navigate to Staff > Add Staff Member
2. The employee ID field will be pre-filled with an auto-generated value
3. Modify if needed or leave as is
4. Complete the rest of the form
5. Click "Save Staff Member"

## Format Examples
- Student Admission Number: EPI-143025 (Prefix: EPI, Time: 14:30:25)
- Staff Employee ID: StID-123455 (Prefix: StID, Time: 12:34:55)

## Technical Notes
- The time component ensures uniqueness within the same second
- Prefixes can be up to 10 characters long
- The preview in settings updates in real-time as prefixes are modified
- All generated IDs follow the consistent [Prefix]-[HHMMSS] format