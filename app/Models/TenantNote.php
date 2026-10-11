<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TenantNote extends Model
{
    use SoftDeletes;

    protected $table = 'tenant_notes';

    const UPDATED_AT = null;

    protected $fillable = [
        'tenant_id',
        'note_title',
        'note',
        'visibility',
        'created_by',
    ];

    public function tenant()
    {
        return $this->belongsTo(
            Tenant::class,
            'tenant_id'
        );
    }
}