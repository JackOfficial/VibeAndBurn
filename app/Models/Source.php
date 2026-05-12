<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Source extends Model
{
    use HasFactory;
    protected $fillable=['api_source'];

    public function services(): HasMany
{
    return $this->hasMany(service::class, 'source_id');
}
}
