<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TransactionType extends Model
{
    protected $fillable = [
        'name',
        'code',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function financials(): HasMany
    {
        return $this->hasMany(Financial::class);
    }

    // Helper method untuk mendapatkan total kas
    public function getTotalKasAttribute()
    {
        return $this->financials()
            ->whereHas('transactionType', fn ($q) => $q->where('category', 'kas'))
            ->sum('amount');
    }

    // Helper method untuk mendapatkan total kebersihan
    public function getTotalKebersihanAttribute()
    {
        return $this->financials()
            ->whereHas('transactionType', fn ($q) => $q->where('category', 'kebersihan'))
            ->sum('amount');
    }
} 