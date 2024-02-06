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
        Schema::create('cars', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('model_id'); //<--- This is the foreign key for models / makes and types - small, van, etc
            $table->unsignedSmallInteger('number_of_passengers');
            $table->unsignedSmallInteger('number_of_doors');
            $table->unsignedSmallInteger('fuel_type');
            $table->boolean('is_automatic');
            $table->unsignedInteger('current_mileage');
            $table->decimal('price_per_day', 8, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
