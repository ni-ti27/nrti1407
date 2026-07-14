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
        Schema::create('fa_action_details', function (Blueprint $table) {
            $table->id();
            $table->string('fa_reg_no',50);
            $table->string('changes',500);
            $table->json('history_field');
            $table->foreignId('fa_application_id')->constrained('fa_application_statuses');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fa_action_details');
    }
};
