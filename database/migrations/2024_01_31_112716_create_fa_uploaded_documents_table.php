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
        Schema::create('fa_uploaded_documents', function (Blueprint $table) {
            $table->id();
            $table->string('reg_no', 50);
            $table->integer('document_category');
            $table->string('document_name', 50);
            $table->string('document_path', 300);
            $table->timestamp('document_upload_date')->nullable();
            $table->string('document_name_original', 200)->nullable();
            $table->timestamps();
            $table->foreign('reg_no')->references('reg_no')->on('fa_submit_request')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fa_uploaded_documents');
    }
};
