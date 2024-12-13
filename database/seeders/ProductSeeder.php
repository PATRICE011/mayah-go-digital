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
        $categories = [
            ['id' => 1, 'category_name' => 'Snacks', 'slug' => Str::slug('Snacks')],
            ['id' => 2, 'category_name' => 'Drinks', 'slug' => Str::slug('Drinks')],
        ];

        DB::table('categories')->insert($categories);

        $products = [
            [
                'product_name' => 'Bread Stix',
                'product_image' => 'BISCUITS-1.png',
                'product_price' => 16,
                'product_stocks' => 20,
                'category_id' => 1, 
                'product_description' => 'Crunchy and baked breadsticks, perfect as a savory snack. A popular choice for snack lovers.',
                'cart_product_description' => 'Crunchy and baked breadsticks.',
            ],
            [
                'product_name' => 'Fita',
                'product_image' => 'BISCUITS-2.png',
                'product_price' => 10,
                'product_stocks' => 20,
                'category_id' => 1, 
                'product_description' => 'Deliciously crunchy biscuits with a delightful taste. Fita is a classic Filipino snack.',
                'cart_product_description' => 'Crunchy and baked breadsticks.',
            ],
            [
                'product_name' => 'C2 Green',
                'product_image' => 'DRINKS-1.png',
                'product_price' => 25,
                'product_stocks' => 20,
                'category_id' => 2, 
                'product_description' => 'C2 Green Tea is a refreshing drink made from real tea leaves, perfect for quenching your thirst.',
                'cart_product_description' => 'Crunchy and baked breadsticks.',
            ],
            [
                'product_name' => 'C2 Yellow',
                'product_image' => 'DRINKS-2.png',
                'product_price' => 25,
                'product_stocks' => 20,
                'category_id' => 2,
                'product_description' => 'C2 Yellow is a refreshing lemon iced tea, perfect for a hot day. Enjoy the refreshing taste!',
                'cart_product_description' => 'Crunchy and baked breadsticks.',
           
            ],
        ];

        DB::table('products')->insert($products);
    }
}
