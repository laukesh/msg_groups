<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DesignDiscipline extends Model
{
    protected $table = 'design_disciplines';

    protected $fillable = [
        'code',
        'name',
        'description',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'sort_order' => 'integer',
        'is_active' => 'boolean',
    ];

    public function packages(): HasMany
    {
        return $this->hasMany(DesignPackage::class, 'design_discipline_id');
    }

    public function drawings(): HasMany
    {
        return $this->hasMany(DesignDrawing::class, 'design_discipline_id');
    }
}
