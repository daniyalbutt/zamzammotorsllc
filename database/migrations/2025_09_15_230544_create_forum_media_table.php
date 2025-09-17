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
        Schema::create('forum_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('forum_discussion_id')->constrained('forum_discussions')->onDelete('cascade');
            $table->string('file_name');
            $table->string('file_path');
            $table->string('file_extension');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forum_media');
    }
};
