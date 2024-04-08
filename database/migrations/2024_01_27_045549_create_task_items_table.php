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
        Schema::create('task_items', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable();
            $table->string('name')->nullable();
            $table->string('unit')->nullable();
            $table->string('kpi')->nullable();
            $table->string('result')->nullable();
            $table->string('image')->nullable();
            $table->string('detail')->nullable();
            $table->unsignedBigInteger('task_id');
            $table->unsignedBigInteger('item_id');
            $table->foreign('task_id')->references('id')->on('task_details')->onDelete('cascade');
            $table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_items');
    }
};
