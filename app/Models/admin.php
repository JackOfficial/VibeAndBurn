<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class admin extends Model
{
    use HasFactory, Notifiable;
    protected $fillable = ['name', 'email', 'password', 'avatar'];
}
