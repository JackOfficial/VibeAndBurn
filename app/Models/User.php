<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'google_id',
        'password',
        'avatar',
        'linkOwner',
        'status'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function funds() // Plural is better for "hasMany"
{
    // A User HAS MANY funds
    return $this->hasMany(fund::class, 'user_id');
}

public function orders() 
{ 
    return $this->hasMany(order::class, 'user_id'); 
}

public function bonuses() 
{ 
    return $this->hasMany(bonus::class, 'user_id'); 
}

public function wallet() { return $this->hasOne(wallet::class); }

public function getBalanceAttribute()
{
    return $this->wallet ? $this->wallet->money : 0.00000000;
}

public function referrer()
{
    return $this->belongsTo(User::class, 'linkOwner');
}

}
