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
        Schema::table('fa_submit_requests', function (Blueprint $table) {
            $table->dropColumn('newrequest_date');
            $table->dropColumn('firstappeal_Subdate');
            $table->string('phone_no')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fa_submit_requests', function (Blueprint $table) {
            //
        });
    }
};
