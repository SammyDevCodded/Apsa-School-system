<?php 
$title = 'Settings'; 
ob_start(); 

// Define currency symbol to code mapping
$currencyOptions = [
    'GH₵' => 'GHS',
    '$' => 'USD',
    '€' => 'EUR',
    '£' => 'GBP',
    '¥' => 'JPY',
    '₹' => 'INR',
    '₩' => 'KRW',
    '₽' => 'RUB',
    'R' => 'ZAR',
    '₵' => 'GHS', // Alternative symbol for Ghanaian Cedi
    'CFA' => 'XOF', // West African CFA Franc
    '₦' => 'NGN', // Nigerian Naira
    'Sh' => 'KES', // Kenyan Shilling
    'TSh' => 'TZS', // Tanzanian Shilling
    'UGX' => 'UGX', // Ugandan Shilling
    'Le' => 'SLL', // Sierra Leonean Leone
];

// Define watermark options
$watermarkTypes = [
    'none' => 'None',
    'logo' => 'Logo Only',
    'name' => 'School Name Only',
    'both' => 'Logo and School Name'
];

$watermarkPositions = [
    'top-left' => 'Top Left',
    'top-center' => 'Top Center',
    'top-right' => 'Top Right',
    'middle-left' => 'Middle Left',
    'center' => 'Center',
    'middle-right' => 'Middle Right',
    'bottom-left' => 'Bottom Left',
    'bottom-center' => 'Bottom Center',
    'bottom-right' => 'Bottom Right'
];

// Define grading types
$gradingTypes = [
    'numeric' => 'Numeric (1, 2, 3...)',
    'letter' => 'Letter (A, B, C...)'
];
?>

