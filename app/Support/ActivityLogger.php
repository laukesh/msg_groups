<?php

namespace App\Support;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Throwable;

class ActivityLogger
{
    /**
     * Generic activity logger.
     *
     * Compatible with existing code such as:
     *
     * ActivityLogger::log(
     *     'logout',
     *     'Authentication',
     *     'User logged out'
     * );
     */
    public static function log(
        string $action,
        string $module,
        string $description,
        ?int $userId = null,
        ?array $oldValues = null,
        ?array $newValues = null,
        ?Model $subject = null
    ): void {
        try {

            // Never log the audit log itself.
            if ($subject instanceof ActivityLog) {
                return;
            }

            ActivityLog::create([
                'user_id' => $userId ?? auth()->id(),

                'action' => strtolower($action),

                'module' => $module,

                'description' => $description,

                'subject_type' => $subject
                    ? get_class($subject)
                    : null,

                'subject_id' => $subject
                    ? $subject->getKey()
                    : null,

                'route' => request()->route()?->getName(),

                'method' => request()->method(),

                'ip_address' => request()->ip(),

                'user_agent' => request()->userAgent(),

                'old_values' => self::sanitize($oldValues),

                'new_values' => self::sanitize($newValues),
            ]);

        } catch (Throwable $e) {

            /*
            |--------------------------------------------------------------------------
            | Logging must never break the actual application.
            |--------------------------------------------------------------------------
            */

            report($e);
        }
    }


    /**
     * Log a model activity.
     */
    public static function model(
        Model $model,
        string $action,
        ?array $oldValues = null,
        ?array $newValues = null
    ): void {

        try {

            /*
            |--------------------------------------------------------------------------
            | Ignore ActivityLog itself
            |--------------------------------------------------------------------------
            */

            if ($model instanceof ActivityLog) {
                return;
            }

            /*
            |--------------------------------------------------------------------------
            | Ignore UserStatusAudit
            |--------------------------------------------------------------------------
            */

            if (class_basename($model) === 'UserStatusAudit') {
                return;
            }

            /*
            |--------------------------------------------------------------------------
            | Do not create audit records from Artisan commands.
            |--------------------------------------------------------------------------
            */

            if (app()->runningInConsole()) {
                return;
            }

            /*
            |--------------------------------------------------------------------------
            | Module
            |--------------------------------------------------------------------------
            */

            $module = self::getModuleName($model);

            /*
            |--------------------------------------------------------------------------
            | Record label
            |--------------------------------------------------------------------------
            */

            $label = self::getModelLabel($model);

            /*
            |--------------------------------------------------------------------------
            | Description
            |--------------------------------------------------------------------------
            */

            $description = ucfirst($action)
                . ' '
                . Str::singular($module)
                . ' '
                . $label;

            /*
            |--------------------------------------------------------------------------
            | Save
            |--------------------------------------------------------------------------
            */

            self::log(
                action: $action,
                module: $module,
                description: $description,
                userId: auth()->id(),
                oldValues: $oldValues,
                newValues: $newValues,
                subject: $model
            );

        } catch (Throwable $e) {

            report($e);
        }
    }


    /**
     * Custom activity.
     */
    public static function custom(
        string $action,
        string $module,
        string $description,
        ?int $userId = null,
        ?array $oldValues = null,
        ?array $newValues = null,
        ?Model $subject = null
    ): void {

        self::log(
            action: $action,
            module: $module,
            description: $description,
            userId: $userId,
            oldValues: $oldValues,
            newValues: $newValues,
            subject: $subject
        );
    }


    /**
     * Determine module automatically.
     */
    protected static function getModuleName(Model $model): string
    {
        $class = class_basename($model);

        $map = [

            'User' => 'Users',

            'Role' => 'Roles',

            'Permission' => 'Permissions',

            'Mall' => 'Malls',

            'Building' => 'Buildings',

            'Floor' => 'Floors',

            'Zone' => 'Zones',

            'Unit' => 'Units',

            'UnitType' => 'Unit Types',

            'UnitStatus' => 'Unit Statuses',

            'Department' => 'Departments',

            'Asset' => 'Assets',

            'AssetCategory' => 'Asset Categories',

            'AssetIncome' => 'Asset Income',

            'AssetExpense' => 'Asset Expenses',

            'Tenant' => 'Tenants',

            'Lease' => 'Leases',

            'LeaseAgreement' => 'Lease Agreements',

            'LeaseProposal' => 'Lease Proposals',

            'Invoice' => 'Invoices',

            'Payment' => 'Payments',

            'Utility' => 'Utilities',

            'Complaint' => 'Complaints',

            'Project' => 'Projects',

            'Contract' => 'Contracts',

            'Procurement' => 'Procurement',

            'ActivityLog' => 'Activity Logs',
        ];

        if (isset($map[$class])) {
            return $map[$class];
        }

        /*
        |--------------------------------------------------------------------------
        | Automatic fallback
        |--------------------------------------------------------------------------
        */

        return Str::plural(
            Str::headline($class)
        );
    }


    /**
     * Get readable model name.
     */
    protected static function getModelLabel(Model $model): string
    {
        $attributes = $model->getAttributes();

        foreach ([
            'name',
            'title',
            'asset_code',
            'code',
            'unit_code',
            'building_code',
            'floor_code',
            'zone_code',
            'tenant_code',
            'invoice_no',
            'reference_no',
            'email',
        ] as $field) {

            if (
                isset($attributes[$field]) &&
                $attributes[$field] !== ''
            ) {
                return '"' . $attributes[$field] . '"';
            }
        }

        return '#' . $model->getKey();
    }


    /**
     * Remove sensitive information.
     */
    protected static function sanitize(?array $values): ?array
    {
        if (!$values) {
            return $values;
        }

        $hidden = [
            'password',
            'password_confirmation',
            'remember_token',
            'api_token',
            'access_token',
            'refresh_token',
            'token',
            'secret',
            'two_factor_secret',
            'two_factor_recovery_codes',
        ];

        foreach ($hidden as $field) {
            unset($values[$field]);
        }

        return $values;
    }
}