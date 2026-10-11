<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
class CommissioningScope extends Model
{
    protected $table = 'commissioning_scopes';

    protected $fillable = [
        'project_id','construction_work_order_id','scope_code','scope_name','scope_type','location',
        'planned_start_date','planned_completion_date','actual_completion_date','status','remarks',
        'created_by','updated_by',
    ];

    protected $casts = [
        'planned_start_date' => 'date',
        'planned_completion_date' => 'date',
        'actual_completion_date' => 'date',
    ];

    public function project() { return $this->belongsTo(Project::class); }
    public function workOrder() { return $this->belongsTo(ConstructionWorkOrder::class, 'construction_work_order_id'); }
    public function testPlans() { return $this->hasMany(CommissioningTestPlan::class); }
    public function tests() { return $this->hasMany(CommissioningTest::class); }
    public function certificates() { return $this->hasMany(CommissioningCertificate::class); }

    public function getProgressPercentageAttribute(): float
    {
        $latest = ConstructionProgressUpdate::where('project_id', $this->project_id)
            ->where('construction_work_order_id', $this->construction_work_order_id)
            ->orderByDesc('progress_date')->orderByDesc('id')->first();
        return $latest ? (float) $latest->progress_percentage : 0.0;
    }
    public function documents(): HasMany
    {
        return $this->hasMany(
            CommissioningDocument::class,
            'commissioning_scope_id'
        );
    }
}
