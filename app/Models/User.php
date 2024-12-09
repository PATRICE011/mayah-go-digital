<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

// In app/Models/User.php
class User extends Authenticatable
{
    protected $table = 'users_area';
    protected $fillable = ['name', 'mobile',  'password', 'otp', 'otp_created_at', 'otp_attempts', 'role_id'];
    protected $hidden = ['password', 'remember_token'];


    public function cart()
    {
        return $this->hasOne(Cart::class);
    }
    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }
}
