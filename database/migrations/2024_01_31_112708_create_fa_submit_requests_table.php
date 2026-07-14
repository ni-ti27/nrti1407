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
        Schema::create('fa_submit_requests', function (Blueprint $table) {
            $table->id();
            $table->integer('applied_dept');
            $table->integer('applied_subdept');
            $table->integer('applied_office');
            $table->string('dept_id', 20)->unique();
            $table->string('reg_no', 50)->unique();
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
            $table->timestamp('newrequest_date')->nullable();
            $table->string('pioname', 100);
            $table->string('pioaddr', 150);
            $table->integer('pioresponse');
            $table->timestamp('firstappeal_Subdate')->nullable();
            $table->timestamps();
            $table->foreignId('status_id')->constrained('fa_application_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fa_submit_requests');
    }
};
