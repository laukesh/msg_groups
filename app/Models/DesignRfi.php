<?php

namespace App\Models;

use App\Models\Concerns\AutoGeneratesDocumentCode;
use App\Models\Concerns\HasDesignWorkflow;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DesignRfi extends Model
{
    use AutoGeneratesDocumentCode;
    use HasDesignWorkflow;

    protected $table = 'design_rfis';

    protected $fillable = [
        'project_id',
        'design_discipline_id',
        'consultant_id',
        'rfi_number',
        'subject',
        'question',
        'reference_document',
        'reference_drawing',
        'raised_date',
        'required_response_date',
        'response',
        'response_date',
        'responded_by',
        'status',
        'prepared_by',
        'prepared_at',
        'submitted_by',
        'submitted_at',
        'rejected_by',
        'rejected_at',
        'rejection_reason',
        'approval_remarks',
        'priority',
        'remarks',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'raised_date' => 'date',
        'required_response_date' => 'date',
        'response_date' => 'date',
        'prepared_at' => 'datetime',
        'submitted_at' => 'datetime',
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

    public function consultant(): BelongsTo
    {
        return $this->belongsTo(ProjectConsultant::class, 'consultant_id');
    }

    public function responder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responded_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responded_by');
    }

    protected function codeField(): string
    {
        return 'rfi_number';
    }

    protected function codePrefix(): string
    {
        return 'DR';
    }
}
