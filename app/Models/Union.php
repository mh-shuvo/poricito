<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Union extends Model
{
    protected $fillable = ['thana_id', 'name', 'slug'];

    public function thana(): BelongsTo
    {
        return $this->belongsTo(Thana::class);
    }

    public function wards(): HasMany
    {
        return $this->hasMany(Ward::class);
    }
}
