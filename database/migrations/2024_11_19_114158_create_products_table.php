<?php


use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->string('product_id')->unique();
            $table->string('product_name');
            $table->string('product_image', 300);
            $table->text('product_description')->nullable(); 
            $table->text('cart_product_description')->nullable(); 
            $table->integer('product_price');
            $table->integer('product_raw_price')->nullable(); 
            $table->integer('product_stocks')->default(0)->nullable();
            $table->integer('product_stocks_sold')->default(0)->nullable();
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade'); // Foreign key to categories
            $table->timestamps();
        });
        
     
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
