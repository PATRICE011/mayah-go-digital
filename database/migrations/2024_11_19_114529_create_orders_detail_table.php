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
        Schema::create('orderdetails', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');  // Foreign key for the Order
            $table->string('order_id_custom', 7)->unique();  // Unique 7-digit Order ID
            $table->string('payment_method')->nullable();
            $table->decimal('total_amount', 10, 2)->nullable();  // Total order amount
            $table->timestamps();
       
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
        });
       
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orderdetails');
    }
};
