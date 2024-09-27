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
    protected $fillable = ['name', 'mobile',  'password', 'otp', 'otp_created_at', 'otp_attempts','is_admin'];
    protected $hidden = ['password', 'remember_token'];

    // protected $primaryKey = 'user_id';

    // public function isAdmin()
    // {
    //     return $this->is_admin == 1;
    // }
        public function cart()
    {
        return $this->hasOne(Cart::class);
    }

}
