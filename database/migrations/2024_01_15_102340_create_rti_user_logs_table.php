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
        Schema::create('rti_user_logs', function (Blueprint $table) {
            $table->id();
              $table->string('user_id', 20);
            $table->string('applicant_name', 100);
            $table->string('regmobile_no', 10);
            $table->string('email_id', 100);
            $table->timestamp('logindatetime');
            $table->timestamp('logOutdatetime')->nullable();
            $table->string('loggedin_ip', 20);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rti_user_logs');
    }
};
