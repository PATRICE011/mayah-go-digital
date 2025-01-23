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
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade'); // Foreign key to products
            $table->enum('type', ['in', 'out']); // 'in' for stock-in, 'out' for stock-out
            $table->integer('quantity'); // Quantity added or removed
            $table->text('remarks')->nullable(); // Reason for the movement (e.g., "Initial Stock", "Sold", "Restock")
            $table->timestamps(); // Created at and updated at
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};
