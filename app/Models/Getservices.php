<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Getservices extends Model
{
    use HasFactory;
    protected $fillable = ['service', 'name', 'type', 'category', 'rate', 'min', 'max', 'refill', 'cancel'];
}
