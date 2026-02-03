<?php
namespace App\Controllers\Portal;

use App\Core\Controller;

class StaffPortalController extends Controller
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
        // Assuming Model has a general select/where capability or we use raw query if needed
        // Checking Staff model, it extends Model. Let's try to find by user_id.
        // If 'where' method returns array of results:
        $staffRecords = $this->staffModel->where('user_id', $userId); // Standard Model method usually?
        // If generic Model doesn't have 'where', we might need to add getByUserId to Staff model? 
        // Let's assume generic 'where' exists or add custom query. Model.php usually has simple where.
        
        $staff = !empty($staffRecords) ? $staffRecords[0] : null;

        $timetable = [];
        $todaySchedule = [];
        
        if ($staff) {
            $timetableRaw = $this->timetableModel->getFiltered(null, $staff['id'], null);
            
            // Organize for view
            // e.g. Group by Day
            foreach ($timetableRaw as $slot) {
                $timetable[$slot['day_of_week']][] = $slot;
                
                // Check if today
                if (strtolower($slot['day_of_week']) === strtolower(date('l'))) {
                    $todaySchedule[] = $slot;
                }
            }
        }

        $this->view('portal/staff/dashboard', [
            'staff' => $staff,
            'my_timetable' => $timetable,
            'today_schedule' => $todaySchedule
        ]);
    }
}
