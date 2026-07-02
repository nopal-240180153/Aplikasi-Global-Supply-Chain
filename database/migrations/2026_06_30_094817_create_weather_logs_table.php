<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('weather_logs', function (Blueprint $table) {

            $table->id();

            $table->foreignId('country_id')
                ->constrained('countries')
                ->cascadeOnDelete();

            $table->decimal('temperature',5,2)->nullable();

            $table->decimal('rainfall',8,2)->nullable();

            $table->decimal('wind_speed',8,2)->nullable();

            $table->decimal('storm_risk',5,2)->default(0);

            $table->string('weather_condition')->nullable();

            $table->timestamp('recorded_at');

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('weather_logs');
    }
};