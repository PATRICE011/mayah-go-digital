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
            ['id' => 1, 'name' => 'admin'],
            ['id' => 2, 'name' => 'staff'],
            ['id' => 3, 'name' => 'resident'],
        ]);

        // Define time ranges for previous and current weeks
        $currentWeekStart = now()->startOfWeek();
        $currentWeekEnd = now()->endOfWeek();

        $previousWeekStart = now()->subWeek()->startOfWeek();
        $previousWeekEnd = now()->subWeek()->endOfWeek();

        // Insert 5 users with role_id = 2 (Staff) for current and previous weeks
        for ($i = 1; $i <= 5; $i++) {
            // For previous week
            DB::table('users_area')->insert([
                'name' => 'Staff User (Prev Week) ' . $i,
                'mobile' => '091234567' . sprintf('%02d', $i),
                'password' => Hash::make('password123'),
                'role_id' => 2,
                'created_at' => $previousWeekStart->copy()->addDays(rand(0, 6)), // Random day in previous week
                'updated_at' => $previousWeekStart->copy()->addDays(rand(0, 6)),
            ]);

            // For current week
            DB::table('users_area')->insert([
                'name' => 'Staff User (Curr Week) ' . $i,
                'mobile' => '091234567' . sprintf('%02d', $i + 5), // Ensure unique mobile numbers
                'password' => Hash::make('password123'),
                'role_id' => 2,
                'created_at' => $currentWeekStart->copy()->addDays(rand(0, 6)), // Random day in current week
                'updated_at' => $currentWeekStart->copy()->addDays(rand(0, 6)),
            ]);
        }

        // Insert 50 users with role_id = 3 (Resident) for current and previous weeks
        for ($i = 1; $i <= 50; $i++) {
            // For previous week
            DB::table('users_area')->insert([
                'name' => 'Resident User (Prev Week) ' . $i,
                'mobile' => '09127890' . sprintf('%02d', $i),
                'password' => Hash::make('password123'),
                'role_id' => 3,
                'created_at' => $previousWeekStart->copy()->addDays(rand(0, 6)), // Random day in previous week
                'updated_at' => $previousWeekStart->copy()->addDays(rand(0, 6)),
            ]);

            // For current week
            DB::table('users_area')->insert([
                'name' => 'Resident User (Curr Week) ' . $i,
                'mobile' => '09127891' . sprintf('%02d', $i), // Ensure unique mobile numbers
                'password' => Hash::make('password123'),
                'role_id' => 3,
                'created_at' => $currentWeekStart->copy()->addDays(rand(0, 6)), // Random day in current week
                'updated_at' => $currentWeekStart->copy()->addDays(rand(0, 6)),
            ]);
        }

       
        DB::table('users_area')->insert([
            [
                'name' => 'John Doe',
                'mobile' => '12345',
                'password' => Hash::make('password123'), // Encrypt the password
                'role_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],

        ]);
    }
}
