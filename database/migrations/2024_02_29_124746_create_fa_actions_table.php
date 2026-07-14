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
        Schema::create('fa_actions', function (Blueprint $table) {
            $table->id();
            $table->string('fa_reg_no');
            $table->string('fa_action_details_id')->nullable();
            $table->string('disposal_reason',2000)->nullable();
            $table->string('spio_comments',2000)->nullable();
            $table->string('fa_reply_doc')->nullable();
            $table->string('document_name', 50)->nullable();
            $table->string('document_original_name', 50)->nullable();
            $table->string('document_path', 300)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fa_actions');
    }
};
