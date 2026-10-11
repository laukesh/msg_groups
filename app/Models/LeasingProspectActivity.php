<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeasingProspectActivity extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'leasing_prospect_activities';

    protected $fillable = [
        'prospect_id',
        'activity_type',
        'subject',
        'description',
        'activity_date',
        'next_follow_up',
        'outcome',
        'created_by',
    ];

    protected $casts = [
        'activity_date' => 'datetime',
        'next_follow_up' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function prospect()
    {
        return $this->belongsTo(
            LeasingProspect::class,
            'prospect_id'
        );
    }

    public function creator()
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }

    public function getActivityTypeLabelAttribute()
    {
        return ucfirst(
            str_replace('_', ' ', $this->activity_type)
        );
    }
}