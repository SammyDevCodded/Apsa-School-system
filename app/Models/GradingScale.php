<?php
namespace App\Models;

use App\Core\Model;

class GradingScale extends Model
{
    protected $table = 'grading_scales';
    protected $fillable = [
        'name',
        'grading_type'
    ];

    public function __construct()
    {
        parent::__construct();
    }

    public function getRules($scaleId)
    {
        $ruleModel = new GradingRule();
        return $ruleModel->where('scale_id', $scaleId);
    }

    public function getAllWithRules()
    {
        $scales = $this->all();
        $ruleModel = new GradingRule();
        
        foreach ($scales as &$scale) {
            $scale['rules'] = $ruleModel->where('scale_id', $scale['id']);
        }
        
        return $scales;
    }
}