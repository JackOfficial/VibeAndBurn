<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPayment extends Model
{
    use HasFactory;
    protected $fillable = ['transaction_ref', 'user_id', 'amount', 'currency', 'payment_method', 'status'];
}
