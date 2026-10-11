<?php

namespace App\Models;

use App\Models;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class IndustryProfile extends Model
{
    use SoftDeletes;

    protected $table = 'industry_profiles';

    protected $fillable = [
        'name',
        'code',
        'description',
        'status',
        'sort_order',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'sort_order' => 'integer',
    ];

   

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        
    return $this->belongsTo(User::class, 'updated_by');
    }
      public function assets(): HasMany
    {
        return $this->hasMany(
            Asset::class,
            'industry_profile_id',
            'id'
        );
    }
}