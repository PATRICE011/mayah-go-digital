<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWishlistTable extends Migration
{
    public function up()
    {
        Schema::create('wishlists', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade'); // Foreign key to products table
            $table->timestamps(); // Track when the product was added to the wishlist

            $table->foreign('user_id')->references('id')->on('users_area')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('wishlists');
    }
}

