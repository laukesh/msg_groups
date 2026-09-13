<?php

namespace App\Models;

use App\Models\Concerns\HasDesignWorkflow;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DesignDrawing extends Model
{
    use HasDesignWorkflow;

    protected $table = 'design_drawings';

    protected $fillable = [
        'project_id',
        'parent_id',
        'design_package_id',
        'design_discipline_id',
        'drawing_number',
        'drawing_title',
        'drawing_type',
        'revision',
        'revision_date',
        'status',
        'version_number',
        'prepared_by',
        'prepared_at',
        'submitted_by',
        'submitted_at',
        'approved_by',
        'rejected_by',
        'rejected_at',
        'rejection_reason',
        'approval_remarks',
        'prepared_by_consultant_id',
        'file_name',
        'file_path',
        'planned_date',
        'submitted_date',
        'approved_date',
        'current_revision',
        'remarks',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'revision_date' => 'date',
        'planned_date' => 'date',
        'submitted_date' => 'date',
        'approved_date' => 'date',
        'current_revision' => 'boolean',
        'version_number' => 'integer',
        'prepared_at' => 'datetime',
        'submitted_at' => 'datetime',
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

    public function preparedByConsultant(): BelongsTo
    {
        return $this->belongsTo(ProjectConsultant::class, 'prepared_by_consultant_id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
