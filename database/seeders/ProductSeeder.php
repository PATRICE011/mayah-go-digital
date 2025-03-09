<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // Define time ranges for previous and current weeks
        $currentWeekStart = now()->startOfWeek();
        $previousWeekStart = now()->subWeek()->startOfWeek();

        $categories = [
            [
                'id' => 1,
                'category_image' => 'BISCUITS-1.png',
                'category_name' => 'Snacks',
                'slug' => Str::slug('Snacks'),
            ],
            [
                'id' => 2,
                'category_image' => 'DRINKS-1.png',
                'category_name' => 'Drinks',
                'slug' => Str::slug('Drinks'),
            ],

        ];
        DB::table('categories')->insert($categories);

        // Products data
        $products = [
            [
                'product_name' => 'Bread Stix',
                'product_image' => 'BISCUITS-1.png',
                'product_price' => 16,
                'product_raw_price' => 10,
                'product_stocks' => 20,
                'product_stocks_sold' => 0,
                'category_id' => 1,
                'product_description' => 'Crunchy and baked breadsticks, perfect as a savory snack. A popular choice for snack lovers.',
                'cart_product_description' => 'Crunchy and baked breadsticks.',
            ],
            [
                'product_name' => 'Fita',
                'product_image' => 'BISCUITS-2.png',
                'product_price' => 10,
                'product_raw_price' => 6, 
                'product_stocks' => 20,
                'product_stocks_sold' => 0,
                'category_id' => 1,
                'product_description' => 'Deliciously crunchy biscuits with a delightful taste. Fita is a classic Filipino snack.',
                'cart_product_description' => 'Crunchy and baked breadsticks.',
            ],
            [
                'product_name' => 'C2 Green',
                'product_image' => 'DRINKS-1.png',
                'product_price' => 25,
                'product_raw_price' => 15, 
                'product_stocks' => 20,
                'product_stocks_sold' => 0,
                'category_id' => 2,
                'product_description' => 'C2 Green Tea is a refreshing drink made from real tea leaves, perfect for quenching your thirst.',
                'cart_product_description' => 'Crunchy and baked breadsticks.',
            ],
            [
                'product_name' => 'C2 Yellow',
                'product_image' => 'DRINKS-2.png',
                'product_price' => 25,
                'product_raw_price' => 15, 
                'product_stocks' => 20,
                'product_stocks_sold' => 0,
                'category_id' => 2,
                'product_description' => 'C2 Yellow is a refreshing lemon iced tea, perfect for a hot day. Enjoy the refreshing taste!',
                'cart_product_description' => 'Crunchy and baked breadsticks.',
            ],
            [
                'product_name' => 'C2 Red',
                'product_image' => 'DRINKS-3.png',
                'product_price' => 25,
                'product_raw_price' => 15, 
                'product_stocks' => 20,
                'product_stocks_sold' => 0,
                'category_id' => 2,
                'product_description' => 'C2 Red is a refreshing lemon iced tea, perfect for a hot day. Enjoy the refreshing taste!',
                'cart_product_description' => 'Crunchy and baked breadsticks.',
            ],
        ];


        // Assign random timestamps for the products
        foreach ($products as $product) {

            do {
                $productId = random_int(100000, 99999999);
            } while (DB::table('products')->where('product_id', $productId)->exists());
            // Randomly assign either the current week or the previous week
            $randomTimestamp = rand(0, 1)
                ? $currentWeekStart->copy()->addDays(rand(0, 6)) // Random day in current week
                : $previousWeekStart->copy()->addDays(rand(0, 6)); // Random day in previous week

            DB::table('products')->insert(array_merge($product, [
                'product_id' => $productId,
                'created_at' => $randomTimestamp,
                'updated_at' => $randomTimestamp,
            ]));
        }
    }
}
