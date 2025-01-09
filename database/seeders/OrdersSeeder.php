<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
class OrdersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Example user IDs from the `users_area` table
        $userIds = DB::table('users_area')->pluck('id')->toArray();

        // Example product data from the `products` table
        $products = DB::table('products')->select('id', 'product_price')->get();

        // Seed data for both current week and previous week
        $weeks = [
            'current' => [
                'start' => now()->startOfWeek(),
                'end' => now()->endOfWeek(),
            ],
            'previous' => [
                'start' => now()->subWeek()->startOfWeek(),
                'end' => now()->subWeek()->endOfWeek(),
            ],
        ];

        foreach ($weeks as $week) {
            $startOfWeek = $week['start'];
            $endOfWeek = $week['end'];

            // Loop through each day of the week
            for ($day = $startOfWeek; $day <= $endOfWeek; $day->addDay()) {
                // Create 1–3 orders for each day
                for ($i = 1; $i <= rand(1, 3); $i++) {
                    // Insert a new order
                    $orderId = DB::table('orders')->insertGetId([
                        'user_id' => $userIds[array_rand($userIds)], // Random user
                        'status' => 'paid', // Order status
                        'created_at' => $day->copy(), // Set created_at to the specific day
                        'updated_at' => $day->copy(),
                    ]);

                    // Generate 1–5 items for each order
                    $totalAmount = 0;
                    $numItems = rand(1, 5);

                    for ($j = 1; $j <= $numItems; $j++) {
                        // Pick a random product
                        $product = $products->random();

                        // Random quantity
                        $quantity = rand(1, 5);

                        // Calculate price for this item
                        $price = $quantity * $product->product_price;

                        // Add this to the total amount
                        $totalAmount += $price;

                        // Insert the order item
                        DB::table('order_items')->insert([
                            'order_id' => $orderId,
                            'product_id' => $product->id,
                            'quantity' => $quantity,
                            'price' => $price,
                            'created_at' => $day->copy(),
                            'updated_at' => $day->copy(),
                        ]);
                    }

                    // Insert into orderdetails
                    DB::table('orderdetails')->insert([
                        'order_id' => $orderId,
                        'order_id_custom' => Str::random(7), // Generate a unique 7-character order ID
                        'payment_method' => ['gcash', 'paymaya', 'cod'][array_rand(['gcash', 'paymaya', 'cod'])], // Random payment method
                        'total_amount' => $totalAmount,
                        'created_at' => $day->copy(),
                        'updated_at' => $day->copy(),
                    ]);
                }
            }
        }
    }
}
