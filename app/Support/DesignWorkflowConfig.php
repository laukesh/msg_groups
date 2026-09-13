<?php

namespace App\Support;

use App\Models\DesignChange;
use App\Models\DesignComment;
use App\Models\DesignDrawing;
use App\Models\DesignPackage;
use App\Models\DesignProjectBrief;
use App\Models\DesignReview;
use App\Models\DesignRfi;
use App\Models\DesignSubmittal;

class DesignWorkflowConfig
{
    public static function for(string $modelClass): array
    {
        return match ($modelClass) {
            DesignProjectBrief::class => self::approvalWorkflow([
                'entity_label' => 'Project Brief',
                'status_column' => 'status',
                'parent_column' => 'parent_brief_id',
                'code_field' => 'brief_code',
                'version_column' => 'version',
                'initial_status' => 'Draft',
            ]),
            DesignPackage::class => self::approvalWorkflow([
                'entity_label' => 'Design Package',
                'status_column' => 'status',
                'parent_column' => 'parent_id',
                'code_field' => 'package_code',
                'version_column' => 'version',
                'initial_status' => 'Draft',
            ]),
            DesignDrawing::class => self::approvalWorkflow([
                'entity_label' => 'Drawing',
                'status_column' => 'status',
                'parent_column' => 'parent_id',
                'code_field' => 'drawing_number',
                'version_column' => 'revision',
                'initial_status' => 'Draft',
                'revision_prefix' => 'R',
                'approved_at_column' => 'approved_date',
            ]),
            DesignSubmittal::class => self::approvalWorkflow([
                'entity_label' => 'Submittal',
                'status_column' => 'status',
                'parent_column' => 'parent_id',
                'code_field' => 'submittal_number',
                'version_column' => 'revision',
                'initial_status' => 'Draft',
                'submit_from' => ['Draft', 'Rejected', 'Resubmitted'],
                'approve_to' => 'Approved',
            ]),
            DesignChange::class => self::approvalWorkflow([
                'entity_label' => 'Design Change',
                'status_column' => 'status',
                'parent_column' => 'parent_id',
                'code_field' => 'change_code',
                'version_column' => null,
                'initial_status' => 'Draft',
                'approved_at_column' => 'approval_date',
            ]),
            DesignRfi::class => [
                'entity_label' => 'RFI',
                'status_column' => 'status',
                'editable_statuses' => ['Open', 'Rejected'],
                'submit_from' => ['Open', 'Rejected'],
                'submit_to' => 'Under Review',
                'approve_from' => ['Under Review'],
                'approve_to' => 'Answered',
                'reject_from' => ['Under Review'],
                'reject_to' => 'Rejected',
                'supports_revision' => false,
                'initial_status' => 'Open',
                'approved_at_column' => 'response_date',
                'approved_by_column' => 'responded_by',
                'submit_label' => 'Submit for Response',
                'approve_label' => 'Mark Answered',
                'reject_label' => 'Reject',
            ],
            DesignReview::class => [
                'entity_label' => 'Review',
                'status_column' => 'review_status',
                'editable_statuses' => ['Under Review', 'Rejected'],
                'submit_from' => ['Draft', 'Rejected'],
                'submit_to' => 'Under Review',
                'approve_from' => ['Under Review'],
                'approve_to' => 'Responded',
                'reject_from' => ['Under Review'],
                'reject_to' => 'Rejected',
                'supports_revision' => false,
                'initial_status' => 'Under Review',
                'approved_at_column' => 'responded_date',
                'approved_by_column' => 'reviewer_id',
                'submit_label' => 'Submit for Review',
                'approve_label' => 'Close Review',
                'reject_label' => 'Reject',
            ],
            DesignComment::class => [
                'entity_label' => 'Comment',
                'status_column' => 'status',
                'editable_statuses' => ['Open', 'Rejected'],
                'submit_from' => ['Open', 'Rejected'],
                'submit_to' => 'Under Review',
                'approve_from' => ['Under Review', 'Responded'],
                'approve_to' => 'Resolved',
                'reject_from' => ['Under Review'],
                'reject_to' => 'Rejected',
                'supports_revision' => false,
                'initial_status' => 'Open',
                'approved_at_column' => 'resolved_date',
                'approved_by_column' => 'verified_by',
                'submit_label' => 'Submit for Review',
                'approve_label' => 'Resolve',
                'reject_label' => 'Reject',
            ],
            default => self::approvalWorkflow([
                'entity_label' => 'Record',
                'status_column' => 'status',
                'parent_column' => 'parent_id',
                'code_field' => 'id',
                'version_column' => 'version',
                'initial_status' => 'Draft',
            ]),
        };
    }

    protected static function approvalWorkflow(array $overrides): array
    {
        return array_merge([
            'status_column' => 'status',
            'editable_statuses' => ['Draft', 'Rejected'],
            'submit_from' => ['Draft', 'Rejected'],
            'submit_to' => 'Under Review',
            'approve_from' => ['Under Review'],
            'approve_to' => 'Approved',
            'reject_from' => ['Under Review'],
            'reject_to' => 'Rejected',
            'revision_from' => ['Approved'],
            'supports_revision' => true,
            'initial_status' => 'Draft',
            'parent_column' => 'parent_id',
            'code_field' => null,
            'version_number_column' => 'version_number',
            'version_column' => 'version',
            'approved_at_column' => 'approved_at',
            'approved_by_column' => 'approved_by',
            'submit_label' => 'Submit for Review',
            'approve_label' => 'Approve',
            'reject_label' => 'Reject',
            'revision_prefix' => null,
        ], $overrides);
    }
}
