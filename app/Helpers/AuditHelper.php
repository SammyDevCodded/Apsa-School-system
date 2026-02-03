<?php
namespace App\Helpers;

use App\Models\AuditLog;

class AuditHelper
{
    private static $auditLogModel = null;

    private static function getAuditLogModel()
    {
        if (self::$auditLogModel === null) {
            self::$auditLogModel = new AuditLog();
        }
        return self::$auditLogModel;
    }

    public static function log($userId, $action, $tableName, $recordId = null, $oldValues = null, $newValues = null, $academicYearId = null, $term = null)
    {
        $auditLogModel = self::getAuditLogModel();
        return $auditLogModel->log($userId, $action, $tableName, $recordId, $oldValues, $newValues, $academicYearId, $term);
    }
}