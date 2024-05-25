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
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->longText('title')->nullable();
            $table->string('uid')->nullable();
            $table->longText('phone')->nullable();
            $table->longText('content')->nullable();
            $table->longText('note')->nullable();
            $table->string('comment_id')->nullable();
            $table->string('link_or_post_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