<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <!-- Flash Messages -->
        <?php if (isset($_SESSION['flash_success'])): ?>
            <div class="rounded-md bg-green-50 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586l-1.707-1.707a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">
                            <?= htmlspecialchars($_SESSION['flash_success']) ?>
                        </p>
                    </div>
                    <div class="ml-auto pl-3">
                        <div class="-mx-1.5 -my-1.5">
                            <button onclick="this.parentElement.parentElement.parentElement.parentElement.remove()" class="inline-flex bg-green-50 rounded-md p-1.5 text-green-500 hover:bg-green-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-green-50 focus:ring-green-600">
                                <span class="sr-only">Dismiss</span>
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <?php unset($_SESSION['flash_success']); ?>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['flash_error'])): ?>
            <div class="rounded-md bg-red-50 p-4 mb-6">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-red-800">
                            <?= htmlspecialchars($_SESSION['flash_error']) ?>
                        </p>
                    </div>
                    <div class="ml-auto pl-3">
                        <div class="-mx-1.5 -my-1.5">
                            <button onclick="this.parentElement.parentElement.parentElement.parentElement.remove()" class="inline-flex bg-red-50 rounded-md p-1.5 text-red-500 hover:bg-red-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-red-50 focus:ring-red-600">
                                <span class="sr-only">Dismiss</span>
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <?php unset($_SESSION['flash_error']); ?>
        <?php endif; ?>

        <!-- Settings Form -->
        <details class="group bg-white shadow overflow-hidden sm:rounded-lg mb-6" open>
            <summary class="px-4 py-5 sm:px-6 cursor-pointer list-none flex justify-between items-center focus:outline-none">
                <div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900">School Information</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">Update your school's name, logo, and currency.</p>
                </div>
                <div class="ml-4 flex-shrink-0">
                   <svg class="h-6 w-6 text-gray-500 transform group-open:rotate-180 transition-transform duration-200" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                   </svg>
                </div>
            </summary>
            <div class="border-t border-gray-200">
                <form action="/settings" method="POST" class="px-4 py-5 sm:p-6" enctype="multipart/form-data">
                    <input type="hidden" name="_method" value="PUT">
                    <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                        <div class="sm:col-span-6">
                            <label for="school_name" class="block text-sm font-medium text-gray-700">School Name</label>
                            <div class="mt-1">
                                <input type="text" name="school_name" id="school_name" value="<?= htmlspecialchars($settings['school_name'] ?? '') ?>" required
                                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            </div>
                        </div>

                        <div class="sm:col-span-6">
                            <label for="school_address" class="block text-sm font-medium text-gray-700">School Address</label>
                            <div class="mt-1">
                                <textarea name="school_address" id="school_address" rows="3"
                                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"><?= htmlspecialchars($settings['school_address'] ?? '') ?></textarea>
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Enter the full address of your school</p>
                        </div>

                        <div class="sm:col-span-6">
                            <label for="school_logo" class="block text-sm font-medium text-gray-700">School Logo</label>
                            <div class="mt-1 flex items-center">
                                <?php if (!empty($settings['school_logo'])): ?>
                                    <img src="<?= htmlspecialchars($settings['school_logo']) ?>" alt="Current Logo" class="h-16 w-16 rounded-full">
                                <?php else: ?>
                                    <div class="h-16 w-16 rounded-full bg-gray-200 flex items-center justify-center">
                                        <span class="text-gray-500 text-xs">No Logo</span>
                                    </div>
                                <?php endif; ?>
                                <div class="ml-5">
                                    <input type="file" name="school_logo" id="school_logo" accept="image/*"
                                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                    <p class="text-xs text-gray-500 mt-1">Upload a logo (JPG, PNG, GIF)</p>
                                    <?php if (!empty($settings['school_logo'])): ?>
                                        <div class="mt-2 flex items-center">
                                            <input type="checkbox" name="remove_logo" value="1" id="remove_logo" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                            <label for="remove_logo" class="ml-2 text-sm text-gray-600">Remove current logo</label>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <label for="currency_symbol" class="block text-sm font-medium text-gray-700">Currency Symbol</label>
                            <div class="mt-1">
                                <select name="currency_symbol" id="currency_symbol" required
                                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                    <option value="">Select a currency symbol</option>
                                    <?php foreach ($currencyOptions as $symbol => $code): ?>
                                        <option value="<?= htmlspecialchars($symbol) ?>" 
                                            data-code="<?= htmlspecialchars($code) ?>"
                                            <?= (isset($settings['currency_symbol']) && $settings['currency_symbol'] == $symbol) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($symbol) ?> (<?= htmlspecialchars($code) ?>)
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <p class="text-xs text-gray-500 mt-1">Select from predefined currency symbols</p>
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <label for="currency_code" class="block text-sm font-medium text-gray-700">Currency Code</label>
                            <div class="mt-1">
                                <input type="text" name="currency_code" id="currency_code" value="<?= htmlspecialchars($settings['currency_code'] ?? 'GHS') ?>" maxlength="3" required readonly
                                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md bg-gray-100">
                                <p class="text-xs text-gray-500 mt-1">Automatically set based on symbol selection</p>
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <label for="idle_timeout_minutes" class="block text-sm font-medium text-gray-700">System Idle Timeout (Minutes)</label>
                            <div class="mt-1">
                                <input type="number" min="0" max="1440" name="idle_timeout_minutes" id="idle_timeout_minutes" value="<?= htmlspecialchars($settings['idle_timeout_minutes'] ?? 0) ?>" required
                                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                <p class="text-xs text-gray-500 mt-1">Set to 0 to disable the suspension overlay.</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 flex items-center justify-end">
                        <input type="hidden" name="form_type" value="school_info">
                        <button type="submit"
                            class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Update School Information
                        </button>
                    </div>
                </form>
            </div>
            </div>
            <div class="border-t border-gray-200">
                <form action="/settings" method="POST" class="px-4 py-5 sm:p-6" enctype="multipart/form-data">
                    <!-- ... existing form fields ... -->
                </form>
            </div>
        </details>

        <!-- Date & Time Settings Form (Only visible to super admins) -->
        <?php if (isset($isSuperAdmin) && $isSuperAdmin): ?>
        <details class="group mt-6 bg-white shadow overflow-hidden sm:rounded-lg mb-6">
            <summary class="px-4 py-5 sm:px-6 cursor-pointer list-none flex justify-between items-center focus:outline-none">
                <div>
                     <h3 class="text-lg leading-6 font-medium text-gray-900">Date & Time Settings</h3>
                     <p class="mt-1 max-w-2xl text-sm text-gray-500">Manually set the system date and time.</p>
                </div>
                <div class="ml-4 flex-shrink-0">
                   <svg class="h-6 w-6 text-gray-500 transform group-open:rotate-180 transition-transform duration-200" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                   </svg>
                </div>
            </summary>
            <div class="border-t border-gray-200">
                <form action="/settings" method="POST" class="px-4 py-5 sm:p-6">
                    <input type="hidden" name="_method" value="PUT">
                    
                    <?php 
                        // Calculate current adjusted time
                        $offset = $settings['time_offset_seconds'] ?? 0;
                        $currentTime = time() + $offset;
                        $currentDate = date('Y-m-d', $currentTime);
                        $currentTimeStr = date('H:i', $currentTime);
                    ?>
                    
                    <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                        <div class="sm:col-span-3">
                            <label for="date" class="block text-sm font-medium text-gray-700">Date</label>
                            <div class="mt-1">
                                <input type="date" name="date" id="date" value="<?= $currentDate ?>" required
                                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <label for="time" class="block text-sm font-medium text-gray-700">Time</label>
                            <div class="mt-1">
                                <input type="time" name="time" id="time" value="<?= $currentTimeStr ?>" required
                                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            </div>
                        </div>
                        
                        <div class="sm:col-span-6">
                            <p class="text-sm text-gray-500 italic">
                                Note: This will override the system time for all users. The current server time is <?= date('Y-m-d H:i:s') ?>.
                            </p>
                        </div>
                    </div>

                    <div class="mt-6 flex items-center justify-end">
                        <input type="hidden" name="form_type" value="datetime_settings">
                        <button type="submit"
                            class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Update Date & Time
                        </button>
                    </div>
                </form>
            </div>
        </details>
        <?php endif; ?>
        <?php if (isset($isSuperAdmin) && $isSuperAdmin): ?>
        <details class="group mt-6 bg-white shadow overflow-hidden sm:rounded-lg mb-6">
            <summary class="px-4 py-5 sm:px-6 cursor-pointer list-none flex justify-between items-center focus:outline-none">
                <div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Watermark Settings</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">Configure watermark for printed documents and exports.</p>
                </div>
                <div class="ml-4 flex-shrink-0">
                   <svg class="h-6 w-6 text-gray-500 transform group-open:rotate-180 transition-transform duration-200" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                   </svg>
                </div>
            </summary>
            <div class="border-t border-gray-200">
                <form action="/settings" method="POST" class="px-4 py-5 sm:p-6">
                    <input type="hidden" name="_method" value="PUT">
                    <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                        <div class="sm:col-span-6">
                            <label for="watermark_type" class="block text-sm font-medium text-gray-700">Watermark Type</label>
                            <div class="mt-1">
                                <select name="watermark_type" id="watermark_type"
                                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                    <?php foreach ($watermarkTypes as $value => $label): ?>
                                        <option value="<?= htmlspecialchars($value) ?>" 
                                            <?= (isset($settings['watermark_type']) && $settings['watermark_type'] == $value) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($label) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <p class="text-xs text-gray-500 mt-1">Select what to display as watermark</p>
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <label for="watermark_position" class="block text-sm font-medium text-gray-700">Watermark Position</label>
                            <div class="mt-1">
                                <select name="watermark_position" id="watermark_position"
                                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                    <?php foreach ($watermarkPositions as $value => $label): ?>
                                        <option value="<?= htmlspecialchars($value) ?>" 
                                            <?= (isset($settings['watermark_position']) && $settings['watermark_position'] == $value) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($label) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <p class="text-xs text-gray-500 mt-1">Select where to position the watermark</p>
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <label for="watermark_transparency" class="block text-sm font-medium text-gray-700">Transparency Level</label>
                            <div class="mt-1">
                                <input type="range" name="watermark_transparency" id="watermark_transparency" 
                                    min="0" max="100" value="<?= htmlspecialchars($settings['watermark_transparency'] ?? 20) ?>"
                                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                <div class="flex justify-between text-xs text-gray-500 mt-1">
                                    <span>0% (Opaque)</span>
                                    <span id="transparency-value"><?= htmlspecialchars($settings['watermark_transparency'] ?? 20) ?>%</span>
                                    <span>100% (Transparent)</span>
                                </div>
                                <p class="text-xs text-gray-500 mt-1">Adjust the transparency of the watermark</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 flex items-center justify-end">
                        <input type="hidden" name="form_type" value="watermark">
                        <button type="submit"
                            class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Update Watermark Settings
                        </button>
                    </div>
                </form>
            </div>
            </div>
        </details>
        
        <!-- Report Card Settings (Only visible to super admins) -->
        <details class="group mt-6 bg-white shadow overflow-hidden sm:rounded-lg mb-6">
            <summary class="px-4 py-5 sm:px-6 cursor-pointer list-none flex justify-between items-center focus:outline-none">
                <div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Report Card Settings</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">Customize the design and layout of student report cards.</p>
                </div>
                <div class="ml-4 flex-shrink-0">
                   <svg class="h-6 w-6 text-gray-500 transform group-open:rotate-180 transition-transform duration-200" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                   </svg>
                </div>
            </summary>
            <div class="border-t border-gray-200">
                <div class="px-4 py-5 sm:p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">Configure report card layout, logo position, and display options.</p>
                        </div>
                        <a href="/report-cards/settings" 
                           class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Configure Report Cards
                        </a>
                    </div>
                </div>
            </div>
        </details>
        
        <!-- Academic Settings (Only visible to super admins) -->
        <?php if (isset($isSuperAdmin) && $isSuperAdmin): ?>
        <details class="group mt-6 bg-white shadow overflow-hidden sm:rounded-lg mb-6">
             <summary class="px-4 py-5 sm:px-6 cursor-pointer list-none flex justify-between items-center focus:outline-none">
                <div>
                     <h3 class="text-lg leading-6 font-medium text-gray-900">Academic Settings</h3>
                     <p class="mt-1 max-w-2xl text-sm text-gray-500">Configure academic year range and other academic settings.</p>
                </div>
                <div class="ml-4 flex-shrink-0">
                   <svg class="h-6 w-6 text-gray-500 transform group-open:rotate-180 transition-transform duration-200" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                   </svg>
                </div>
            </summary>
            <div class="border-t border-gray-200">
                <form action="/settings" method="POST" class="px-4 py-5 sm:p-6">
                    <input type="hidden" name="_method" value="PUT">
                    <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                        <div class="sm:col-span-3">
                            <label for="academic_year_future_limit" class="block text-sm font-medium text-gray-700">Future Academic Years Limit</label>
                            <div class="mt-1">
                                <input type="number" name="academic_year_future_limit" id="academic_year_future_limit" 
                                    value="<?= htmlspecialchars($settings['academic_year_future_limit'] ?? 10) ?>" min="1" max="50"
                                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                <p class="text-xs text-gray-500 mt-1">Number of future years to display in dropdowns.</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 flex items-center justify-end">
                        <input type="hidden" name="form_type" value="academic_settings">
                        <button type="submit"
                            class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Update Academic Settings
                        </button>
                    </div>
                </form>
            </div>
            </div>
        </details>
        <?php endif; ?>
        
        <!-- Auto-Generate Settings Form (Only visible to super admins) -->
        <details class="group mt-6 bg-white shadow overflow-hidden sm:rounded-lg mb-6">
             <summary class="px-4 py-5 sm:px-6 cursor-pointer list-none flex justify-between items-center focus:outline-none">
                <div>
                     <h3 class="text-lg leading-6 font-medium text-gray-900">Auto-Generate Settings</h3>
                     <p class="mt-1 max-w-2xl text-sm text-gray-500">Configure prefixes for auto-generating student admission numbers and staff employee IDs.</p>
                </div>
                 <div class="ml-4 flex-shrink-0">
                   <svg class="h-6 w-6 text-gray-500 transform group-open:rotate-180 transition-transform duration-200" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                   </svg>
                </div>
            </summary>
            <div class="border-t border-gray-200">
                <form action="/settings" method="POST" class="px-4 py-5 sm:p-6">
                    <input type="hidden" name="_method" value="PUT">
                    <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                        <div class="sm:col-span-3">
                            <label for="student_admission_prefix" class="block text-sm font-medium text-gray-700">Student Admission Number Prefix</label>
                            <div class="mt-1">
                                <input type="text" name="student_admission_prefix" id="student_admission_prefix" 
                                    value="<?= htmlspecialchars($settings['student_admission_prefix'] ?? 'EPI') ?>" maxlength="10"
                                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                <p class="text-xs text-gray-500 mt-1">Format: [Prefix][HHMMSS] - Example: EPI-143025</p>
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <label for="staff_employee_prefix" class="block text-sm font-medium text-gray-700">Staff Employee ID Prefix</label>
                            <div class="mt-1">
                                <input type="text" name="staff_employee_prefix" id="staff_employee_prefix" 
                                    value="<?= htmlspecialchars($settings['staff_employee_prefix'] ?? 'StID') ?>" maxlength="10"
                                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                <p class="text-xs text-gray-500 mt-1">Format: [Prefix][HHMMSS] - Example: StID-123455</p>
                            </div>
                        </div>
                        
                        <!-- Preview section -->
                        <div class="sm:col-span-6 mt-4">
                            <h4 class="text-md font-medium text-gray-800">Preview</h4>
                            <div class="mt-2 p-3 bg-gray-50 rounded-md">
                                <p class="text-sm text-gray-600">
                                    Next Student Admission Number: 
                                    <span id="admission-preview" class="font-mono"><?= htmlspecialchars($settings['student_admission_prefix'] ?? 'EPI') ?>-<?= date('His') ?></span>
                                </p>
                                <p class="text-sm text-gray-600 mt-1">
                                    Next Staff Employee ID: 
                                    <span id="employee-preview" class="font-mono"><?= htmlspecialchars($settings['staff_employee_prefix'] ?? 'StID') ?>-<?= date('His') ?></span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 flex items-center justify-end">
                        <input type="hidden" name="form_type" value="auto_generate">
                        <button type="submit"
                            class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Update Auto-Generate Settings
                        </button>
                    </div>
                </form>
            </div>
            </div>
        </details>
        
        <!-- Grading System Settings (Only visible to super admins) -->
        <details class="group mt-6 bg-white shadow overflow-hidden sm:rounded-lg mb-6">
             <summary class="px-4 py-5 sm:px-6 cursor-pointer list-none flex justify-between items-center focus:outline-none">
                <div>
                     <h3 class="text-lg leading-6 font-medium text-gray-900">Grading System</h3>
                     <p class="mt-1 max-w-2xl text-sm text-gray-500">Configure how marks and grades are assigned along with remarks.</p>
                </div>
                <div class="ml-4 flex-shrink-0">
                   <svg class="h-6 w-6 text-gray-500 transform group-open:rotate-180 transition-transform duration-200" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                       <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                   </svg>
                </div>
            </summary>
            <div class="border-t border-gray-200">
                <div class="px-4 py-5 sm:p-6">
                    <!-- Grading Scales Section -->
                    <div class="mb-8">
                        <div class="flex justify-between items-center mb-4">
                            <h4 class="text-md font-medium text-gray-800">Grading Scales</h4>
                            <button type="button" id="add-scale-btn" class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Add New Scale
                            </button>
                        </div>
                        
                        <!-- Add Scale Form (Initially Hidden) -->
                        <div id="add-scale-form" class="hidden mb-6 bg-gray-50 p-4 rounded-md">
                            <form action="/settings/grading-scale" method="POST" class="space-y-4">
                                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                    <div>
                                        <label for="scale_name" class="block text-sm font-medium text-gray-700">Scale Name</label>
                                        <input type="text" name="scale_name" id="scale_name" required
                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    </div>
                                    <div>
                                        <label for="grading_type" class="block text-sm font-medium text-gray-700">Grading Type</label>
                                        <select name="grading_type" id="grading_type" required
                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                            <?php foreach ($gradingTypes as $value => $label): ?>
                                                <option value="<?= htmlspecialchars($value) ?>"><?= htmlspecialchars($label) ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="flex justify-end">
                                    <button type="button" id="cancel-scale-btn" class="mr-2 inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        Cancel
                                    </button>
                                    <button type="submit"
                                        class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        Create Scale
                                    </button>
                                </div>
                            </form>
                        </div>
                        
                        <!-- Existing Scales -->
                        <?php if (!empty($gradingScales)): ?>
                            <?php foreach ($gradingScales as $scale): ?>
                                <div class="mb-6 border border-gray-200 rounded-md">
                                    <div class="bg-gray-50 px-4 py-3 border-b border-gray-200 flex justify-between items-center">
                                        <div>
                                            <h5 class="text-md font-medium text-gray-800"><?= htmlspecialchars($scale['name']) ?></h5>
                                            <p class="text-sm text-gray-500">
                                                <?= htmlspecialchars($gradingTypes[$scale['grading_type']] ?? $scale['grading_type']) ?>
                                            </p>
                                        </div>
                                        <div>
                                            <button type="button" class="add-rule-btn inline-flex items-center px-2 py-1 border border-transparent text-xs leading-4 font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500" data-scale-id="<?= $scale['id'] ?>">
                                                Add Rule
                                            </button>
                                            <a href="/settings/grading-scale/<?= $scale['id'] ?>/delete" 
                                               class="ml-2 inline-flex items-center px-2 py-1 border border-transparent text-xs leading-4 font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500"
                                               onclick="return confirm('Are you sure you want to delete this grading scale and all its rules?')">
                                                Delete
                                            </a>
                                        </div>
                                    </div>
                                    
                                    <!-- Add Rule Form (Initially Hidden) -->
                                    <div id="add-rule-form-<?= $scale['id'] ?>" class="hidden p-4 bg-white border-b border-gray-200">
                                        <form action="/settings/grading-rule" method="POST" class="space-y-4">
                                            <input type="hidden" name="scale_id" value="<?= $scale['id'] ?>">
                                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-4">
                                                <div>
                                                    <label for="min_score_<?= $scale['id'] ?>" class="block text-sm font-medium text-gray-700">Min Score</label>
                                                    <input type="number" name="min_score" id="min_score_<?= $scale['id'] ?>" step="0.01" min="0" required
                                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                                </div>
                                                <div>
                                                    <label for="max_score_<?= $scale['id'] ?>" class="block text-sm font-medium text-gray-700">Max Score</label>
                                                    <input type="number" name="max_score" id="max_score_<?= $scale['id'] ?>" step="0.01" min="0" required
                                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                                </div>
                                                <div>
                                                    <label for="grade_<?= $scale['id'] ?>" class="block text-sm font-medium text-gray-700">Grade</label>
                                                    <input type="text" name="grade" id="grade_<?= $scale['id'] ?>" required
                                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                                </div>
                                                <div>
                                                    <label for="remark_<?= $scale['id'] ?>" class="block text-sm font-medium text-gray-700">Remark</label>
                                                    <input type="text" name="remark" id="remark_<?= $scale['id'] ?>"
                                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                                </div>
                                            </div>
                                            <div class="flex justify-end">
                                                <button type="button" class="cancel-rule-btn mr-2 inline-flex justify-center py-1 px-3 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" data-scale-id="<?= $scale['id'] ?>">
                                                    Cancel
                                                </button>
                                                <button type="submit"
                                                    class="inline-flex justify-center py-1 px-3 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                    Add Rule
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                    
                                    <!-- Rules Table -->
                                    <?php if (!empty($scale['rules'])): ?>
                                        <div class="overflow-x-auto">
                                            <table class="min-w-full divide-y divide-gray-200">
                                                <thead class="bg-gray-50">
                                                    <tr>
                                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Score Range</th>
                                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Grade</th>
                                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Remark</th>
                                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="bg-white divide-y divide-gray-200">
                                                    <?php foreach ($scale['rules'] as $rule): ?>
                                                        <tr>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                                <?= htmlspecialchars($rule['min_score']) ?> - <?= htmlspecialchars($rule['max_score']) ?>
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                                <?= htmlspecialchars($rule['grade']) ?>
                                                            </td>
                                                            <td class="px-6 py-4 text-sm text-gray-900">
                                                                <?= htmlspecialchars($rule['remark'] ?? 'N/A') ?>
                                                            </td>
                                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                                <a href="/settings/grading-rule/<?= $rule['id'] ?>/delete" 
                                                                   class="text-red-600 hover:text-red-900"
                                                                   onclick="return confirm('Are you sure you want to delete this grading rule?')">
                                                                    Delete
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    <?php else: ?>
                                        <div class="p-4 text-center text-gray-500">
                                            No grading rules defined for this scale. Add rules above.
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="text-center text-gray-500 py-4">
                                No grading scales defined. Create one using the "Add New Scale" button above.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            </div>
        </details>
        <?php endif; ?>
        
        <!-- Bulk Import Section (Only visible to super admins) -->
        <?php if (isset($isSuperAdmin) && $isSuperAdmin): ?>
        <div class="mt-8 bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Bulk Data Import</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">Import students and classes in bulk using CSV or Excel files.</p>
            </div>
            <div class="border-t border-gray-200">
                <div class="px-4 py-5 sm:p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">Import large amounts of student or class data quickly and efficiently.</p>
                        </div>
                        <a href="/settings/import" 
                           class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Import Data
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- System Wipe Section (Only visible to strict super admins) -->
        <?php if (isset($isTrueSuperAdmin) && $isTrueSuperAdmin): ?>
        <div class="mt-8 bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">System Wipe</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">Permanently delete data from the system.</p>
            </div>
            <div class="border-t border-gray-200">
                <div class="px-4 py-5 sm:p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-500">Wipe selected portions of the system data. This action cannot be undone.</p>
                        </div>
                        <a href="/settings/wipe" 
                           class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            Wipe System Data
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- System Information -->
        <div class="mt-8 bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">System Information</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">Technical details about the system.</p>
            </div>
            <div class="border-t border-gray-200">
                <dl>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            School Name
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <?= htmlspecialchars($settings['school_name'] ?? APP_NAME) ?>
                        </dd>
                    </div>
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            Currency
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <?= htmlspecialchars($settings['currency_code'] ?? 'GHS') ?> (<?= htmlspecialchars($settings['currency_symbol'] ?? 'GH₵') ?>)
                        </dd>
                    </div>
                    <?php if (isset($isSuperAdmin) && $isSuperAdmin): ?>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            Watermark Settings
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            Type: <?= htmlspecialchars($watermarkTypes[$settings['watermark_type'] ?? 'none']) ?><br>
                            Position: <?= htmlspecialchars($watermarkPositions[$settings['watermark_position'] ?? 'center']) ?><br>
                            Transparency: <?= htmlspecialchars($settings['watermark_transparency'] ?? 20) ?>%
                        </dd>
                    </div>
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            Auto-Generate Settings
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            Student Admission Prefix: <?= htmlspecialchars($settings['student_admission_prefix'] ?? 'EPI') ?><br>
                            Staff Employee Prefix: <?= htmlspecialchars($settings['staff_employee_prefix'] ?? 'StID') ?>
                        </dd>
                    </div>
                    <?php endif; ?>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            PHP Version
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <?= phpversion() ?>
                        </dd>
                    </div>
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            Server Software
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <?= htmlspecialchars($_SERVER['SERVER_SOFTWARE'] ?? 'Unknown') ?>
                        </dd>
                    </div>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            Database Version
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <?php
                            try {
                                $pdo = new PDO("mysql:host=localhost;dbname=school_erp", 'root', '');
                                $stmt = $pdo->query("SELECT VERSION()");
                                $version = $stmt->fetchColumn();
                                echo htmlspecialchars($version);
                            } catch (Exception $e) {
                                echo 'Unable to connect to database';
                            }
                            ?>
                        </dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Update currency code when currency symbol changes
    const currencySymbolSelect = document.getElementById('currency_symbol');
    const currencyCodeInput = document.getElementById('currency_code');
    
    if (currencySymbolSelect && currencyCodeInput) {
        currencySymbolSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const currencyCode = selectedOption.getAttribute('data-code');
            if (currencyCode) {
                currencyCodeInput.value = currencyCode;
            }
        });
    }
    
    // Update transparency value display
    const transparencySlider = document.getElementById('watermark_transparency');
    const transparencyValue = document.getElementById('transparency-value');
    
    if (transparencySlider && transparencyValue) {
        transparencySlider.addEventListener('input', function() {
            transparencyValue.textContent = this.value + '%';
        });
    }
    
    // Add confirmation for removing logo
    const removeLogoCheckbox = document.querySelector('input[name="remove_logo"]');
    if (removeLogoCheckbox) {
        removeLogoCheckbox.addEventListener('change', function() {
            if (this.checked) {
                const confirmRemove = confirm('Are you sure you want to remove the current logo?');
                if (!confirmRemove) {
                    this.checked = false;
                }
            }
        });
    }
    
    // Add form submission validation
    const forms = document.querySelectorAll('form');
    forms.forEach(function(form) {
        form.addEventListener('submit', function(e) {
            // Check if this is the school info form and a file is selected
            const fileInput = form.querySelector('input[type="file"]');
            if (fileInput && fileInput.files.length > 0) {
                const file = fileInput.files[0];
                const maxSize = 2 * 1024 * 1024; // 2MB in bytes
                
                if (file.size > maxSize) {
                    alert('File size too large. Please upload an image smaller than 2MB.');
                    e.preventDefault();
                    return false;
                }
                
                const allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
                if (allowedTypes.indexOf(file.type) === -1) {
                    alert('Invalid file type. Please upload a JPG, PNG, or GIF image.');
                    e.preventDefault();
                    return false;
                }
            }
        });
    });
    
    // Grading System functionality
    const addScaleBtn = document.getElementById('add-scale-btn');
    const addScaleForm = document.getElementById('add-scale-form');
    const cancelScaleBtn = document.getElementById('cancel-scale-btn');
    
    // Show/hide add scale form
    if (addScaleBtn && addScaleForm) {
        addScaleBtn.addEventListener('click', function() {
            addScaleForm.classList.toggle('hidden');
        });
    }
    
    // Cancel button for scale form
    if (cancelScaleBtn && addScaleForm) {
        cancelScaleBtn.addEventListener('click', function() {
            addScaleForm.classList.add('hidden');
        });
    }
    
    // Add rule functionality for each scale
    const addRuleButtons = document.querySelectorAll('.add-rule-btn');
    addRuleButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            const scaleId = this.getAttribute('data-scale-id');
            const ruleForm = document.getElementById('add-rule-form-' + scaleId);
            if (ruleForm) {
                ruleForm.classList.toggle('hidden');
            }
        });
    });
    
    // Cancel rule buttons
    const cancelRuleButtons = document.querySelectorAll('.cancel-rule-btn');
    cancelRuleButtons.forEach(function(button) {
        button.addEventListener('click', function() {
            const scaleId = this.getAttribute('data-scale-id');
            const ruleForm = document.getElementById('add-rule-form-' + scaleId);
            if (ruleForm) {
                ruleForm.classList.add('hidden');
            }
        });
    });
});
</script>

<?php 
$content = ob_get_clean();
include RESOURCES_PATH . '/layouts/app.php';
?>