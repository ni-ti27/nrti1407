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
        Schema::create('transfer_to_another_departments', function (Blueprint $table) {
            $table->id();
            $table->string('old_department_id');
            $table->string('old_department_name');
            $table->string('new_department_id');
            $table->string('new_department_name');
            $table->string('meta_deta');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfer_to_another_departments');
    }
};
