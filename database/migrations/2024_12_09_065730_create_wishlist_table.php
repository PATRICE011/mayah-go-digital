<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWishlistTable extends Migration
{
    public function up()
    {
        Schema::create('wishlists', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // No longer nullable
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->timestamps();
        
            // The user_id foreign key references the users_area table, deleting wishlist when user is deleted
            $table->foreign('user_id')->references('id')->on('users_area')->onDelete('cascade');
        });
        
    }

    public function down()
    {
        Schema::dropIfExists('wishlists');
    }
}

