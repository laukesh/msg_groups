<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Deposit extends Model
{
    use SoftDeletes;

    protected $table = 'deposits';
    protected $fillable = [
        'uuid',
        'lease_agreement_id',
        'deposit_type',
        'deposit_amount',
        'received_amount',
        'balance_amount',
        'due_date',
        'payment_status',
        'refundable_amount',
        'remarks',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'deposit_amount' => 'decimal:2',
        'received_amount' => 'decimal:2',
        'balance_amount' => 'decimal:2',
        'refundable_amount' => 'decimal:2',
        'due_date' => 'date',
    ];

    protected static function booted()
    {
        static::creating(function ($deposit) {

            if (empty($deposit->uuid)) {

                $deposit->uuid =
                    (string) Str::uuid();
            }

            if ($deposit->received_amount === null) {

                $deposit->received_amount = 0;
            }

            if ($deposit->refundable_amount === null) {

                $deposit->refundable_amount = 0;
            }

            if ($deposit->balance_amount === null) {

                $deposit->balance_amount =
                    $deposit->deposit_amount
                    - $deposit->received_amount;
            }

            if (empty($deposit->payment_status)) {

                $deposit->payment_status =
                    $deposit->received_amount <= 0
                        ? 'Pending'
                        : (
                            $deposit->received_amount
                            < $deposit->deposit_amount
                                ? 'Partial'
                                : 'Paid'
                        );
            }
        });
    }


    /*
    |--------------------------------------------------------------------------
    | Lease Agreement
    |--------------------------------------------------------------------------
    */

    public function leaseAgreement()
    {
        return $this->belongsTo(
            LeaseAgreement::class,
            'lease_agreement_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Deposit Receipts
    |--------------------------------------------------------------------------
    */

    public function receipts()
    {
        return $this->hasMany(
            DepositReceipt::class,
            'deposit_id'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Deposit Refunds
    |--------------------------------------------------------------------------
    */

    public function refunds()
    {
        return $this->hasMany(
            DepositRefund::class,
            'deposit_id'
        );
    }

}

