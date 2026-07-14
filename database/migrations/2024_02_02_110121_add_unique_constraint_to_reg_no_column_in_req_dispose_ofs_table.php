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
        Schema::table('req_dispose_ofs', function (Blueprint $table) {
            $table->string('file_no')->nullable()->change();
            $table->unique('reg_no');
            $table->string('disposal_type')->nullable()->change();
            $table->string('document_name')->nullable()->change();
            $table->string('document_path')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('req_dispose_ofs', function (Blueprint $table) {
            $table->dropUnique('req_dispose_ofs_reg_no_unique');
            $table->string('file_no')->nullable(false)->change();
            $table->string('disposal_type')->nullable(false)->change();
            $table->string('document_name', 50)->nullable(false)->change();
            $table->string('document_path', 300)->nullable(false)->change();
        });
    }
};
