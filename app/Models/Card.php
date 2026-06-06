<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Card extends Model
{
    protected $fillable = [
        'name',
        'last_digits',
        'credit_limit',
        'used_limit',
        'closing_day',
        'due_day',
        'is_active',
        'notes',
    ];

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }
}