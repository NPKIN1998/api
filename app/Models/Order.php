<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'price',
        'daily_income',
        'profits',
        'days_paid',
        'days_ordered',
        'date_ordered',
        'days_to_earn',
    ];
}
