<?php

namespace App\Models;

use App\Models\Concerns\AutoGeneratesDocumentCode;
use App\Models\Concerns\HasDesignWorkflow;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DesignReview extends Model
{
    use AutoGeneratesDocumentCode;
    use HasDesignWorkflow;

    protected $table = 'design_reviews';

    protected $fillable = [
        'design_submittal_id',
        'review_number',
        'review_date',
        'reviewer_id',
        'review_status',
        'decision',
        'general_comments',
        'response_required',
        'response_due_date',
        'responded_date',
        'prepared_by',
        'prepared_at',
        'submitted_by',
        'submitted_at',
        'rejected_by',
        'rejected_at',
        'rejection_reason',
        'approval_remarks',
        'remarks',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'review_date' => 'date',
        'response_required' => 'boolean',
        'response_due_date' => 'date',
        'responded_date' => 'date',
        'prepared_at' => 'datetime',
        'submitted_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    public function submittal(): BelongsTo
    {
        return $this->belongsTo(DesignSubmittal::class, 'design_submittal_id');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(DesignComment::class, 'design_review_id');
    }

    protected function codeField(): string
    {
        return 'review_number';
    }

    protected function codePrefix(): string
    {
        return 'DRV';
    }
}
