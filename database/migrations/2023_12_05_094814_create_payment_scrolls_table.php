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
        Schema::create('payment_scrolls', function (Blueprint $table) {
            $table->id();
            $table->integer('transaction_id');
            $table->date('trans_dt');
            $table->string('eChallan_no', 15);
            $table->decimal('total_amt', 15, 2)->nullable();
            $table->date('bank_recv_date');
            $table->string('bank_recv_tm', 11);
            $table->string('party_ref_no', 50);
            $table->string('party', 200);
            $table->text('payment_response_xml')->nullable();
            $table->integer('demand_id');
            $table->string('head1', 20);
            $table->double('amount');
            $table->string('sbi_ref', 20);
            $table->string('dept_data', 500);
            $table->char('posted_status', 2);
            $table->date('posted_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_scrolls');
    }
};
