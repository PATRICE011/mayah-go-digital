<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Call the AdminUserSeeder
        $this->call(ProductSeeder::class);
        $this->call(UserSeeder::class);
    }
}

