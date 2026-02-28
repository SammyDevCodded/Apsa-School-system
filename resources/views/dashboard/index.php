<?php 
$title = 'Dashboard'; 
ob_start(); 
?>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <!-- Welcome Section -->
        <div class="glass-morphism rounded-2xl p-6 mb-8 shadow-lg border-l-4 border-indigo-600 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Welcome back, <?= htmlspecialchars($user['first_name'] ?? 'User') ?>! 👋</h1>
                <p class="mt-2 text-gray-600">Here's what's happening in your school today.</p>
            </div>
            <div class="text-right hidden md:block pl-6">
                <!-- Data attributes to store PHP time for JS -->
                <div id="dashboard-clock-wrapper" data-start-time="<?= $currentDateTime * 1000 ?>">
                    <div id="dashboard-clock-time" class="text-4xl font-bold text-indigo-700 tracking-tight font-mono leading-none">
                        <?= date('h:i:s A', $currentDateTime) ?>
                    </div>
                    <div id="dashboard-clock-date" class="text-sm font-medium text-gray-500 uppercase tracking-wider mt-1">
                        <?= date('l, F j, Y', $currentDateTime) ?>
                    </div>
                </div>
            </div>
        </div>
        <!-- Academic Year Alerts -->
        <?php if (isset($daysRemaining) && $daysRemaining !== null): ?>
            <?php if ($daysRemaining <= 7 && $daysRemaining > 0): ?>
            <div class="glass-morphism rounded-2xl p-6 mb-8 border-l-4 border-yellow-500 bg-yellow-50 bg-opacity-40">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-lg font-medium text-yellow-800">Academic Year Ending Soon</h3>
                        <div class="mt-2 text-yellow-700">
                            <p>The current academic year (<strong><?= htmlspecialchars($currentAcademicYear['name']) ?></strong>) ends in <strong><?= $daysRemaining ?> days</strong>.</p>
                            <p class="mt-1">Please ensure the next academic year is created and ready for activation.</p>
                        </div>
                        <div class="mt-4">
                            <a href="/academic_years" class="text-sm font-medium text-yellow-800 hover:text-yellow-600 underline">
                                Manage Academic Years
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php elseif ($daysRemaining === 0): ?>
            <div class="glass-morphism rounded-2xl p-6 mb-8 border-l-4 border-red-500 bg-red-50 bg-opacity-40">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-lg font-medium text-red-800">Academic Year Ended</h3>
                        <div class="mt-2 text-red-700">
                            <p>The current academic year (<strong><?= htmlspecialchars($currentAcademicYear['name']) ?></strong>) has ended.</p>
                            <p class="mt-1">Please mark it as completed and activate the new academic year to continue operations.</p>
                        </div>
                        <div class="mt-4">
                            <a href="/academic_years" class="text-sm font-medium text-red-800 hover:text-red-600 underline">
                                Go to Academic Years
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        <?php endif; ?>
        
        <!-- Stats Grid -->
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-8">
            <!-- Total Students -->
            <div class="glass-morphism rounded-xl p-5 border-l-4 border-indigo-500 hover:shadow-lg transition-shadow duration-300">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-indigo-100 rounded-lg p-3">
                        <svg class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Students</dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-bold text-gray-900"><?= $totalStudents ?? 0 ?></div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>

            <!-- Total Users -->
            <div class="glass-morphism rounded-xl p-5 border-l-4 border-green-500 hover:shadow-lg transition-shadow duration-300">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-green-100 rounded-lg p-3">
                        <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Users</dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-bold text-gray-900"><?= $totalUsers ?? 0 ?></div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>

            <!-- Total Classes -->
            <div class="glass-morphism rounded-xl p-5 border-l-4 border-yellow-500 hover:shadow-lg transition-shadow duration-300">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-yellow-100 rounded-lg p-3">
                        <svg class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Active Classes</dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-bold text-gray-900"><?= $totalClasses ?? 0 ?></div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>

            <!-- Total Staff -->
            <div class="glass-morphism rounded-xl p-5 border-l-4 border-purple-500 hover:shadow-lg transition-shadow duration-300">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-purple-100 rounded-lg p-3">
                        <svg class="h-6 w-6 text-purple-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Staff</dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-bold text-gray-900"><?= $totalStaff ?? 0 ?></div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <!-- Analytical Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
            <!-- Finance Chart -->
            <div class="glass-morphism rounded-xl p-6 lg:col-span-2 shadow-sm">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="h-5 w-5 mr-2 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z" />
                    </svg>
                    Revenue vs Expenditure (Last 6 Months)
                </h3>
                <div class="relative h-64 w-full">
                    <canvas id="financeChart"></canvas>
                </div>
            </div>

            <!-- Demographics Chart -->
            <div class="glass-morphism rounded-xl p-6 shadow-sm">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="h-5 w-5 mr-2 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                    </svg>
                    Student Demographics
                </h3>
                <div class="relative h-64 w-full flex justify-center">
                    <canvas id="genderChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Cash Book Flow Chart -->
        <div class="glass-morphism rounded-xl p-6 mb-8 shadow-sm">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Cash Book Flow (Credit vs Debit)</h3>
            <div class="relative h-64 w-full">
                <canvas id="cashbookChart"></canvas>
            </div>
        </div>

        <!-- Class Distribution Chart -->
        <div class="glass-morphism rounded-xl p-6 mb-8 shadow-sm">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Student Distribution by Class</h3>
            <div class="relative h-64 w-full">
                <canvas id="classChart"></canvas>
            </div>
        </div>

        <!-- Recent Activity Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Recent Students -->
            <div class="glass-morphism rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-200 border-opacity-30 flex justify-between items-center bg-gray-50 bg-opacity-30">
                    <h3 class="text-lg font-medium text-gray-900">Recent Students</h3>
                    <a href="/students" class="text-sm text-indigo-600 hover:text-indigo-900 font-medium">View All</a>
                </div>
                <ul class="divide-y divide-gray-200 divide-opacity-30">
                    <?php if (empty($recentStudents)): ?>
                        <li class="px-6 py-4 text-center text-sm text-gray-500">No students found.</li>
                    <?php else: ?>
                        <?php foreach ($recentStudents as $student): ?>
                        <li class="px-6 py-4 hover:bg-white hover:bg-opacity-40 transition-colors duration-150">
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0">
                                    <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold">
                                        <?= substr($student['first_name'], 0, 1) . substr($student['last_name'], 0, 1) ?>
                                    </div>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 truncate">
                                        <?= htmlspecialchars($student['first_name'] . ' ' . $student['last_name']) ?>
                                    </p>
                                    <p class="text-xs text-gray-500 truncate">
                                        Class: <?= htmlspecialchars($student['class_name'] ?? 'N/A') ?>
                                    </p>
                                </div>
                                <div>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Active
                                    </span>
                                </div>
                            </div>
                        </li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
            </div>

            <!-- Recent Notifications -->
            <div class="glass-morphism rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-5 border-b border-gray-200 border-opacity-30 flex justify-between items-center bg-gray-50 bg-opacity-30">
                    <h3 class="text-lg font-medium text-gray-900">Notifications</h3>
                    <a href="/notifications" class="text-sm text-indigo-600 hover:text-indigo-900 font-medium">View All</a>
                </div>
                <ul class="divide-y divide-gray-200 divide-opacity-30">
                    <?php if (empty($recentNotifications)): ?>
                        <li class="px-6 py-4 text-center text-sm text-gray-500">No new notifications.</li>
                    <?php else: ?>
                        <?php foreach ($recentNotifications as $notification): ?>
                        <li class="px-6 py-4 hover:bg-white hover:bg-opacity-40 transition-colors duration-150 <?= !$notification['is_read'] ? 'bg-blue-50 bg-opacity-30' : '' ?>">
                            <div class="flex space-x-3">
                                <div class="flex-shrink-0">
                                    <?php if (!$notification['is_read']): ?>
                                        <div class="h-2 w-2 mt-2 rounded-full bg-blue-600"></div>
                                    <?php endif; ?>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm text-gray-900">
                                        <?= htmlspecialchars($notification['message']) ?>
                                    </p>
                                    <p class="text-xs text-gray-500 mt-1">
                                        <?= date('M j, g:i A', strtotime($notification['created_at'])) ?>
                                    </p>
                                </div>
                            </div>
                        </li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Chart Data Integration -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Common Options
        Chart.defaults.font.family = "'Inter', system-ui, -apple-system, sans-serif";
        Chart.defaults.color = '#4B5563';
        // 1. Finance Chart (Revenue vs Expenditure)
        const financeCtx = document.getElementById('financeChart').getContext('2d');
        const rawRevenue = <?= json_encode(array_reverse($monthlyRevenue ?? [])) ?>;
        const rawExpenses = <?= json_encode(array_reverse($monthlyExpenses ?? [])) ?>;
        const currencySymbol = "<?= $currency['symbol'] ?? '$' ?>";
        
        // Merge labels dynamically to ensure timelines align even if there are gaps
        let allLabels = new Set();
        rawRevenue.forEach(item => allLabels.add(item.month_name));
        rawExpenses.forEach(item => allLabels.add(item.month_name));
        const mergedLabels = Array.from(allLabels).sort((a, b) => {
            // Sort chronologically using underlying month keys if necessary, or just rely on raw order
            return new Date(a) - new Date(b); 
        });

        const alignedRevenue = mergedLabels.map(label => {
            const found = rawRevenue.find(r => r.month_name === label);
            return found ? parseFloat(found.total_amount) : 0;
        });

        const alignedExpenses = mergedLabels.map(label => {
            const found = rawExpenses.find(e => e.month_name === label);
            return found ? parseFloat(found.total_amount) : 0;
        });
        
        new Chart(financeCtx, {
            type: 'line',
            data: {
                labels: mergedLabels,
                datasets: [
                    {
                        label: 'Revenue',
                        data: alignedRevenue,
                        borderColor: '#6366F1', // Indigo 500
                        backgroundColor: 'rgba(99, 102, 241, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#6366F1',
                        pointRadius: 4,
                        pointHoverRadius: 6
                    },
                    {
                        label: 'Expenditure',
                        data: alignedExpenses,
                        borderColor: '#EF4444', // Red 500
                        backgroundColor: 'rgba(239, 68, 68, 0.05)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#EF4444',
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { 
                        display: true,
                        position: 'top'
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        backgroundColor: 'rgba(255, 255, 255, 0.9)',
                        titleColor: '#1F2937',
                        bodyColor: '#4B5563',
                        borderColor: '#E5E7EB',
                        borderWidth: 1,
                        padding: 10,
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += currencySymbol + new Intl.NumberFormat('en-US').format(context.parsed.y);
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { borderDash: [2, 2], drawBorder: false },
                        ticks: { callback: function(value) { return currencySymbol + value; } }
                    },
                    x: {
                        grid: { display: false, drawBorder: false }
                    }
                }
            }
        });

        // 2. Demographics Chart (Doughnut)
        const genderCtx = document.getElementById('genderChart').getContext('2d');
        const stats = <?= json_encode($studentStats) ?>;
        
        new Chart(genderCtx, {
            type: 'doughnut',
            data: {
                labels: ['Male', 'Female'],
                datasets: [{
                    data: [stats.male_students || 0, stats.female_students || 0],
                    backgroundColor: [
                        '#3B82F6', // Blue 500
                        '#EC4899'  // Pink 500
                    ],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: { usePointStyle: true, padding: 20 }
                    }
                },
                cutout: '70%'
            }
        });

        // 3. Class Distribution Chart (Bar)
        const classCtx = document.getElementById('classChart').getContext('2d');
        const classes = <?= json_encode($studentsByClass) ?>;
        
        new Chart(classCtx, {
            type: 'bar',
            data: {
                labels: classes.map(c => c.class_name),
                datasets: [{
                    label: 'Students',
                    data: classes.map(c => c.student_count),
                    backgroundColor: 'rgba(16, 185, 129, 0.6)', // Green 500
                    borderColor: '#10B981',
                    borderWidth: 1,
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { borderDash: [2, 2], drawBorder: false }
                    },
                    x: {
                        grid: { display: false, drawBorder: false }
                    }
                }
            }
        });

        // 4. Cash Book Flow Chart (Bar)
        const cashbookCtx = document.getElementById('cashbookChart').getContext('2d');
        const cashbookFlow = <?= json_encode(array_reverse($cashbookFlow ?? [])) ?>;
        
        new Chart(cashbookCtx, {
            type: 'bar',
            data: {
                labels: cashbookFlow.map(c => c.month_name),
                datasets: [
                    {
                        label: 'Credit (Inflow)',
                        data: cashbookFlow.map(c => c.total_credit),
                        backgroundColor: 'rgba(16, 185, 129, 0.8)', // Green 500
                        borderRadius: 4
                    },
                    {
                        label: 'Debit (Outflow)',
                        data: cashbookFlow.map(c => c.total_debit),
                        backgroundColor: 'rgba(245, 158, 11, 0.8)', // Amber 500
                        borderRadius: 4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { 
                        display: true,
                        position: 'top' 
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false,
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    label += currencySymbol + new Intl.NumberFormat('en-US').format(context.parsed.y);
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { borderDash: [2, 2], drawBorder: false },
                        ticks: { callback: function(value) { return currencySymbol + value; } }
                    },
                    x: {
                        grid: { display: false, drawBorder: false }
                    }
                }
            }
        });
    });

    // Real-time Clock Logic
    document.addEventListener('DOMContentLoaded', function() {
        // Get elements
        const wrapper = document.getElementById('dashboard-clock-wrapper');
        const timeElement = document.getElementById('dashboard-clock-time');
        const dateElement = document.getElementById('dashboard-clock-date');
        
        if (wrapper && timeElement && dateElement) {
            // Get start time from server (passed via data attribute)
            // It's in milliseconds since epoch
            let currentTime = parseInt(wrapper.getAttribute('data-start-time'));
            
            function updateClock() {
                // Add 1 second (1000 ms)
                currentTime += 1000;
                
                const now = new Date(currentTime);
                
                // Format Time: HH:MM:SS AM/PM
                timeElement.textContent = now.toLocaleTimeString('en-US', { 
                    hour: '2-digit', 
                    minute: '2-digit', 
                    second: '2-digit',
                    hour12: true
                });
                
                // Format Date: Weekday, Month Day, Year
                dateElement.textContent = now.toLocaleDateString('en-US', { 
                    weekday: 'long', 
                    year: 'numeric', 
                    month: 'long', 
                    day: 'numeric' 
                });
            }
            
            // Update every second
            setInterval(updateClock, 1000);
        }
    });

</script>

<?php 
    $content = ob_get_clean();
    include RESOURCES_PATH . '/layouts/app.php';
    ?>