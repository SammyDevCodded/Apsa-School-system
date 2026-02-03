<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\AcademicYear;
use App\Models\Student;
use App\Models\Staff;
use App\Models\Payment;
use App\Models\ExamResult;
use App\Models\Promotion;

class SchoolDatabaseController extends Controller
{
    public function index()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }
        
        $academicYearModel = new AcademicYear();
        // unique handling: fetch ALL years, but we will highlight completed ones in the UI
        $academicYears = $academicYearModel->getAll();
        
        $selectedYearId = $this->get('academic_year_id');
        $tab = $this->get('tab', 'students');
        $search = $this->get('search', '');
        
        $data = [];
        
        if ($selectedYearId) {
            // Fetch data based on selected year
            switch ($tab) {
                case 'students':
                    $studentModel = new Student();
                    // Just basic search for now, filtered later by history logic if we had it
                    // For now, "School Database" allows searching ALL students but contextually for that year if possible
                    // Since we don't have a specific "student_history" table, we just list students.
                    // Ideally, we'd filter students who were active in that year.
                    // For this MVP, we will list all student records but allow searching.
                    // A better approach: Promotion table can link students to years.
                    
                    if ($search) {
                         $data = $studentModel->search($search);
                    } else {
                         // Pagination could be added here
                         $data = $studentModel->getAllWithClass();
                    }
                    break;
                    
                case 'staff':
                    $staffModel = new Staff();
                    $data = $staffModel->getAllWithUser();
                    break;
                    
                case 'financials':
                    $paymentModel = new Payment();
                    // Filter payments by the selected academic year
                    $filters = ['academic_year_id' => $selectedYearId];
                    if ($search) {
                        $data = $paymentModel->getAllWithDetails($filters, $search);
                    } else {
                        $data = $paymentModel->getAllWithDetails($filters);
                    }
                    break;
                    
                case 'academics':
                    // This would ideally be exam results for that year
                    $examResultModel = new ExamResult();
                    // Assuming we can filter by academic year on results/exams
                    // This might require a custom query if getByAcademicYear doesn't exist
                    // For MVP, passing empty/placeholder
                    $data = []; 
                    break;
            }
        }
        
        $this->view('school_database/index', [
            'academicYears' => $academicYears,
            'selectedYearId' => $selectedYearId,
            'tab' => $tab,
            'data' => $data,
            'search' => $search
        ]);
    }
}
