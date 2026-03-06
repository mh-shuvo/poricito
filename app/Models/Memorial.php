<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Memorial extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'ward_id',
        'name',
        'date_of_birth',
        'date_of_death',
        'bio',
        'status',
        'admin_notes',
        'updated_by_admin_id',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'ward_id' => 'integer',
        'updated_by_admin_id' => 'integer',
        'date_of_birth' => 'date',
        'date_of_death' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function ward(): BelongsTo
    {
        return $this->belongsTo(Ward::class);
    }

    public function photos(): HasMany
    {
        return $this->hasMany(MemorialPhoto::class);
    }

    public function updatedByAdmin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by_admin_id');
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }
}
