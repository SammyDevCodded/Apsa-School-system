<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'Timetable' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background: white;
            font-family: sans-serif;
            -webkit-print-color-adjust: exact; 
            print-color-adjust: exact;
        }
        @media print {
            .no-print {
                display: none;
            }
            body {
                padding: 0;
            }
            .page-break {
                page-break-after: always;
            }
        }
    </style>
</head>
<body class="p-8 bg-white text-gray-900" onload="window.print()">

    <!-- Print Controls -->
    <div class="no-print fixed top-4 right-4 flex gap-2">
        <button onclick="window.print()" class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700">Print</button>
        <button onclick="window.close()" class="bg-gray-500 text-white px-4 py-2 rounded shadow hover:bg-gray-600">Close</button>
    </div>

    <!-- Header -->
    <div class="mb-8 border-b pb-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <?php if (!empty($settings['school_logo'])): ?>
                    <img src="/uploads/settings/<?= htmlspecialchars($settings['school_logo']) ?>" alt="Logo" class="h-20 w-auto mr-4 object-contain">
                <?php endif; ?>
                <div>
                    <h1 class="text-3xl font-bold text-gray-900"><?= htmlspecialchars($settings['school_name'] ?? 'School Name') ?></h1>
                    <p class="text-sm text-gray-500"><?= htmlspecialchars($settings['school_address'] ?? '') ?></p>
                    <p class="text-sm text-gray-500"><?= htmlspecialchars($settings['school_phone'] ?? '') ?></p>
                </div>
            </div>
            <div class="text-right">
                <h2 class="text-2xl font-bold text-gray-800"><?= htmlspecialchars($pageTitle) ?></h2>
                <h3 class="text-lg font-medium text-gray-600"><?= htmlspecialchars($subTitle) ?></h3>
                <p class="text-sm text-gray-400 mt-1">Printed: <?= date('d M Y, h:i A') ?></p>
            </div>
        </div>
    </div>

    <!-- Timetable Grid -->
    <div class="w-full">
        <table class="min-w-full border-collapse border border-gray-300">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border border-gray-300 px-4 py-2 text-left text-sm font-semibold text-gray-700">Day</th>
                    <th class="border border-gray-300 px-4 py-2 text-left text-sm font-semibold text-gray-700">Time</th>
                    <th class="border border-gray-300 px-4 py-2 text-left text-sm font-semibold text-gray-700">Class</th>
                    <th class="border border-gray-300 px-4 py-2 text-left text-sm font-semibold text-gray-700">Subject</th>
                    <th class="border border-gray-300 px-4 py-2 text-left text-sm font-semibold text-gray-700">Teacher</th>
                    <th class="border border-gray-300 px-4 py-2 text-left text-sm font-semibold text-gray-700">Room</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($timetables)): ?>
                    <tr>
                        <td colspan="6" class="border border-gray-300 px-4 py-8 text-center text-gray-500">
                            No timetable entries found.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php 
                    // Sort by Day then Time
                    $dayOrder = ['monday' => 1, 'tuesday' => 2, 'wednesday' => 3, 'thursday' => 4, 'friday' => 5, 'saturday' => 6, 'sunday' => 7];
                    usort($timetables, function($a, $b) use ($dayOrder) {
                        $da = $dayOrder[strtolower($a['day_of_week'])] ?? 99;
                        $db = $dayOrder[strtolower($b['day_of_week'])] ?? 99;
                        if ($da === $db) {
                            return strcmp($a['start_time'], $b['start_time']);
                        }
                        return $da - $db;
                    });
                    ?>

                    <?php foreach ($timetables as $entry): ?>
                        <tr>
                            <td class="border border-gray-300 px-4 py-2 text-sm text-gray-900 font-medium whitespace-nowrap">
                                <?= ucfirst(htmlspecialchars($entry['day_of_week'])) ?>
                            </td>
                            <td class="border border-gray-300 px-4 py-2 text-sm text-gray-600 whitespace-nowrap">
                                <?= htmlspecialchars(date('h:i A', strtotime($entry['start_time']))) ?> - <?= htmlspecialchars(date('h:i A', strtotime($entry['end_time']))) ?>
                            </td>
                            <td class="border border-gray-300 px-4 py-2 text-sm text-gray-900">
                                <?= htmlspecialchars($entry['class_name'] ?? 'N/A') ?>
                            </td>
                            <td class="border border-gray-300 px-4 py-2 text-sm text-gray-900 font-medium">
                                <?= htmlspecialchars($entry['subject_name'] ?? 'N/A') ?>
                            </td>
                            <td class="border border-gray-300 px-4 py-2 text-sm text-gray-600">
                                <?= htmlspecialchars(($entry['staff_first_name'] ?? '') . ' ' . ($entry['staff_last_name'] ?? '')) ?>
                            </td>
                            <td class="border border-gray-300 px-4 py-2 text-sm text-gray-500">
                                <?= htmlspecialchars($entry['room'] ?? '-') ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="mt-8 border-t pt-4 text-center text-xs text-gray-400">
        <p>&copy; <?= date('Y') ?> <?= htmlspecialchars($settings['school_name'] ?? 'School Management System') ?>. All rights reserved.</p>
    </div>

</body>
</html>
