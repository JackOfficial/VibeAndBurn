<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class category extends Model
{
    use HasFactory;
    protected $fillable = ['socialmedia_id', 'category'];

    public function socialmedia(): BelongsTo
    {
        return $this->belongsTo(socialmedia::class, 'socialmedia_id');
    }

    public function services(): HasMany
    {
        return $this->hasMany(service::class, 'category_id');
    }
}
