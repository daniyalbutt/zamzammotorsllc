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
                
                $table->foreignId('make_id')->constrained();
                $table->foreignId('model_id')->constrained();
                $table->foreignId('body_type_id')->constrained('body_types');
                
                $table->string('title');
                $table->text('content')->nullable();
                $table->enum('condition', ['Used', 'New', 'Certified Pre-Owned'])->default('Used');
                $table->string('offer_type')->nullable();
                
                $table->enum('drive_type', ['AWD/4WD', 'Front Wheel Drive', 'Rear Wheel Drive', '2Wheel Drive'])->nullable();
                $table->enum('transmission', ['Automatic', 'Manual', 'CVT', 'Semi-Automatic'])->nullable();
                $table->enum('fuel_type', ['Petrol', 'Diesel', 'Hybrid', 'Electric', 'CNG'])->nullable();
                $table->integer('cylinders')->nullable();
                $table->string('color');
                $table->string('doors');
                $table->integer('year')->nullable();
                
                $table->json('features')->nullable();
                $table->json('safety_features')->nullable();
                
                $table->json('image_urls')->nullable();

                $table->timestamps();
                
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