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
        Schema::create('task_maps', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->string('area')->nullable();
            $table->string('position')->nullable();
            $table->string('target')->nullable();
            $table->string('unit')->nullable();
            $table->string('kpi')->nullable();
            $table->string('result')->nullable();
            $table->string('image')->nullable();
            $table->string('detail')->nullable();
            $table->string('round')->nullable();
            $table->string('fake_result')->nullable();
            $table->unsignedBigInteger('task_id');
            $table->unsignedBigInteger('map_id');
            $table->foreign('task_id')->references('id')->on('task_details')->onDelete('cascade');
            $table->foreign('map_id')->references('id')->on('maps')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_maps');
    }
};
