# Multiple Classes for Exams Feature

## Overview
This feature allows exams to be assigned to multiple classes instead of just one, providing more flexibility in exam management.

## Changes Made

### 1. Database Migration
Created a new migration file `023_create_exam_classes_table.php` to add a many-to-many relationship table between exams and classes:
- New table `exam_classes` with foreign keys to `exams` and `classes`
- Unique constraint on `exam_id` and `class_id` to prevent duplicates
- Foreign key constraints with CASCADE actions

### 2. Exam Model Updates
Modified `app/Models/Exam.php` to support multiple classes:
- Added `getClasses($examId)` method to retrieve all classes assigned to an exam
- Added `assignClasses($examId, $classIds)` method to manage class assignments
- Added `getAllWithClasses()` method to retrieve exams with their assigned classes for display

### 3. Exam Controller Updates
Modified `app/Controllers/ExamController.php` to handle multiple class selections:
- Updated `store()` method to accept multiple class IDs from checkboxes
- Updated `update()` method to update class assignments
- Updated `create()` and `edit()` methods to pass class data to views
- Added validation to ensure at least one class is selected

### 4. View Updates

#### Create Exam Form (`resources/views/exams/create.php`)
- Replaced single class dropdown with checkboxes for multiple class selection
- Added JavaScript validation to ensure at least one class is selected
- Improved UI with scrollable container for classes

#### Edit Exam Form (`resources/views/exams/edit.php`)
- Replaced single class dropdown with checkboxes for multiple class selection
- Pre-checked boxes for currently assigned classes
- Added JavaScript validation to ensure at least one class is selected

#### Exam Details View (`resources/views/exams/show.php`)
- Updated to display all assigned classes in a list format
- Maintained backward compatibility for single class display

#### Exams List View (`resources/views/exams/index.php`)
- Updated to display all assigned classes for each exam
- Shows classes as a comma-separated list

## Implementation Details

### Backward Compatibility
The implementation maintains backward compatibility by:
1. Keeping the `class_id` column in the `exams` table for existing functionality
2. Using the first selected class as the primary class for backward compatibility
3. Displaying assigned classes in addition to the primary class

### Data Flow
1. User selects multiple classes via checkboxes on create/edit forms
2. Form submits array of selected class IDs
3. Controller validates selection and creates/updates exam
4. Model manages the many-to-many relationship in the `exam_classes` table
5. Views display all assigned classes for each exam

### Validation
- Client-side validation ensures at least one class is selected
- Server-side validation prevents creation/update without classes
- Proper error messages are displayed to users

## Installation

To use this feature, you need to run the database migration to create the `exam_classes` table:

1. Run the migration script:
   ```
   php run_migration_023.php
   ```

2. Alternatively, you can run the migration directly:
   ```
   php database/migrations/023_create_exam_classes_table.php
   ```

## Usage
1. Navigate to "Add Exam" page
2. Select one or more classes using checkboxes
3. Fill in other exam details
4. Save the exam
5. The exam will now be associated with all selected classes

## Benefits
- Allows exams to be shared across multiple classes
- Provides more flexible exam scheduling
- Maintains backward compatibility with existing functionality
- Improves user experience with intuitive checkbox interface