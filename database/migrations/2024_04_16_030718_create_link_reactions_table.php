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
        Schema::create('link_reactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('link_id')->nullable();
            $table->unsignedBigInteger('reaction_id')->nullable();
            $table->timestamps();
            $table->foreign('reaction_id')->references('id')->on('reactions')->onDelete('cascade');
            $table->foreign('link_id')->references('id')->on('links')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('link_reactions');
    }
};
