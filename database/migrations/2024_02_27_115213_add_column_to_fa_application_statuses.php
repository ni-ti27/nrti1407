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
        Schema::table('fa_application_statuses', function (Blueprint $table) {
            $table->string('fa_reg_no')->nullable();
            $table->foreignId('assigned_off1')->nullable()->constrained('dept_officers_master');
            $table->foreignId('assigned_off2')->nullable()->constrained('dept_officers_master');
            $table->foreignId('updated_by')->constrained('dept_officers_master');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fa_application_statuses', function (Blueprint $table) {
            $table->dropColumn('fa_reg_no');
            $table->dropColumn('assigned_off1');
            $table->dropColumn('assigned_off2');
            $table->dropColumn('updated_by');
        });
    }
};
