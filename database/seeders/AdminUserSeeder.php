<?php

namespace Database\Seeders;

use App\Models\admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;


class AdminUserSeeder extends Seeder
{
    public function run()
    {
        // Create an admin user
        admin::create([
            'name' => 'Admin User',
            'mobile' => '12345',
            'password' => Hash::make('admin123'), // Use a strong password here
            'is_admin' => true, // Set the is_admin flag
        ]);
    }
}

