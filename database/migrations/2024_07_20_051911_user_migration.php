<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_area', function (Blueprint $table) {
            $table->id();
            $table->string('name'); 
            $table->string('mobile')->unique();
            $table->string('password'); 
            $table->string('otp')->nullable(); //for otp
            $table->timestamp('otp_created_at')->nullable(); 
            $table->integer('otp_attempts')->default(0);
            $table->foreignId('role_id')->nullable()->default(3)->constrained('roles')->cascadeOnUpdate()->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_area');
    }
};
