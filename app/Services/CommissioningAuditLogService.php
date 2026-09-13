<?php

namespace App\Services;

use App\Models\CommissioningAuditLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class CommissioningAuditLogService
{
    public static function log(
        string $action,
        Model $model,
        ?string $description = null,
        ?array $oldValues = null,
        ?array $newValues = null,
        ?int $projectId = null
    ): CommissioningAuditLog {

        /*
        |--------------------------------------------------------------------------
        | Determine Project
        |--------------------------------------------------------------------------
        */

        if (!$projectId) {
            $projectId = $model->project_id ?? null;
        }

        if (!$projectId && method_exists($model, 'project')) {
            try {
                $projectId = $model->project?->id;
            } catch (\Throwable $e) {
                $projectId = null;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | User
        |--------------------------------------------------------------------------
        */

        $userId = Auth::id();

        /*
        |--------------------------------------------------------------------------
        | Create Audit Record
        |--------------------------------------------------------------------------
        */

        return CommissioningAuditLog::create([
            'project_id'     => $projectId,
            'user_id'        => $userId,

            'action'         => $action,

            'auditable_type' => get_class($model),
            'auditable_id'   => $model->getKey(),

            'old_values'     => $oldValues,
            'new_values'     => $newValues,

            'description'    => $description,

            'ip_address'     => request()->ip(),
            'user_agent'     => request()->userAgent(),

            'created_at'     => now(),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Created
    |--------------------------------------------------------------------------
    */

    public static function created(
        Model $model,
        ?string $description = null
    ): CommissioningAuditLog {

        return self::log(
            'created',
            $model,
            $description,
            null,
            $model->getAttributes()
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Updated
    |--------------------------------------------------------------------------
    */

    public static function updated(
        Model $model,
        array $oldValues = [],
        ?string $description = null
    ): CommissioningAuditLog {

        return self::log(
            'updated',
            $model,
            $description,
            $oldValues,
            $model->getAttributes()
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Deleted
    |--------------------------------------------------------------------------
    */

    public static function deleted(
        Model $model,
        array $oldValues = [],
        ?string $description = null
    ): CommissioningAuditLog {

        return self::log(
            'deleted',
            $model,
            $description,
            $oldValues,
            null
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Submitted
    |--------------------------------------------------------------------------
    */

    public static function submitted(
        Model $model,
        ?string $description = null
    ): CommissioningAuditLog {

        return self::log(
            'submitted',
            $model,
            $description
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Approved
    |--------------------------------------------------------------------------
    */

    public static function approved(
        Model $model,
        ?string $description = null
    ): CommissioningAuditLog {

        return self::log(
            'approved',
            $model,
            $description
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Rejected
    |--------------------------------------------------------------------------
    */

    public static function rejected(
        Model $model,
        ?string $description = null
    ): CommissioningAuditLog {

        return self::log(
            'rejected',
            $model,
            $description
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Cancelled
    |--------------------------------------------------------------------------
    */

    public static function cancelled(
        Model $model,
        ?string $description = null
    ): CommissioningAuditLog {

        return self::log(
            'cancelled',
            $model,
            $description
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Custom Workflow Action
    |--------------------------------------------------------------------------
    */

    public static function action(
        string $action,
        Model $model,
        ?string $description = null,
        ?array $oldValues = null,
        ?array $newValues = null
    ): CommissioningAuditLog {

        return self::log(
            $action,
            $model,
            $description,
            $oldValues,
            $newValues
        );
    }
}