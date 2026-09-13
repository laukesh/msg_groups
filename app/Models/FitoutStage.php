<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FitoutStage extends Model
{
    use SoftDeletes;

    protected $table = 'fitout_stages';

    protected $fillable = [
        'uuid',
        'fitout_request_id',
        'contractor_id',
        'stage_name',
        'stage_sequence',
        'planned_start_date',
        'planned_end_date',
        'actual_start_date',
        'actual_end_date',
        'completion_percentage',
        'engineer_id',
        'stage_status',
        'remarks',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'planned_start_date' => 'date',
        'planned_end_date' => 'date',
        'actual_start_date' => 'date',
        'actual_end_date' => 'date',
        'completion_percentage' => 'decimal:2',
    ];

    public function fitoutRequest()
    {
        return $this->belongsTo(
            FitoutRequest::class,
            'fitout_request_id'
        );
    }

    public function contractor()
    {
        return $this->belongsTo(
            FitoutContractor::class,
            'contractor_id'
        );
    }

    public function inspections()
    {
        return $this->hasMany(
            Inspection::class,
            'fitout_stage_id'
        );
    }
}