<?php

namespace App\Support;

class DesignManagementOptions
{
    public static function briefStatuses(): array
    {
        return ['Draft', 'Under Review', 'Approved', 'Rejected', 'Superseded'];
    }

    public static function packageStatuses(): array
    {
        return ['Draft', 'In Progress', 'Under Review', 'Approved', 'Rejected', 'Superseded'];
    }

    public static function drawingStatuses(): array
    {
        return ['Draft', 'Submitted', 'Under Review', 'Approved', 'Superseded', 'Rejected'];
    }

    public static function drawingTypes(): array
    {
        return [
            'General Arrangement',
            'Layout',
            'Detail',
            'Section',
            'Elevation',
            'Schedule',
            'Specification',
            'Other',
        ];
    }

    public static function submittalStatuses(): array
    {
        return ['Draft', 'Submitted', 'Under Review', 'Approved', 'Approved with Comments', 'Rejected', 'Resubmitted'];
    }

    public static function submittalDecisions(): array
    {
        return ['Approved', 'Approved with Comments', 'Rejected', 'Revise and Resubmit'];
    }

    public static function reviewStatuses(): array
    {
        return ['Draft', 'Under Review', 'Responded', 'Rejected', 'Closed'];
    }

    public static function reviewDecisions(): array
    {
        return ['Approved', 'Approved with Comments', 'Rejected', 'Revise and Resubmit'];
    }

    public static function commentSeverities(): array
    {
        return ['Minor', 'Major', 'Critical'];
    }

    public static function commentStatuses(): array
    {
        return ['Open', 'Under Review', 'Responded', 'Resolved', 'Rejected', 'Closed'];
    }

    public static function commentCategories(): array
    {
        return ['Design', 'Coordination', 'Compliance', 'Specification', 'Documentation', 'Other'];
    }

    public static function rfiStatuses(): array
    {
        return ['Open', 'Under Review', 'Answered', 'Rejected', 'Closed', 'Cancelled'];
    }

    public static function rfiPriorities(): array
    {
        return ['Low', 'Normal', 'High', 'Urgent'];
    }

    public static function changeStatuses(): array
    {
        return ['Draft', 'Submitted', 'Under Review', 'Approved', 'Rejected', 'Implemented'];
    }

    public static function changeTypes(): array
    {
        return ['Design Change', 'Variation', 'Clarification', 'Scope Change', 'Value Engineering'];
    }

    public static function costCategories(): array
    {
        return ['Design', 'Construction', 'Materials', 'Labour', 'Equipment', 'Consultancy', 'Other'];
    }

    public static function currencies(): array
    {
        return ['INR', 'USD', 'EUR', 'GBP', 'AED'];
    }
}
