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
        Schema::create('req_dispose_ofs', function (Blueprint $table) {
            $table->id();
            $table->string('reg_no');
            $table->string('file_no');
            $table->string('disposal_type');
            $table->string('reason');
            $table->string('document_name', 50)->nullable;
            $table->string('document_path', 300)->nullable;
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('req_dispose_ofs');
    }
};
