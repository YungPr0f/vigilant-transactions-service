<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    //

    protected $fillable = [
        'id', 'user_id', 'amount',
        'currency', 'ip', 'device_id',
        'timestamp', 'merchant_id'
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'reasons' => 'array',
            'timestamp' => 'datetime',
            'user_id' => 'integer',
            'device_id' => 'integer',
            'merchant_id' => 'integer',
        ];
    }
}
