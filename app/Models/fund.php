<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class fund extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'method', 'amount', 'Payedwith'];
}
