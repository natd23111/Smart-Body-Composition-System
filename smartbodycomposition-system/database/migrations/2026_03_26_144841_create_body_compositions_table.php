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
        Schema::create('body_compositions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('measurement_date');
            $table->time('measurement_time')->nullable();
            $table->float('weight_kg')->nullable();
            $table->float('body_fat_percent')->nullable();
            $table->float('body_fat_kg')->nullable();
            $table->float('body_water_percent')->nullable();
            $table->float('muscle_mass')->nullable();
            $table->float('physical_rating')->nullable();
            $table->float('bone_mass')->nullable();
            $table->float('kcal')->nullable();
            $table->float('bmr')->nullable();
            $table->float('visceral_fat')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('body_compositions');
    }
};
