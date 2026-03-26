<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ticket extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * * Added 'category_id', 'priority', and 'last_reply_at' to match 
     * the professional migration.
     */
    protected $fillable = [
        'user_id',
        'category_id',
        'subject',
        'order_id',
        'payment',
        'amount',
        'transaction_id',
        'status',
        'priority',
        'last_reply_at'
    ];

    /**
     * The attributes that should be cast.
     * This ensures 'last_reply_at' is treated as a Carbon date object.
     */
    protected $casts = [
        'last_reply_at' => 'datetime',
    ];

    /**
     * Get the User who opened the ticket.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the Category this ticket belongs to.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(TicketCategory::class, 'category_id');
    }

    /**
     * Get the conversation thread (Support messages).
     */
    public function supports(): HasMany
    {
        return $this->hasMany(Supports::class);
    }

    /**
     * Scope: Filter by Status (e.g., Ticket::status('pending')->get())
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}