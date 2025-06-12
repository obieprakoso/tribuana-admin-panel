<?php

namespace App\Models;

use App\Enums\ResidentStatus;
use Illuminate\Database\Eloquent\Model;

class Resident extends Model
{
    protected $fillable = [
        'head_of_family',
        'house_number',
        'contact',
        'status',
        'is_deleted'
    ];

    protected $casts = [
        'is_deleted' => 'boolean',
        'status' => ResidentStatus::class,
    ];

    protected $attributes = [
        'is_deleted' => false,
        'status' => ResidentStatus::PERMANENT->value
    ];

    public function financials()
    {
        return $this->hasMany(Financial::class);
    }
}
