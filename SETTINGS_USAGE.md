# Settings Page Usage Guide

## Accessing the Settings Page

The Settings page is available exclusively to Super Admin users and can be accessed through the navigation bar:

1. Log in as a Super Admin user (username: `superadmin`)
2. Look for the "Settings" link in the main navigation bar (it has a gear icon)
3. Click on the "Settings" link to access the settings page

## Available Settings

The Settings page allows Super Admin users to configure the following system-wide settings:

### 1. School Information
- **School Name**: The official name of your institution
- **School Logo**: Upload a custom logo for your school (JPG, PNG, or GIF format)

### 2. Currency Settings
- **Currency Code**: Three-letter currency code (e.g., GHS, USD, EUR)
- **Currency Symbol**: The symbol used to represent the currency (e.g., GH₵, $, €)

## How to Update Settings

1. Navigate to the Settings page
2. Modify any of the following fields as needed:
   - School Name
   - School Logo (click "Choose File" to select an image)
   - Currency Code
   - Currency Symbol
3. Click the "Update Settings" button to save your changes
4. A success message will appear at the top of the page confirming the update

## Default Values

The system comes pre-configured with the following default settings:
- **School Name**: Futuristic School
- **School Logo**: Not set (default placeholder will be shown)
- **Currency Code**: GHS (Ghanaian Cedis)
- **Currency Symbol**: GH₵

## Important Notes

1. Only users with the "Super Admin" role can access the Settings page
2. All other users (including regular Admins) will be redirected to the dashboard if they try to access the Settings page
3. Changes made on the Settings page affect the entire system globally
4. Supported image formats for the school logo are JPG, PNG, and GIF
5. The currency settings are used throughout the system for financial transactions and reporting

## Troubleshooting

If you encounter any issues with the Settings page:
1. Ensure you are logged in as a Super Admin user
2. Check that your browser supports file uploads if having issues with the logo
3. Verify that the `/storage/uploads/` directory has write permissions
4. Clear your browser cache and try again

For technical support, please contact your system administrator.