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
        Schema::create('payment__details', function (Blueprint $table) {
            $table->id();
            $table->date('trans_dt')->nullable();
            $table->bigInteger('applicant_id')->nullable();
            $table->string('reg_no', 30)->nullable();
            $table->string('echallan_no', 50)->nullable();
            $table->decimal('total_amt', 20, 2)->nullable();
            $table->integer('demand_id');
            $table->string('party_ref_no', 200)->nullable();
            $table->string('party', 200)->nullable();
            $table->text('paystat_response_xml')->nullable();
            $table->date('bank_recv_dt')->nullable();
            $table->string('bank_recv_tm', 255)->nullable();
            $table->string('sbi_ref', 255)->nullable();
            $table->string('bank_ref_no', 50)->nullable();
            $table->string('bank_rcvd_date', 25)->nullable();
            $table->string('posted_status', 15)->nullable();
            $table->date('posted_date')->nullable();
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
        Schema::dropIfExists('payment__details');
    }
};
