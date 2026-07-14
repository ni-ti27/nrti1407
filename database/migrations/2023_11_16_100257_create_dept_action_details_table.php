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
        Schema::create('action_details', function (Blueprint $table) {
            $table->id();
            $table->string('reg_no',50);
            $table->foreign('reg_no')->references('reg_no')->on('submit_requests');
            // Foreign key constraints
            $table->foreignId('status_id')->nullable()->constrained('application_status');
            $table->foreignId('assigned_off1')->nullable()->constrained('dept_officers_master'); // assigned to Nodal Id by
            $table->foreignId('assigned_off2')->nullable()->constrained('dept_officers_master'); // assigned to PIO id
            $table->foreignId('updated_by')->constrained('dept_officers_master');
            $table->string('ip_address')->nullable();
            $table->string('mac_address')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('action_details');
    }
};
