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
        Schema::create('setting_task_chemistries', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->string('name')->nullable();
            $table->string('unit')->nullable();
            $table->string('kpi')->nullable();
            $table->string('result')->nullable();
            $table->string('image')->nullable();
            $table->string('detail')->nullable();
            $table->unsignedBigInteger('task_id');
            $table->unsignedBigInteger('chemistry_id');
            $table->foreign('task_id')->references('id')->on('tasks')->onDelete('cascade');
            $table->foreign('chemistry_id')->references('id')->on('chemistries')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('setting_task_chemistries');
    }
};
