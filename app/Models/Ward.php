<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ward extends Model
{
    protected $fillable = ['union_id', 'name', 'slug'];

    public function union(): BelongsTo
    {
        return $this->belongsTo(Union::class);
    }

    public function memorials(): HasMany
    {
        return $this->hasMany(Memorial::class);
    }
}
