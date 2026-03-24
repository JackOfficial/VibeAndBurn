<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class socialmedia extends Model
{
    use HasFactory;
    protected $fillable = ['socialmedia'];

    public function categories(): HasMany
    {
        return $this->hasMany(category::class, 'socialmedia_id');
    }

}
