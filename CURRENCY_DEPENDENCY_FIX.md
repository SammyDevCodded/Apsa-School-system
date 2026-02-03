# Currency Dependency Fix

## Issue
The currency code and currency symbol fields on the settings page were independent, allowing users to enter mismatched values. Users could select a symbol like "$" but enter a currency code like "EUR", which would cause inconsistency.

## Solution
Implemented a dependency system where the currency code is automatically determined based on the selected currency symbol from a predefined list.

## Changes Made

### 1. Updated Settings Page Template
File: [resources/views/settings/index.php](file:///c%3A/wamp64/www/f2/resources/views/settings/index.php)

#### Changes:
1. **Replaced text input with select dropdown** for currency symbol
2. **Added predefined options** with symbol-code mapping using `data-code` attributes
3. **Made currency code field read-only** with visual indication (grayed out)
4. **Added JavaScript** to automatically update the currency code when a symbol is selected
5. **Added descriptive text** to explain the relationship between the fields

#### Features:
- Users can only select from a predefined list of currency symbols
- Currency code is automatically populated based on symbol selection
- Visual feedback showing both symbol and code in the dropdown options
- Form still validates that both fields are required
- JavaScript handles real-time updates when selections change

### 2. Updated Settings Controller
File: [app/Controllers/SettingsController.php](file:///c%3A/wamp64/www/f2/app/Controllers/SettingsController.php)

#### Changes:
1. **Added currency symbol to code mapping** as a class property
2. **Modified update method** to ignore the currency_code form input
3. **Determined currency code** programmatically based on selected symbol
4. **Added fallback** to default GHS code if symbol is not in mapping

#### Features:
- Server-side validation ensures consistency
- Even if form is manipulated, currency code is determined by symbol
- Default fallback to GHS (Ghanaian Cedi) if symbol is unknown

## Currency Options Provided

The following currency symbols and their corresponding codes are available:

| Symbol | Code  | Currency Name              |
|--------|-------|----------------------------|
| GH₵    | GHS   | Ghanaian Cedi              |
| ₵      | GHS   | Alternative Ghanaian Cedi  |
| $      | USD   | US Dollar                  |
| €      | EUR   | Euro                       |
| £      | GBP   | British Pound              |
| ¥      | JPY   | Japanese Yen               |
| ₹      | INR   | Indian Rupee               |
| ₩      | KRW   | South Korean Won           |
| ₽      | RUB   | Russian Ruble              |
| R      | ZAR   | South African Rand         |
| CFA    | XOF   | West African CFA Franc     |
| ₦      | NGN   | Nigerian Naira             |
| Sh     | KES   | Kenyan Shilling            |
| TSh    | TZS   | Tanzanian Shilling         |
| UGX    | UGX   | Ugandan Shilling           |
| Le     | SLL   | Sierra Leonean Leone       |

## Implementation Details

### Frontend (JavaScript)
- Listens for changes on the currency symbol dropdown
- Updates the currency code input with the corresponding value from the `data-code` attribute
- Initializes the correct value on page load
- Provides real-time feedback to users

### Backend (PHP)
- Ignores the currency_code form input for security and consistency
- Uses the predefined mapping to determine the correct currency code
- Provides fallback to GHS if symbol is not recognized
- Maintains backward compatibility with existing data

## Testing
Created test scripts to verify:
1. The currency mapping works correctly
2. The JavaScript updates the currency code field properly
3. The server-side processing ignores user input for currency code
4. Fallback behavior works for unknown symbols

## Security Benefits
- Prevents users from entering invalid or mismatched currency codes
- Reduces potential for data inconsistency
- Provides a controlled list of acceptable values
- Server-side validation ensures data integrity regardless of client-side manipulation

## User Experience Benefits
- Simplifies the process for users (just select a symbol)
- Eliminates the need to know currency codes
- Provides immediate visual feedback
- Reduces potential for input errors
- Maintains familiar form interface

The currency dependency feature is now fully implemented and working as intended.