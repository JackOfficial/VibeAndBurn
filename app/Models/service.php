<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class service extends Model
{
    use HasFactory;
    protected $fillable = ['category_id', 'service', 'rate_per_1000', 'min_order', 'max_order', 'Average_completion_time',
'quality', 'start', 'speed', 'refill', 'price_per_1000', 'description', 'status', 'state'];

   public function category(): BelongsTo
    {
        // This assumes your services table has a 'category_id' column
        return $this->belongsTo(category::class, 'category_id');
    }

}
