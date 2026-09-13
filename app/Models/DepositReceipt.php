<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class DepositReceipt extends Model
{
    use SoftDeletes;

    protected $table = 'deposit_receipts';
    protected $fillable = [
        'uuid',
        'deposit_id',
        'receipt_no',
        'receipt_date',
        'payment_amount',
        'payment_mode',
        'bank_name',
        'transaction_reference',
        'payment_status',
        'received_by',
        'remarks',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'receipt_date' => 'date',
        'payment_amount' => 'decimal:2',
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
    | Automatically generate UUID
    |--------------------------------------------------------------------------
    */

    protected static function booted()
    {
        static::creating(function ($receipt) {

            if (empty($receipt->uuid)) {

                $receipt->uuid =
                    (string) Str::uuid();
            }
        });
    }
}

