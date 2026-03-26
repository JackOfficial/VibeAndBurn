<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Support extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * Note: We fixed 'ticket_Id' to 'ticket_id' to match standard naming.
     */
    protected $fillable = [
        'ticket_id',
        'user_id',
        'message',
        'is_admin'
    ];

    /**
     * Get the Ticket that this message belongs to.
     */
    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    /**
     * Get the User who sent this message.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope: Filter messages sent by Admins.
     * Useful for styling or analytics.
     */
    public function scopeAdmin($query)
    {
        return $query->where('is_admin', true);
    }

    /**
     * Scope: Filter messages sent by Users.
     */
    public function scopeUser($query)
    {
        return $query->where('is_admin', false);
    }
}