<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ticket extends Model
{
    use HasFactory;

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

    protected $casts = [
        'last_reply_at' => 'datetime',
        'status' => 'integer',
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
     * IMPORTANT: I renamed this from 'supports' to 'messages' 
     * to match the logic in your Blade and Component.
     */
    public function messages(): HasMany
    {
        // Ensure your model name is 'Support' or 'Message' correctly here
        return $this->hasMany(Support::class, 'ticket_id');
    }

    /**
     * Helper to get status badge classes quickly
     */
    public function getStatusBadgeAttribute()
    {
        return [
            0 => 'badge-warning', // Pending
            1 => 'badge-primary', // Replied
            2 => 'badge-success', // Closed
        ][$this->status] ?? 'badge-secondary';
    }

    /**
     * Scope: Filter by Status
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}