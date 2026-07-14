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
        Schema::create('dept_officers_master', function (Blueprint $table) {
            $table->id();
            $table->string('name',100);
            $table->string('gender',4);
            $table->string('designation',10);
            $table->unsignedBigInteger('dept_id');
            $table->integer('role');
            $table->integer('is_active');
            $table->string('nodel_code');
            $table->string('phone_no');
            $table->string('mobile_no');
            $table->string('email');
            $table->string('address1');
            $table->string('address2')->nullable();
            $table->string('address3')->nullable();
            $table->integer('pincode');
            $table->integer('state');
            $table->integer('country');
            $table->string('ip_address')->nullable();
            $table->string('mac_address')->nullable();
            $table->json('created_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dept_officers_master');
    }
};
