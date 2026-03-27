<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class bonus extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'bonus', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    
}
