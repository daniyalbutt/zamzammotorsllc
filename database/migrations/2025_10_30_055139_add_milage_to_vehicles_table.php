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
        Schema::table('vehicles', function (Blueprint $table) {
            $table->string('mileage')->nullable();
            $table->string('engine')->nullable();   
            $table->string('rhd_lhd')->nullable();
            $table->string('stock_id')->nullable();
            $table->string('video')->nullable();
        });         
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropColumn('mileage');
            $table->dropColumn('engine');
            $table->dropColumn('rhd_lhd');
            $table->dropColumn('stock_id');
            $table->dropColumn('video');
        });
    }
};
