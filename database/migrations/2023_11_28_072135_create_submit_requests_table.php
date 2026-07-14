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
        Schema::create('submit_requests', function (Blueprint $table) {
            $table->id();
            $table->integer('applied_dept');
            $table->integer('applied_subdept');
            $table->integer('applied_office');
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
            $table->string('is_bpl', 3);
            $table->string('card_no', 50);
            $table->integer('issued_year');
            $table->string('issuing_authority', 100);
            $table->string('request_text', 3000);
            $table->string('document_name', 50);
            $table->string('document_path', 300);
            $table->string('reg_no', 50)->unique();
            $table->string('payment_details')->default('N/A');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('submit_requests');
    }
};
