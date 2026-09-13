<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LandDueDiligence extends Model
{
    use SoftDeletes;

    protected $table = 'land_due_diligences';


    protected $fillable = [

        'land_id',

        'type',

        'reference_no',

        'assessment_date',

        'conducted_by',

        'status',

        'summary',

        'findings',

        'recommendations',

        'remarks',

        'created_by',

        'updated_by',

    ];


    protected $casts = [

        'assessment_date' => 'date',

    ];


    /*
    |--------------------------------------------------------------------------
    | Land
    |--------------------------------------------------------------------------
    */

    public function land()
    {
        return $this->belongsTo(
            Land::class,
            'land_id'
        );
    }
}