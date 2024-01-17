<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount',
        'term',
        'balance',
        'type_id',
        'status_id',
        'user_id',
    ];

    protected $casts = [
        'amount' => 'float',
        'term' => 'integer',
        'balance' => 'float',
        'type_id' => 'integer',
        'status_id' => 'integer',
        'user_id' => 'integer',
    ];
}
