<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class order extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'service_id', 'link', 'quantity', 'comment', 'charge', 'start_count', 'remains', 'status'];
    
//     protected $casts = [
//     'charge' => 'decimal:4', // Keeps 4 decimal places for accuracy
//     'quantity' => 'integer',
// ];

}
