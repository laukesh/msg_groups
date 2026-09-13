<?php

namespace App\Services;

use App\Models\RevenueAuditLog;
use Illuminate\Support\Facades\Auth;

class RevenueAuditService
{
    public static function log(
        string $module,
        string $action,
        ?string $referenceType = null,
        ?int $referenceId = null,
        ?string $description = null,
        ?array $oldValues = null,
        ?array $newValues = null
    ): RevenueAuditLog {

        return RevenueAuditLog::create([

            'user_id' =>
                Auth::id(),

            'module' =>
                $module,

            'action' =>
                $action,

            'reference_type' =>
                $referenceType,

            'reference_id' =>
                $referenceId,

            'description' =>
                $description,

            'old_values' =>
                $oldValues,

            'new_values' =>
                $newValues,

            'ip_address' =>
                request()->ip(),

            'created_at' =>
                now(),

        ]);
    }
}