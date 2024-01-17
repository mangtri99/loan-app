<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Repayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'amount',
        'loan_id',
        'status_id',
        'due_date',
        'paid_date',
    ];

    protected $casts = [
        'loan_id' => 'integer',
        'status_id' => 'integer',
        'due_date' => 'date',
        'paid_date' => 'date',
    ];
}
