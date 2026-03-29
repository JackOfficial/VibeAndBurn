<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class wallet extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'money'];
    
    protected $casts = [
        'money' => 'decimal:8',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function setMoneyAttribute($value)
{
    $this->attributes['money'] = max(0, $value);
}
    
}
