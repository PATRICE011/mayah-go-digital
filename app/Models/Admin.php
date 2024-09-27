<?php

// Admin.php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;  // Change to Authenticatable
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Admin extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'admin_area';

    protected $fillable = [
        'name',
        'mobile',
        'password',
        'is_admin',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}

