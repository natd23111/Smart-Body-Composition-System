<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('goals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('metric', ['weight_kg', 'body_fat_percent', 'muscle_mass', 'bmi', 'visceral_fat', 'body_water_percent']);
            $table->float('target_value');
            $table->float('start_value')->nullable();
            $table->date('deadline')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['active', 'achieved', 'abandoned'])->default('active');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('goals');
    }
};
