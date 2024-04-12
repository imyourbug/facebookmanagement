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
        Schema::create('links', function (Blueprint $table) {
            $table->id();
            $table->string('time')->default(0);
            $table->string('title')->default('');
            $table->string('content')->default('');
            $table->string('comment_first')->default(0);
            $table->string('comment_second')->default(0);
            $table->string('data_first')->default(0);
            $table->string('data_second')->default(0);
            $table->string('emotion_first')->default(0);
            $table->string('emotion_second')->default(0);
            $table->string('delay')->default(0);
            $table->string('status')->default(0);
            $table->string('is_scan')->default(0);
            $table->string('note')->default('');
            $table->string('link_or_post_id')->default('');
            $table->string('type')->default('');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('links');
    }
};
