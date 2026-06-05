<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
    protected $fillable = [
        'name',
        'bank_name',
        'agency',
        'account_number',
        'account_type',
        'current_balance',
        'notes',
        'is_active'
    ];

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}