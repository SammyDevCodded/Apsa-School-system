<?php 
$title = 'Edit Exam Result'; 
ob_start(); 
?>

<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Edit Exam Result</h1>
            <a href="/exam_results" class="bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-700">
                Back to Results
            </a>
        </div>

        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <form action="/exam_results/<?= $result['id'] ?>" method="POST" class="space-y-6">
                    <input type="hidden" name="_method" value="PUT">
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label for="exam_id" class="block text-sm font-medium text-gray-700">Exam</label>
                            <select name="exam_id" id="exam_id" required
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">Select Exam</option>
                                <?php foreach ($exams ?? [] as $exam): ?>
                                    <option value="<?= $exam['id'] ?>" <?= (isset($result['exam_id']) && $result['exam_id'] == $exam['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($exam['name'] . ' (' . date('M j, Y', strtotime($exam['date'])) . ')') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div>
                            <label for="student_id" class="block text-sm font-medium text-gray-700">Student</label>
                            <select name="student_id" id="student_id" required
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">Select Student</option>
                                <?php foreach ($students ?? [] as $student): ?>
                                    <option value="<?= $student['id'] ?>" <?= (isset($result['student_id']) && $result['student_id'] == $student['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($student['first_name'] . ' ' . $student['last_name'] . ' (' . $student['admission_no'] . ')') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div>
                            <label for="subject_id" class="block text-sm font-medium text-gray-700">Subject</label>
                            <select name="subject_id" id="subject_id" required
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">Select Subject</option>
                                <?php foreach ($subjects ?? [] as $subject): ?>
                                    <option value="<?= $subject['id'] ?>" <?= (isset($result['subject_id']) && $result['subject_id'] == $subject['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($subject['name'] . ' (' . $subject['code'] . ')') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div>
                            <label for="marks" class="block text-sm font-medium text-gray-700">Marks (0-100)</label>
                            <input type="number" step="0.01" min="0" max="100" name="marks" id="marks" value="<?= htmlspecialchars($result['marks'] ?? '') ?>" required
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                            class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Update Result
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php 
$content = ob_get_clean();
if (empty($isAjax) || !$isAjax) {
    include RESOURCES_PATH . '/layouts/app.php';
} else {
    echo $content;
}
?>