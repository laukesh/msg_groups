<?php

namespace App\Models;

use App\Models\Concerns\AutoGeneratesDocumentCode;
use App\Models\Concerns\HasDesignWorkflow;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DesignSubmittal extends Model
{
    use AutoGeneratesDocumentCode;
    use HasDesignWorkflow;

    protected $table = 'design_submittals';

    protected $fillable = [
        'project_id',
        'parent_id',
        'design_package_id',
        'design_discipline_id',
        'consultant_id',
        'submittal_number',
        'subject',
        'description',
        'submission_date',
        'revision',
        'version_number',
        'status',
        'prepared_by',
        'prepared_at',
        'submitted_by',
        'submitted_at',
        'approved_by',
        'approved_at',
        'rejected_by',
        'rejected_at',
        'rejection_reason',
        'approval_remarks',
        'due_date',
        'reviewed_date',
        'final_decision',
        'decision_remarks',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'submission_date' => 'date',
        'due_date' => 'date',
        'reviewed_date' => 'date',
        'version_number' => 'integer',
        'prepared_at' => 'datetime',
        'submitted_at' => 'datetime',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function designPackage(): BelongsTo
    {
        return $this->belongsTo(DesignPackage::class, 'design_package_id');
    }

    public function discipline(): BelongsTo
    {
        return $this->belongsTo(DesignDiscipline::class, 'design_discipline_id');
    }

    public function consultant(): BelongsTo
    {
        return $this->belongsTo(ProjectConsultant::class, 'consultant_id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(DesignReview::class, 'design_submittal_id');
    }

    protected function codeField(): string
    {
        return 'submittal_number';
    }

    protected function codePrefix(): string
    {
        return 'DS';
    }
}
