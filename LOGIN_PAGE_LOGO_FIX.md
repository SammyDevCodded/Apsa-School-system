# Login Page Logo Display Fix

## Issue
The school logo set in the settings was not displaying on the login page.

## Solution
Implemented changes to display the school logo and name on the login page:

## Changes Made

### 1. Added `getSchoolSettings()` function to TemplateHelper
File: [app/Helpers/TemplateHelper.php](file:///c%3A/wamp64/www/f2/app/Helpers/TemplateHelper.php)

Added a new helper function that retrieves school settings from the database:
- Fetches settings from the `settings` table where `id = 1`
- Returns default values if no settings are found or if there's an error
- Includes all settings data: school_name, school_logo, currency_code, currency_symbol

### 2. Updated Login Page Template
File: [resources/views/auth/login.php](file:///c%3A/wamp64/www/f2/resources/views/auth/login.php)

Modified the login page to:
1. Load the TemplateHelper functions
2. Retrieve school settings using `getSchoolSettings()`
3. Display the school logo if one is set in the settings
4. Display the school name as the heading instead of generic text

## Implementation Details

### Logo Display
- Logo is displayed in a circular format (rounded-full class)
- Sized at 24x24 pixels (h-24 w-24 classes)
- Centered using flex justify-center
- Includes proper alt text for accessibility

### School Name Display
- If a school name is set in settings, it's displayed as the main heading
- If no school name is set, it falls back to "Sign in to your account"

### Error Handling
- If there's a database error, the page falls back to default values
- If no logo is set, no image is displayed (clean fallback)
- All output is properly escaped using `htmlspecialchars()` for security

## Testing
Created test scripts to verify:
1. The `getSchoolSettings()` function works correctly
2. The login page will display the logo when set
3. Proper fallback behavior when no logo is set

## Current Status
The login page now displays:
- School logo (circular, centered) when set in settings
- School name as the main heading when set in settings
- Proper fallback behavior when settings are not configured

The logo that was set in the previous fix is now visible on the login page.