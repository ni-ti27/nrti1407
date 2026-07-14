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
        Schema::create('sub_depts', function (Blueprint $table) {
            $table->id();
            $table->string('sub_dept_name');
            $table->string('is_actiive');
            $table->string('metaData');
            $table->unsignedBigInteger('dept_id');
            $table->foreign('dept_id')->references('id')->on('departments');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_depts');
    }
};
