<?php
namespace App\Models;

use App\Core\Model;

class ReportCardSetting extends Model
{
    protected $table = 'report_card_settings';
    protected $fillable = [
        'logo_position',
        'show_school_name',
        'show_school_address',
        'custom_school_address',
        'show_school_logo',
        'show_student_photo',
        'show_grading_scale',
        'show_attendance',
        'show_comments',
        'show_signatures',
        'header_font_size',
        'body_font_size',
        'show_date_of_birth',
        'show_class_score',
        'show_exam_score',
        'show_teacher_signature',
        'teacher_signature',
        'show_headteacher_signature',
        'headteacher_signature',
        'show_parent_signature',
        'show_position'
    ];

    public function __construct()
    {
        parent::__construct();
    }

    public function getSettings()
    {
        $sql = "SELECT * FROM {$this->table} WHERE id = 1 LIMIT 1";
        return $this->db->fetchOne($sql);
    }

    public function updateSettings($data)
    {
        // Remove the id from data if it exists, as it's not needed for the update
        unset($data['id']);
        
        // Only update the first record
        return $this->update(1, $data);
    }
}