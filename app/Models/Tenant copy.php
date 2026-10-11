<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tenant extends Model
{
    use SoftDeletes;

    protected $table = 'tenants';

    protected $fillable = [
        'user_id',
        'uuid',
        'tenant_code',
        'company_name',
        'brand_name',
        'business_category_id',
        'gst_number',
        'pan_number',
        'company_registration_no',
        'website',
        'email',
        'phone',
        'status',
        'created_by',
        'updated_by',
    ];

    public function user()
    {
        return $this->belongsTo(
            User::class,
            'user_id'
        );
    }

    public function contacts()
    {
        return $this->hasMany(
            TenantContact::class,
            'tenant_id'
        );
    }

    public function addresses()
    {
        return $this->hasMany(
            TenantAddress::class,
            'tenant_id'
        );
    }

    public function bankAccounts()
    {
        return $this->hasMany(
            TenantBankAccount::class,
            'tenant_id'
        );
    }

    public function documents()
    {
        return $this->hasMany(
            TenantDocument::class,
            'tenant_id'
        );
    }

    public function emergencyContacts()
    {
        return $this->hasMany(
            TenantEmergencyContact::class,
            'tenant_id'
        );
    }

    public function notes()
    {
        return $this->hasMany(
            TenantNote::class,
            'tenant_id'
        );
    }

    public function history()
    {
        return $this->hasMany(
            TenantHistory::class,
            'tenant_id'
        );
    }

    public function leaseAgreements()
    {
        return $this->hasMany(
            LeaseAgreement::class,
            'tenant_id'
        );
    }

    public function invoices()
    {
        return $this->hasMany(
            Invoice::class,
            'tenant_id'
        );
    }

    public function rentPayments()
    {
        return $this->hasMany(
            RentPayment::class,
            'tenant_id'
        );
    }
    
}