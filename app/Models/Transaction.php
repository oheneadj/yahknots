<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    //
    protected $fillable = [
        'client_reference',
        'transaction_id',
        'customer_name',
        'customer_number',
        'network',
        'amount',
        'message',
        'status',
        'response_code',
        'response_body',
    ];

    protected $casts = [
        'response_body' => 'array',
    ];
}
