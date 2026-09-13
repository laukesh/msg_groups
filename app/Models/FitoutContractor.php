<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FitoutContractor extends Model
{
    use SoftDeletes;

    protected $table = 'fitout_contractors';

    protected $fillable = [

        'user_id',

        'uuid',

        'contractor_code',

        'contractor_name',

        'contact_person',

        'mobile',

        'email',

        'address',

        'trade_license_no',

        'labour_license_no',

        'gst_number',

        'insurance_policy_no',

        'insurance_expiry',

        'safety_induction_date',

        'worker_count',

        'status',

        'remarks',

        'created_by',

        'updated_by',

    ];

    protected $casts = [

        'insurance_expiry' =>
            'date',

        'safety_induction_date' =>
            'date',

        'worker_count' =>
            'integer',

    ];


    /*
    |--------------------------------------------------------------------------
    | Login User
    |--------------------------------------------------------------------------
    */

    public function user()
    {
        return $this->belongsTo(
            User::class,
            'user_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Fit-Out Requests
    |--------------------------------------------------------------------------
    */

    public function fitoutRequests()
    {
        return $this->hasMany(
            FitoutRequest::class,
            'contractor_id'
        );
    }
}