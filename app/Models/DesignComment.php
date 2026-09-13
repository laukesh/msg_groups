<?php

namespace App\Models;

use App\Models\Concerns\AutoGeneratesDocumentCode;
use App\Models\Concerns\HasDesignWorkflow;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DesignComment extends Model
{
    use AutoGeneratesDocumentCode;
    use HasDesignWorkflow;

    protected $table = 'design_comments';

    protected $fillable = [
        'design_review_id',
        'comment_number',
        'category',
        'location_reference',
        'comment_text',
        'severity',
        'response_required',
        'consultant_response',
        'response_date',
        'status',
        'prepared_by',
        'prepared_at',
        'submitted_by',
        'submitted_at',
        'rejected_by',
        'rejected_at',
        'rejection_reason',
        'approval_remarks',
        'resolved_date',
        'verified_by',
        'verified_at',
        'remarks',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'response_required' => 'boolean',
        'response_date' => 'date',
        'resolved_date' => 'date',
        'verified_at' => 'datetime',
        'prepared_at' => 'datetime',
        'submitted_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    public function review(): BelongsTo
    {
        return $this->belongsTo(DesignReview::class, 'design_review_id');
    }

    public function verifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    protected function codeField(): string
    {
        return 'comment_number';
    }

    protected function codePrefix(): string
    {
        return 'DCM';
    }
}
