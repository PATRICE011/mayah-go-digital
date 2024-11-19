<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('roles')->insert([
            ['id' => 1,'name' => 'admin'],
            ['id' => 2,'name' => 'staff'],
            ['id' => 3,'name' => 'resident'],
        ]);

        DB::table('users_area')->insert([
            [
                'name' => 'John Doe',
                'mobile' => '12345',
                'password' => Hash::make('password123'), // Encrypt the password
                'role_id' => 1, 
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Jane Smith',
                'mobile' => '123456',
                'password' => Hash::make('securepass'), // Encrypt the password
                'role_id' => 2, 
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Alice Brown',
                'mobile' => '09127919278',
                'password' => Hash::make('password123'), // Encrypt the password
                'role_id' => 3, 
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
