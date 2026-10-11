<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zone extends Model
{
    use HasFactory;

    protected $table = 'zones';

    protected $fillable = [
        'floor_id',
        'zone_code',
        'zone_name',
        'description',
        'status',
        'created_by',
        'updated_by',
    ];

    /*
    |--------------------------------------------------------------------------
    | Floor Relationship
    |--------------------------------------------------------------------------
    */
    public function floor()
    {
        return $this->belongsTo(Floor::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Created By
    |--------------------------------------------------------------------------
    */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /*
    |--------------------------------------------------------------------------
    | Updated By
    |--------------------------------------------------------------------------
    */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}