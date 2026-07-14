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
        Schema::create('sub_sub_depts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('is_active');
            $table->string('metaData');
            $table->unsignedBigInteger('sub_dept_id');
            $table->foreign('sub_dept_id')->references('id')->on('sub_depts');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_sub_depts');
    }
};
