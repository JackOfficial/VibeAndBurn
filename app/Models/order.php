<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class order extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'source_id', 'service_id', 'link', 'orderId', 'quantity', 'comment', 'charge', 'start_count', 'remains', 'status'];

public function service()
{
    return $this->belongsTo(service::class, 'service_id');
}

public function user() {
    return $this->belongsTo(User::class);
}

protected static function booted()
{
    static::creating(function ($order) {
        // If source_id was forgotten in the Controller, fetch it from the Service
        if (empty($order->source_id) && $order->service_id) {
            $service = DB::table('services')->find($order->service_id);
            if ($service) {
                $order->source_id = $service->source_id;
            }
        }
    });
}

}
