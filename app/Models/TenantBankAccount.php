<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TenantBankAccount extends Model
{
    use SoftDeletes;

    protected $table = 'tenant_bank_accounts';

    protected $fillable = [
        'tenant_id',
        'account_holder',
        'bank_name',
        'branch_name',
        'account_number',
        'ifsc_code',
        'swift_code',
        'account_type',
        'is_default',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    public function tenant()
    {
        return $this->belongsTo(
            Tenant::class,
            'tenant_id'
        );
    }
}