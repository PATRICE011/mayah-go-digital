<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePosOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pos_orders', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('order_number')->unique(); // Unique order identifier
            $table->decimal('total_amount', 10, 2); // Total price for the order
            $table->decimal('cash_paid', 10, 2); // Amount of cash paid
            $table->decimal('change', 10, 2)->default(0); // Change to return
            
            $table->enum('status', ['completed', 'pending', 'cancelled'])->default('pending'); // Order status
            $table->timestamps(); // created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pos_orders');
    }
}

