<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lead extends Model
{
    use SoftDeletes;

    protected $table = 'leads';

    protected $fillable = [
        'company_name',
        'contact_name',
        'designation',
        'email',
        'phone',
        'alternate_phone',
        'brand_name',
        'category',
        'area_need',
        'area_unit',
        'budget_min',
        'budget_max',
        'budget_period',
        'preferred_location',
        'preferred_unit_id',
        'source',
        'budget',
        'status',
        'owner_id',
        'next_action',
        'next_action_date',
        'notes',
        'requirements',
    ];

    protected $casts = [
        'area_need' => 'decimal:2',
        'budget_min' => 'decimal:2',
        'budget_max' => 'decimal:2',
        'next_action_date' => 'date',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function preferredUnit()
    {
        return $this->belongsTo(Unit::class, 'preferred_unit_id');
    }

    public function activities()
    {
        return $this->hasMany(
            LeadActivity::class,
            'lead_id'
        );
    }
}