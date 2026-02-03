<?php 
$title = 'About'; 
ob_start(); 
?>

<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <h1 class="text-2xl font-semibold text-gray-900">About <?= APP_NAME ?></h1>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">Futuristic School Management ERP System (Localhost Edition)</p>
            </div>
            <div class="border-t border-gray-200">
                <div class="px-4 py-5 sm:p-6">
                    <div class="prose max-w-none">
                        <p>
                            The <strong>Futuristic School Management ERP (Localhost Edition)</strong> is a reengineered offline-first school management system designed to run exclusively on a <strong>WAMP server</strong>. 
                            It focuses on <strong>data storage, analytics, and management automation</strong> within a secure local environment. 
                            This version removes all external integrations such as online payments, cloud APIs, email, or SMS — ensuring the system operates entirely offline while maintaining modern architecture and futuristic usability.
                        </p>

                        <h2 class="text-xl font-semibold text-gray-900 mt-6">Key Features</h2>
                        <ul class="list-disc pl-5 space-y-2">
                            <li>Complete offline operation with no external dependencies</li>
                            <li>Modern web interface with responsive design</li>
                            <li>Comprehensive student, staff, and academic management</li>
                            <li>Financial tracking and reporting capabilities</li>
                            <li>Attendance tracking and reporting</li>
                            <li>Exam and result management</li>
                            <li>Timetable scheduling</li>
                            <li>Role-based access control</li>
                            <li>Audit trails and activity logging</li>
                            <li>Automated database backups</li>
                            <li>Real-time notifications</li>
                            <li>Detailed reporting and analytics</li>
                        </ul>

                        <h2 class="text-xl font-semibold text-gray-900 mt-6">Technology Stack</h2>
                        <ul class="list-disc pl-5 space-y-2">
                            <li><strong>Backend:</strong> PHP 7.4.33 with custom MVC framework</li>
                            <li><strong>Frontend:</strong> Tailwind CSS for responsive UI</li>
                            <li><strong>Database:</strong> MySQL 8 / MariaDB</li>
                            <li><strong>Authentication:</strong> Session-based with password hashing</li>
                            <li><strong>Export:</strong> CSV, Excel, PDF, Print capabilities</li>
                        </ul>

                        <h2 class="text-xl font-semibold text-gray-900 mt-6">Security Features</h2>
                        <ul class="list-disc pl-5 space-y-2">
                            <li>Password hashing with bcrypt/argon2</li>
                            <li>Session-based protection</li>
                            <li>Role and permission validation</li>
                            <li>CSRF protection for forms</li>
                            <li>Sanitized inputs and parameterized queries</li>
                            <li>Database encryption for sensitive data</li>
                            <li>Encrypted database backups</li>
                        </ul>

                        <h2 class="text-xl font-semibold text-gray-900 mt-6">System Information</h2>
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 mt-4">
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h3 class="font-medium text-gray-900">Application</h3>
                                <p class="mt-1 text-gray-600"><?= APP_NAME ?></p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h3 class="font-medium text-gray-900">PHP Version</h3>
                                <p class="mt-1 text-gray-600"><?= phpversion() ?></p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h3 class="font-medium text-gray-900">Server Software</h3>
                                <p class="mt-1 text-gray-600"><?= $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown' ?></p>
                            </div>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <h3 class="font-medium text-gray-900">Database</h3>
                                <p class="mt-1 text-gray-600">
                                    <?php
                                    try {
                                        $pdo = new PDO("mysql:host=localhost;dbname=school_erp", 'root', '');
                                        $stmt = $pdo->query("SELECT VERSION()");
                                        $version = $stmt->fetchColumn();
                                        echo $version;
                                    } catch (Exception $e) {
                                        echo 'Unable to connect to database';
                                    }
                                    ?>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
$content = ob_get_clean();
include RESOURCES_PATH . '/layouts/app.php';
?>