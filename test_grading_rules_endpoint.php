<?php
// Simulate the AJAX request to get grading rules
require 'config/config.php';

try {
    $scaleId = 1; // JSH scale
    
    $db = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
    $sql = "SELECT * FROM grading_rules WHERE scale_id = :scale_id ORDER BY min_score DESC";
    $stmt = $db->prepare($sql);
    $stmt->execute(['scale_id' => $scaleId]);
    $rules = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Grading rules for scale ID " . $scaleId . ":\n";
    foreach ($rules as $rule) {
        echo "Min: " . $rule['min_score'] . ", Max: " . $rule['max_score'] . ", Grade: " . $rule['grade'] . ", Remark: " . $rule['remark'] . "\n";
    }
    
    // Test the lookup function with sample marks
    function getGradeAndRemarkForMarks($marks, $rules) {
        $grade = '';
        $remark = '';
        
        // Check if marks is a valid number
        if ($marks === '' || !is_numeric($marks)) {
            return ['grade' => $grade, 'remark' => $remark];
        }
        
        // Convert marks to float for comparison
        $marksFloat = floatval($marks);
        
        // Loop through grading rules in reverse order to find the first matching rule
        for ($i = count($rules) - 1; $i >= 0; $i--) {
            $rule = $rules[$i];
            if ($marksFloat >= floatval($rule['min_score']) && $marksFloat <= floatval($rule['max_score'])) {
                $grade = $rule['grade'];
                $remark = $rule['remark'];
                break;
            }
        }
        
        return ['grade' => $grade, 'remark' => $remark];
    }
    
    // Test with some sample marks
    $testMarks = [85, 75, 65, 55, 45, 25];
    echo "\nTesting grade/remark lookup:\n";
    foreach ($testMarks as $marks) {
        $result = getGradeAndRemarkForMarks($marks, $rules);
        echo "Marks: " . $marks . " -> Grade: " . $result['grade'] . ", Remark: " . $result['remark'] . "\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}