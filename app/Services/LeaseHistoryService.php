<?php

namespace App\Services;

use App\Models\LeaseHistory;

class LeaseHistoryService
{
    public static function log(
        int $agreementId,
        string $activityType,
        string $title,
        ?string $description = null,
        $oldValue = null,
        $newValue = null,
        ?string $referenceModule = null,
        ?int $referenceId = null,
        ?string $remarks = null
    ) {
        return LeaseHistory::create([

            'lease_agreement_id' =>
                $agreementId,

            'activity_type' =>
                $activityType,

            'reference_module' =>
                $referenceModule,

            'reference_id' =>
                $referenceId,

            'activity_title' =>
                $title,

            'activity_description' =>
                $description,

            'old_value' =>
                self::convertValue($oldValue),

            'new_value' =>
                self::convertValue($newValue),

            'activity_date' =>
                now(),

            'performed_by' =>
                auth()->id(),

            'ip_address' =>
                request()->ip(),

            'device_info' =>
                request()->userAgent(),

            'remarks' =>
                $remarks,

            'created_at' =>
                now(),
        ]);
    }


    private static function convertValue($value)
    {
        if ($value === null) {
            return null;
        }

        if (is_array($value) || is_object($value)) {
            return json_encode(
                $value,
                JSON_UNESCAPED_UNICODE
            );
        }

        return (string) $value;
    }
}