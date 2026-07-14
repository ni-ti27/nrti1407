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
        Schema::create('online_payment_details', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('applicant_id')->nullable();
            $table->string('reg_no', 30)->nullable();
            $table->integer('demand_id');
            $table->string('echallan_no', 100)->nullable();
            $table->decimal('total_amt', 20, 2)->nullable();
            $table->text('paystat_response_xml')->nullable();
            $table->string('bank_ref_no', 255)->nullable();
            $table->string('bank_rcvd_date', 255)->nullable();
            $table->string('status', 25)->nullable();
            $table->string('payment_verified', 25)->nullable();
            $table->date('payment_verified_date')->nullable();
            $table->date('updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('online_payment_details');
    }
};
