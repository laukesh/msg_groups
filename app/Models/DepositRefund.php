<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class DepositRefund extends Model
{
    use SoftDeletes;

    protected $table = 'deposit_refunds';
    protected $fillable = [
        'uuid',
        'deposit_id',
        'refund_no',
        'refund_date',
        'original_deposit',
        'outstanding_rent',
        'cam_deduction',
        'utility_deduction',
        'damage_deduction',
        'penalty_deduction',
        'other_deduction',
        'total_deduction',
        'refund_amount',
        'payment_mode',
        'bank_name',
        'transaction_reference',
        'refund_status',
        'approved_by',
        'approved_at',
        'remarks',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'refund_date' => 'date',
        'original_deposit' => 'decimal:2',
        'outstanding_rent' => 'decimal:2',
        'cam_deduction' => 'decimal:2',
        'utility_deduction' => 'decimal:2',
        'damage_deduction' => 'decimal:2',
        'penalty_deduction' => 'decimal:2',
        'other_deduction' => 'decimal:2',
        'total_deduction' => 'decimal:2',
        'refund_amount' => 'decimal:2',
        'approved_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | Deposit
    |--------------------------------------------------------------------------
    */

    public function deposit()
    {
        return $this->belongsTo(
            Deposit::class,
            'deposit_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | UUID
    |--------------------------------------------------------------------------
    */

    protected static function booted()
    {
        static::creating(function ($refund) {

            if (empty($refund->uuid)) {
                $refund->uuid = (string) Str::uuid();
            }
        });
    }
}
