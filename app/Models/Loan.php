<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    const LOAN_TERMS = [3,6,9,12,24];

    protected $fillable = [
        'amount',
        'term',
        'type_id',
        'status_id',
        'user_id',
    ];

    protected $casts = [
        'amount' => 'float',
        'term' => 'integer',
        'type_id' => 'integer',
        'status_id' => 'integer',
        'user_id' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function type()
    {
        return $this->belongsTo(Type::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function repayments()
    {
        return $this->hasMany(Repayment::class);
    }
}
