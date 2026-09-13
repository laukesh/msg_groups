<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ContractManagementContract extends Model
{
    protected $table = 'contract_management_contracts';


    protected $fillable = [

        'project_id',

        'contract_code',

        'contract_source',

        'procurement_contract_id',
        'project_consultant_id',

        'party_type',
        'party_name',

        'contract_number',
        'contract_title',
        'contract_type',

        'contract_value',
        'currency',

        'start_date',
        'completion_date',
        'signing_date',

        'retention_required',
        'retention_percentage',

        'advance_payment_required',
        'advance_payment_amount',

        'performance_security_required',
        'performance_security_amount',

        'status',

        'responsible_user_id',

        'scope_of_work',
        'terms_and_conditions',
        'special_conditions',

        'remarks',

        'created_by',
        'updated_by',
    ];


    protected $casts = [

        'start_date' => 'date',

        'completion_date' => 'date',

        'signing_date' => 'date',

        'contract_value' => 'decimal:2',

        'retention_required' => 'boolean',

        'retention_percentage' => 'decimal:2',

        'advance_payment_required' => 'boolean',

        'advance_payment_amount' => 'decimal:2',

        'performance_security_required' => 'boolean',

        'performance_security_amount' => 'decimal:2',
    ];


    /*
    |--------------------------------------------------------------------------
    | Project
    |--------------------------------------------------------------------------
    */

    public function project(): BelongsTo
    {
        return $this->belongsTo(
            Project::class,
            'project_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Procurement Contract
    |--------------------------------------------------------------------------
    */

    public function procurementContract(): BelongsTo
    {
        return $this->belongsTo(
            ProcurementContract::class,
            'procurement_contract_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Consultant
    |--------------------------------------------------------------------------
    */

    public function consultant(): BelongsTo
    {
        return $this->belongsTo(
            ProjectConsultant::class,
            'project_consultant_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Responsible User
    |--------------------------------------------------------------------------
    */

    public function responsibleUser(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'responsible_user_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Creator
    |--------------------------------------------------------------------------
    */

    public function creator(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Updater
    |--------------------------------------------------------------------------
    */

    public function updater(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'updated_by'
        );
    }

    public function claims(): HasMany
	{
	    return $this->hasMany(
	        ContractClaim::class,
	        'contract_management_contract_id'
	    );
	}

	public function extensionsOfTime(): HasMany
	{
	    return $this->hasMany(
	        ContractExtensionOfTime::class,
	        'contract_management_contract_id'
	    );
	}

    public function insurances(): HasMany
    {
        return $this->hasMany(
            ContractInsurance::class,
            'contract_management_contract_id'
        );
    }

    public function performanceSecurities()
    {
        return $this->hasMany(
            ContractPerformanceSecurity::class,
            'contract_management_contract_id'
        );
    }

    public function retentions(): HasMany
    {
        return $this->hasMany(
            ContractManagementRetention::class,
            'contract_management_contract_id'
        );
    }

    public function advancePayments()
    {
        return $this->hasMany(
            ContractManagementAdvancePayment::class,
            'contract_management_contract_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Documents
    |--------------------------------------------------------------------------
    */

    public function documents(): HasMany
    {
        return $this->hasMany(
            ContractManagementDocument::class,
            'contract_management_contract_id'
        );
    }

    public function correspondence()
    {
        return $this->hasMany(
            ContractManagementCorrespondence::class,
            'contract_management_contract_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Procurement Contract Milestones
    |--------------------------------------------------------------------------
    */

    public function milestones()
    {
        return $this->hasManyThrough(
            ProcurementContractMilestone::class,
            ProcurementContract::class,
            'id',
            'procurement_contract_id',
            'procurement_contract_id',
            'id'
        );
    }
}