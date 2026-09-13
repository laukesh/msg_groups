<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inspection extends Model
{
    use SoftDeletes;

    protected $table = 'inspections';

    protected $fillable = [
        'uuid',
        'fitout_request_id',
        'fitout_stage_id',
        'inspection_type',
        'inspection_number',
        'scheduled_date',
        'scheduled_time',
        'inspection_date',
        'inspector_id',
        'result',
        'status',
        'observations',
        'recommendations',
        'reinspection_required',
        'parent_inspection_id',
        'report_file_path',
        'completed_at',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'scheduled_date' => 'date',
        'inspection_date' => 'date',
        'scheduled_time' => 'datetime:H:i',
        'completed_at' => 'datetime',
        'reinspection_required' => 'boolean',
    ];


    /*
    |--------------------------------------------------------------------------
    | Fit-Out Request
    |--------------------------------------------------------------------------
    */

    public function fitoutRequest()
    {
        return $this->belongsTo(
            FitoutRequest::class,
            'fitout_request_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Fit-Out Stage
    |--------------------------------------------------------------------------
    */

    public function fitoutStage()
    {
        return $this->belongsTo(
            FitoutStage::class,
            'fitout_stage_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Inspector
    |--------------------------------------------------------------------------
    */

    public function inspector()
    {
        return $this->belongsTo(
            User::class,
            'inspector_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Parent Inspection
    |--------------------------------------------------------------------------
    */

    public function parentInspection()
    {
        return $this->belongsTo(
            Inspection::class,
            'parent_inspection_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Re-Inspections
    |--------------------------------------------------------------------------
    */

    public function reinspections()
    {
        return $this->hasMany(
            Inspection::class,
            'parent_inspection_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Created By
    |--------------------------------------------------------------------------
    */

    public function createdBy()
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Updated By
    |--------------------------------------------------------------------------
    */

    public function updatedBy()
    {
        return $this->belongsTo(
            User::class,
            'updated_by'
        );
    }
}