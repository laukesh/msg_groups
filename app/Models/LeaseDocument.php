<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeaseDocument extends Model
{
    use SoftDeletes;

    protected $table = 'lease_documents';

    protected $fillable = [

        'lease_agreement_id',
        'document_type_id',

        'document_name',
        'document_number',

        'version_no',

        'file_name',
        'file_path',
        'file_size',
        'file_extension',

        'issue_date',
        'expiry_date',

        'verification_status',

        'verified_by',
        'verified_at',

        'remarks',

        'created_by',
        'updated_by',

    ];

    protected $casts = [

        'issue_date'  => 'date',
        'expiry_date' => 'date',
        'verified_at' => 'datetime',

    ];


    /*
    |--------------------------------------------------------------------------
    | Document Type
    |--------------------------------------------------------------------------
    */

    public function documentType()
    {
        return $this->belongsTo(
            DocumentType::class,
            'document_type_id'
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

    public function agreement()
	{
	    return $this->belongsTo(
	        LeaseAgreement::class,
	        'lease_agreement_id'
	    );
	}


	public function verifiedBy()
	{
	    return $this->belongsTo(
	        User::class,
	        'verified_by'
	    );
	}
}