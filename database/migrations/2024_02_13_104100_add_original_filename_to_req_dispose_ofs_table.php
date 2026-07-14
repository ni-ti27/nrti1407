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
            $table->string('original_doc_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('req_dispose_ofs', function (Blueprint $table) {
            $table->dropColumn('original_doc_name');
        });
    }
};
