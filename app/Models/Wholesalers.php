<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Wholesalers extends Model
{
    use HasFactory;
    
    protected $fillable = ['wholesaler', 'status'];
    
}
