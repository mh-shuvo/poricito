<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MemorialPhoto extends Model
{
    protected $fillable = ['memorial_id', 'photo_path', 'caption', 'display_order'];

    public function memorial(): BelongsTo
    {
        return $this->belongsTo(Memorial::class);
    }
}
