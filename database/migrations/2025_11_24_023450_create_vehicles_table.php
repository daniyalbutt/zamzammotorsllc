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
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->enum('condition', ['New', 'Used']);
            $table->enum('steering_type', ['RHD', 'LHD']);
            $table->string('chassis_engine_no')->nullable();
            $table->string('make');
            $table->string('model');
            $table->string('body_type')->nullable();
            $table->string('stock_id')->unique();
            $table->year('year');
            $table->string('offer_type')->nullable();
            $table->enum('drive_type', ['AWD/4WD', 'FWD', 'RWD'])->nullable();
            $table->enum('transmission', ['Automatic', 'Manual', 'CVT', 'Semi Automatic']);
            $table->enum('fuel_type', ['Diesel', 'Gasoline', 'Hybrid', 'Electric']);
            $table->integer('mileage')->nullable();
            $table->string('color')->nullable();
            $table->integer('doors')->nullable();
            $table->text('features')->nullable();
            $table->text('safety_features')->nullable();
            $table->enum('availability', ['Available', 'Reserved', 'Sold Out'])->default('Available');
            $table->decimal('price', 12, 2)->nullable();
            $table->string('video')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
