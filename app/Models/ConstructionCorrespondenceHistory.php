<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConstructionCorrespondenceHistory extends Model
{
    protected $table = 'construction_correspondence_history';

    public $timestamps = false;

    protected $fillable = [
        'construction_correspondence_id',

        'action',

        'old_status',
        'new_status',

        'remarks',

        'performed_by',
        'performed_at',
    ];

    protected $casts = [

        'performed_at' => 'datetime',
    ];


    /*
    |--------------------------------------------------------------------------
    | Correspondence
    |--------------------------------------------------------------------------
    */

    public function correspondence(): BelongsTo
    {
        return $this->belongsTo(
            ConstructionCorrespondence::class,
            'construction_correspondence_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Performed By
    |--------------------------------------------------------------------------
    */

    public function performedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'performed_by'
        );
    }
}