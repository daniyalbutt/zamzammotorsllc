<?php

use App\Models\Forum;
use App\Models\User;
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
        Schema::create('forums_discussions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Forum::class);
            $table->foreignIdFor(User::class);
            $table->longText('content');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forums_discussions');
    }
};
