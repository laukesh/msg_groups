<?php

namespace App\Models;

use App\Models\Concerns\AutoGeneratesDocumentCode;
use App\Models\Concerns\HasDesignWorkflow;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DesignChange extends Model
{
    use AutoGeneratesDocumentCode;
    use HasDesignWorkflow;

    protected $table = 'design_changes';

    protected $fillable = [
        'project_id',
        'parent_id',
        'design_package_id',
        'design_discipline_id',
        'change_code',
        'change_title',
        'change_type',
        'reason',
        'description',
        'requested_date',
        'required_date',
        'requested_by',
        'status',
        'version_number',
        'prepared_by',
        'prepared_at',
        'submitted_by',
        'submitted_at',
        'rejected_by',
        'rejected_at',
        'rejection_reason',
        'cost_impact',
        'currency',
        'time_impact_days',
        'approved_by',
        'approval_date',
        'approval_remarks',
        'implemented_date',
        'remarks',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'requested_date' => 'date',
        'required_date' => 'date',
        'cost_impact' => 'decimal:2',
        'time_impact_days' => 'integer',
        'approval_date' => 'date',
        'implemented_date' => 'date',
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

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function costImpacts(): HasMany
    {
        return $this->hasMany(DesignChangeCostImpact::class, 'design_change_id');
    }

    protected function codeField(): string
    {
        return 'change_code';
    }

    protected function codePrefix(): string
    {
        return 'DC';
    }
}
