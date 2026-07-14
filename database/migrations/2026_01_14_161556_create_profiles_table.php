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

        Schema::create('profiles', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('name', 100);
            $table->string('gender', 10);

            $table->string('address_line1', 200);
            $table->string('address_line2', 200);
            $table->string('address_line3', 200);

            $table->integer('pincode');
            $table->integer('country');
            $table->integer('state');
            $table->integer('district');
            $table->integer('taluka');
            $table->integer('village');

            $table->string('phone_no', 15);
            $table->string('mobile_no', 15);
            $table->string('email_id', 255);

            $table->integer('edu_status');
            $table->integer('qualification');
            $table->integer('origin_country');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};
