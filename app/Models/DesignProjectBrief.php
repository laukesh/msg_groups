<?php

namespace App\Models;

use App\Models\Concerns\AutoGeneratesDocumentCode;
use App\Models\Concerns\HasDesignWorkflow;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DesignProjectBrief extends Model
{
    use AutoGeneratesDocumentCode;
    use HasDesignWorkflow;

    protected $table = 'design_project_briefs';

    protected $fillable = [
        'project_id',
        'parent_brief_id',
        'brief_code',
        'title',
        'version',
        'version_number',
        'project_requirements',
        'design_objectives',
        'functional_requirements',
        'technical_requirements',
        'design_standards',
        'authority_requirements',
        'status',
        'prepared_by',
        'approved_by',
        'prepared_at',
        'submitted_at',
        'submitted_by',
        'approved_at',
        'rejected_at',
        'rejected_by',
        'rejection_reason',
        'approval_remarks',
        'remarks',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
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

    public function parentBrief(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_brief_id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function isEditable(): bool
    {
        return $this->isWorkflowEditable();
    }

    protected function codeField(): string
    {
        return 'brief_code';
    }

    protected function codePrefix(): string
    {
        return 'DB';
    }
}
