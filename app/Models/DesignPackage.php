<?php

namespace App\Models;

use App\Models\Concerns\AutoGeneratesDocumentCode;
use App\Models\Concerns\HasDesignWorkflow;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DesignPackage extends Model
{
    use AutoGeneratesDocumentCode;
    use HasDesignWorkflow;

    protected $table = 'design_packages';

    protected $fillable = [
        'project_id',
        'parent_id',
        'design_discipline_id',
        'package_code',
        'package_name',
        'description',
        'planned_submission_date',
        'actual_submission_date',
        'responsible_consultant_id',
        'status',
        'version',
        'version_number',
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
        'remarks',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'planned_submission_date' => 'date',
        'actual_submission_date' => 'date',
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

    public function discipline(): BelongsTo
    {
        return $this->belongsTo(DesignDiscipline::class, 'design_discipline_id');
    }

    public function responsibleConsultant(): BelongsTo
    {
        return $this->belongsTo(ProjectConsultant::class, 'responsible_consultant_id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function drawings(): HasMany
    {
        return $this->hasMany(DesignDrawing::class, 'design_package_id');
    }

    public function submittals(): HasMany
    {
        return $this->hasMany(DesignSubmittal::class, 'design_package_id');
    }

    protected function codeField(): string
    {
        return 'package_code';
    }

    protected function codePrefix(): string
    {
        return 'DP';
    }
}
