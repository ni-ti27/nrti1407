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
        Schema::create('rti_applicant_regdata', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('regmobile_no', 15);
            $table->string('email_id', 50)->nullable();
            $table->string('dept_id', 20);
            $table->string('reg_no', 50);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rti_applicant_regdata');
    }
};
