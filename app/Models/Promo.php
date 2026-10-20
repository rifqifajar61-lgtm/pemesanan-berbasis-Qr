<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    protected $fillable = [
        'name',
        'code',
        'discount_percent',
        'start_date',
        'end_date',
        'is_active',
    ];

    protected $casts = [
        'discount_percent' => 'integer',
        'start_date'       => 'date',
        'end_date'         => 'date',
        'is_active'        => 'boolean',
    ];

    // Promo yang sedang berlaku hari ini
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now());
    }
}