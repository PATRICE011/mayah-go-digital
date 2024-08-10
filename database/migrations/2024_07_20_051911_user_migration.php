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
            $table->string('name'); // for name
            // $table->string('email')->unique(); // for email
            $table->string('mobile')->unique();// for mobile number
            // $table->text('address'); // for address
            $table->string('password'); // for password
            $table->string('otp')->nullable(); //for otp
            $table->timestamp('otp_created_at')->nullable(); // for storing OTP generation time
            $table->integer('otp_attempts')->default(0);
            $table->boolean('is_admin')->default(0);
            $table->timestamps(); // created_at and updated_at timestamps
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
