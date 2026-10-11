<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Unit extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'units';

    protected $fillable = [
        'mall_id',
        'building_id',
        'floor_id',
        'zone_id',
        'unit_type_id',
        'unit_status_id',
        'unit_no',
        'shop_name',
        'unit_name',
        'carpet_area',
        'builtup_area',
        'leasable_area',
        'frontage',
        'depth',
        'floor_level',
        'monthly_rent',
        'base_rent',
        'cam_rate',
        'utility_rate',
        'security_deposit',
        'current_status',
        'status',
        'remarks',
        'created_by',
        'updated_by',
    ];

    /**
     * Mall
     */
    public function mall()
    {
        return $this->belongsTo(
            Mall::class,
            'mall_id'
        );
    }

    /**
     * Building
     */
    public function building()
    {
        return $this->belongsTo(
            Building::class,
            'building_id'
        );
    }

    /**
     * Floor
     */
    public function floor()
    {
        return $this->belongsTo(
            Floor::class,
            'floor_id'
        );
    }

    /**
     * Zone
     */
    public function zone()
    {
        return $this->belongsTo(
            Zone::class,
            'zone_id'
        );
    }

    /**
     * Unit Type
     */
    public function unitType()
    {
        return $this->belongsTo(
            UnitType::class,
            'unit_type_id'
        );
    }

    /**
     * Unit Status
     */
    public function unitStatus()
    {
        return $this->belongsTo(
            UnitStatus::class,
            'unit_status_id'
        );
    }

    /**
     * Proposal Units
     */
    public function proposalUnits()
    {
        return $this->hasMany(
            ProposalUnit::class,
            'unit_id'
        );
    }
}