<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UnitDocument extends Model
{
    use HasFactory;

    protected $table = 'unit_documents';

  protected $fillable = [
    'unit_id',
    'document_type',
    'document_name',
    'document_path',
    'document_number',
    'document_date',
    'expiry_date',
    'remarks',
    'created_by',
    'updated_by',
];

protected $casts = [
    'document_date' => 'date',
    'expiry_date'   => 'date',
];


    /**
     * Unit associated with the document.
     */
    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    /**
     * User who created the document.
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * User who last updated the document.
     */
    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
