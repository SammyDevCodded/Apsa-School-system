<?php
namespace App\Models;

use App\Core\Model;

class Setting extends Model
{
    protected $table = 'settings';
    protected $fillable = [
        'school_name',
        'school_address',
        'school_logo',
        'currency_code',
        'currency_symbol',
        'watermark_type',
        'watermark_position',
        'watermark_transparency',
        'student_admission_prefix',
        'staff_employee_prefix'
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
    
    public function getCurrency()
    {
        $settings = $this->getSettings();
        if ($settings) {
            return [
                'code' => $settings['currency_code'] ?? 'GHS',
                'symbol' => $settings['currency_symbol'] ?? 'GH₵'
            ];
        }
        
        return [
            'code' => 'GHS',
            'symbol' => 'GH₵'
        ];
    }
    
    public function getWatermarkSettings()
    {
        $settings = $this->getSettings();
        if ($settings) {
            return [
                'type' => $settings['watermark_type'] ?? 'none',
                'position' => $settings['watermark_position'] ?? 'center',
                'transparency' => $settings['watermark_transparency'] ?? 20
            ];
        }
        
        return [
            'type' => 'none',
            'position' => 'center',
            'transparency' => 20
        ];
    }
}