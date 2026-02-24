<?php
namespace App\Controllers\Portal;

use App\Core\Controller;

class StaffPortalController extends Controller
{
    private $staffModel;
    private $timetableModel;

    public function __construct()
    {
        if (!isset($_SESSION['staff_portal_logged_in'])) {
            $this->redirect('/portal/login');
        }
        
        $this->staffModel = new \App\Models\Staff();
        $this->timetableModel = new \App\Models\Timetable();
    }

    public function dashboard()
    {
        $userId = $_SESSION['user_id'];
        
        // Find staff record associated with this user
        $staffRecords = $this->staffModel->where('user_id', $userId);
        $staff = !empty($staffRecords) ? $staffRecords[0] : null;

        $timetable = [];
        $todaySchedule = [];
        $subjects = [];
        $recentExams = [];
        
        if ($staff) {
            // Get Timetable
            $timetableRaw = $this->timetableModel->getFiltered(null, $staff['id'], null);
            
            foreach ($timetableRaw as $slot) {
                // Normalize day key to Title Case (e.g., "Monday")
                $dayKey = ucfirst(strtolower($slot['day_of_week']));
                $timetable[$dayKey][] = $slot;
                if (strtolower($slot['day_of_week']) === strtolower(date('l'))) {
                    $todaySchedule[] = $slot;
                }
            }

            // Get Subjects
            $subjects = $this->staffModel->getSubjects($staff['id']);
            
            // Get Recent Exams (if subjects exist)
            if (!empty($subjects)) {
                $subjectIds = array_column($subjects, 'id');
                $examModel = new \App\Models\Exam();
                $recentExams = $examModel->getBySubjects($subjectIds, 5);
            }
        }

        $this->view('portal/staff/dashboard', [
            'staff' => $staff,
            'my_timetable' => $timetable,
            'today_schedule' => $todaySchedule,
            'my_subjects' => $subjects,
            'recent_exams' => $recentExams,
            'portal_role' => 'staff' // Explicitly set role
        ]);
    }

    public function getProfileData()
    {
        $userId = $_SESSION['user_id'];
        $staffRecords = $this->staffModel->where('user_id', $userId);
        $staff = !empty($staffRecords) ? $staffRecords[0] : null;

        // Return JSON
        header('Content-Type: application/json');
        echo json_encode(['staff' => $staff]);
        exit;
    }

    public function timetable()
    {
        $userId = $_SESSION['user_id'];
        $staffRecords = $this->staffModel->where('user_id', $userId);
        $staff = !empty($staffRecords) ? $staffRecords[0] : null;

        $timetable = [];
        if ($staff) {
            $timetableRaw = $this->timetableModel->getFiltered(null, $staff['id'], null);
            foreach ($timetableRaw as $slot) {
                // Normalize day key to Title Case
                $dayKey = ucfirst(strtolower($slot['day_of_week']));
                $timetable[$dayKey][] = $slot;
            }
        }

        $this->view('portal/staff/timetable', [
            'staff' => $staff,
            'my_timetable' => $timetable,
            'portal_role' => 'staff'
        ]);
    }

    public function academics()
    {
        $userId = $_SESSION['user_id'];
        $staffRecords = $this->staffModel->where('user_id', $userId);
        $staff = !empty($staffRecords) ? $staffRecords[0] : null;

        $subjects = [];
        $recentExams = [];
        $studentPerformance = [];
        
        if ($staff) {
            $subjects = $this->staffModel->getSubjects($staff['id']);
             if (!empty($subjects)) {
                $subjectIds = array_column($subjects, 'id');
                
                // Recent Exams
                $examModel = new \App\Models\Exam();
                $recentExams = $examModel->getBySubjects($subjectIds, 10);
                
                // Student Performance
                $examResultModel = new \App\Models\ExamResult();
                $rawResults = $examResultModel->getDetailedResultsBySubjects($subjectIds);
                
                // Group by Academic Year -> Term -> Subject
                foreach ($rawResults as $row) {
                    $year = $row['academic_year_name'] ?? 'Unknown Year';
                    $term = $row['term'] ?? 'Unknown Term';
                    $subject = $row['subject_name'];
                    
                    if (!isset($studentPerformance[$year])) {
                        $studentPerformance[$year] = [];
                    }
                    if (!isset($studentPerformance[$year][$term])) {
                        $studentPerformance[$year][$term] = [];
                    }
                    if (!isset($studentPerformance[$year][$term][$subject])) {
                        $studentPerformance[$year][$term][$subject] = [];
                    }
                    
                    $studentPerformance[$year][$term][$subject][] = $row;
                }
            }
        }

        $this->view('portal/staff/academics', [
            'staff' => $staff,
            'my_subjects' => $subjects,
            'recent_exams' => $recentExams,
            'student_performance' => $studentPerformance,
            'portal_role' => 'staff'
        ]);
    }

    // JSON Endpoints for Modals
    public function getSubjectsData()
    {
        $userId = $_SESSION['user_id'];
        $staffRecords = $this->staffModel->where('user_id', $userId);
        $staff = !empty($staffRecords) ? $staffRecords[0] : null;

        $subjects = [];
        if ($staff) {
            $subjects = $this->staffModel->getSubjects($staff['id']);
        }
        
        header('Content-Type: application/json');
        echo json_encode(['subjects' => $subjects]);
        exit;
    }

    public function getTimetableData()
    {
        $userId = $_SESSION['user_id'];
        $staffRecords = $this->staffModel->where('user_id', $userId);
        $staff = !empty($staffRecords) ? $staffRecords[0] : null;

        $timetable = [];
        if ($staff) {
            $timetableRaw = $this->timetableModel->getFiltered(null, $staff['id'], null);
             foreach ($timetableRaw as $slot) {
                // Normalize day key to Title Case
                $dayKey = ucfirst(strtolower($slot['day_of_week'])); 
                $timetable[$dayKey][] = $slot;
            }
        }
        
        header('Content-Type: application/json');
        echo json_encode(['timetable' => $timetable]);
        exit;
    }
}
