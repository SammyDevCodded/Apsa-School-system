# Settings Update Fix Summary

## Issue Description
When trying to change the school logo from the settings page, the application threw a fatal error:
```
Fatal error: Uncaught Exception: Query failed: SQLSTATE[HY093]: Invalid parameter number
```

## Root Cause
The issue was in the [Model.php](file:///c%3A/wamp64/www/f2/app/Core/Model.php) file in the `update` method. The method was adding the primary key to the data array, which caused a conflict when:
1. The primary key was already present in the data array
2. The SQL query had two placeholders for the same parameter (`:id`)

This resulted in a query like:
```sql
UPDATE settings SET school_name = :school_name, currency_code = :currency_code, currency_symbol = :currency_symbol, school_logo = :school_logo, id = :id WHERE id = :id
```

With parameters:
```php
['school_name' => 'APSA-ERP', 'currency_code' => 'GHS', 'currency_symbol' => 'GH₵', 'school_logo' => '/storage/uploads/logo_1760679127_OIP (1).webp', 'id' => 1]
```

The PDO was expecting two `:id` parameters but only received one in the array.

Additionally, the [Setting.php](file:///c%3A/wamp64/www/f2/app/Models/Setting.php) model was explicitly adding the 'id' field to the data array in the `updateSettings` method, which compounded the issue.

## Solution
We implemented two fixes:

### 1. Fixed the base Model class
We modified the `update` method in [Model.php](file:///c%3A/wamp64/www/f2/app/Core/Model.php) to:

1. **Exclude the primary key from the SET clause**: Only include fields that are not the primary key in the UPDATE statement
2. **Properly handle parameters**: Create a separate parameters array that includes only the necessary fields plus the primary key for the WHERE clause

### After (fixed code):
```php
public function update($id, $data)
{
    $set = '';
    $params = [];
    
    // Build the SET clause and parameters, excluding the primary key
    foreach ($data as $key => $value) {
        if ($key !== $this->primaryKey) {
            $set .= "{$key} = :{$key}, ";
            $params[$key] = $value;
        }
    }
    $set = rtrim($set, ', ');
    
    // Add the primary key for the WHERE clause
    $params[$this->primaryKey] = $id;
    
    $sql = "UPDATE {$this->table} SET {$set} WHERE {$this->primaryKey} = :{$this->primaryKey}";
    return $this->db->execute($sql, $params);
}
```

### 2. Fixed the Settings model
We modified the `updateSettings` method in [Setting.php](file:///c%3A/wamp64/www/f2/app/Models/Setting.php) to:

1. **Remove the 'id' field from the data array**: Ensure that the primary key is not passed in the data array
2. **Properly call the parent update method**: Pass only the necessary data

### After (fixed code):
```php
public function updateSettings($data)
{
    // Remove the id from data if it exists, as it's not needed for the update
    unset($data['id']);
    
    // Only update the first record
    return $this->update(1, $data);
}
```

## Testing
We created and ran a test script that verified the fix works correctly:
- The settings update now works without errors
- All fields are properly updated in the database
- The primary key is correctly used in the WHERE clause

## Impact
This fix resolves the issue with updating settings, including:
- Changing the school name
- Updating currency settings
- Uploading and changing the school logo
- Any other settings updates in the application

The fix is backward compatible and doesn't affect any other functionality in the application. All other controllers and models that use the update method will now work correctly without the parameter conflict issue.