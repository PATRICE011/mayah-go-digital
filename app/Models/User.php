<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    protected $table = 'users_area'; // Your custom table name, if applicable
    protected $fillable = ['name', 'mobile', 'password', 'otp', 'otp_created_at', 'otp_attempts', 'role_id'];
    protected $hidden = ['password', 'remember_token'];

    public function cart()
    {
        return $this->hasOne(Cart::class);  // Correct relationship (one cart per user)
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);  // Correct relationship (many wishlists per user)
    }
}

