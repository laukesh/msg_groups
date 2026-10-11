<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Tenant extends Model
{
    use SoftDeletes;

    protected $table = 'tenants';

    // Merge these fields into your existing $fillable.
    protected $fillable = [
        'user_id',
        'uuid',
        'tenant_code',
        'company_name',
        'brand_name',
        'business_category_id',
        'parent_group_id',
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
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function emergencyContacts()
    {
        return $this->hasMany(
            TenantEmergencyContact::class,
            'tenant_id'
        );
    }
    public function parentGroup(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_group_id');
    }

    public function childGroups(): HasMany
    {
        return $this->hasMany(self::class, 'parent_group_id');
    }

    public function contacts(): HasMany
    {
        return $this->hasMany(TenantContact::class, 'tenant_id');
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(TenantAddress::class, 'tenant_id');
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
    public function bankAccounts(): HasMany
    {
        return $this->hasMany(TenantBankAccount::class, 'tenant_id');
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
 

    public function documents(): HasMany
    {
        return $this->hasMany(TenantDocument::class, 'tenant_id');
    }

    public function leases(): HasMany
    {
        return $this->hasMany(LeaseAgreement::class, 'tenant_id');
    }
}