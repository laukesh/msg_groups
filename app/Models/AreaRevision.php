<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AreaRevision extends Model
{
    protected $table = 'area_revisions';

    protected $fillable = [
        'property_area_id',
        'revision_number',

        'previous_gross_area',
        'new_gross_area',

        'previous_net_area',
        'new_net_area',

        'previous_rentable_area',
        'new_rentable_area',

        'reason',

        'revision_date',
        'effective_from',

        'status',

        'submitted_by',
        'approved_by',
        'approved_at',

        'remarks',
    ];

    protected $casts = [
        'previous_gross_area'   => 'decimal:4',
        'new_gross_area'        => 'decimal:4',

        'previous_net_area'     => 'decimal:4',
        'new_net_area'          => 'decimal:4',

        'previous_rentable_area' => 'decimal:4',
        'new_rentable_area'      => 'decimal:4',

        'revision_date' => 'date',
        'effective_from' => 'date',

        'approved_at' => 'datetime',
        'created_at'  => 'datetime',
        'updated_at'  => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function propertyArea(): BelongsTo
    {
        return $this->belongsTo(
            PropertyArea::class,
            'property_area_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopeDraft(Builder $query): Builder
    {
        return $query->where('status', 'DRAFT');
    }

    public function scopeSubmitted(Builder $query): Builder
    {
        return $query->where('status', 'SUBMITTED');
    }

    public function scopeApproved(Builder $query): Builder
    {
        return $query->where('status', 'APPROVED');
    }

    public function scopeRejected(Builder $query): Builder
    {
        return $query->where('status', 'REJECTED');
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    public function isDraft(): bool
    {
        return $this->status === 'DRAFT';
    }

    public function isSubmitted(): bool
    {
        return $this->status === 'SUBMITTED';
    }

    public function isApproved(): bool
    {
        return $this->status === 'APPROVED';
    }

    public function isRejected(): bool
    {
        return $this->status === 'REJECTED';
    }

    public function grossAreaChanged(): bool
    {
        return (float) $this->previous_gross_area
            !== (float) $this->new_gross_area;
    }

    public function netAreaChanged(): bool
    {
        return (float) $this->previous_net_area
            !== (float) $this->new_net_area;
    }

    public function rentableAreaChanged(): bool
    {
        return (float) $this->previous_rentable_area
            !== (float) $this->new_rentable_area;
    }
}
