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
        Schema::create('link_comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('link_id')->nullable();
            $table->unsignedBigInteger('comment_id')->nullable();
            $table->timestamps();
            $table->foreign('comment_id')->references('id')->on('comments')->onDelete('cascade');
            $table->foreign('link_id')->references('id')->on('links')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('link_comments');
    }
};
