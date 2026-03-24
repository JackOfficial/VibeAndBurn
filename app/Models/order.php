<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class order extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'service_id', 'link', 'orderId', 'quantity', 'comment', 'charge', 'start_count', 'remains', 'status'];

public function service()
{
    return $this->belongsTo(Service::class, 'service_id');
}

}
