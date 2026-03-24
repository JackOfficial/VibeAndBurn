<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class service extends Model
{
    use HasFactory;
    protected $fillable = ['category_id', 'source_id', 'serviceId', // Add these for API syncing
    'service', 'rate_per_1000', 'min_order', 'max_order', 
    'Average_completion_time', 'quality', 'start', 'speed', 
    'refill', 'price_per_1000', 'description', 'status', 'state'];

   public function category(): BelongsTo
    {
        // This assumes your services table has a 'category_id' column
        return $this->belongsTo(category::class, 'category_id');
    }

    public function source(): BelongsTo
    {
        return $this->belongsTo(Source::class, 'source_id');
    }

}
