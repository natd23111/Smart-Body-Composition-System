<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recommendation_templates', function (Blueprint $table) {
            $table->id();
            $table->string('template_id')->unique(); // e.g. 'hydration-foundation'
            $table->string('template_code')->unique(); // e.g. 'TMPL-HYD-001'
            $table->string('type'); // Hydration, Exercise, Nutrition, Recovery, Sleep
            $table->string('title');
            $table->text('summary');
            $table->json('details'); // array of bullet-point strings
            $table->string('priority')->default('medium'); // high, medium, low
            $table->string('status')->default('Active'); // Active, Archived
            $table->string('icon')->default('heart');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recommendation_templates');
    }
};
