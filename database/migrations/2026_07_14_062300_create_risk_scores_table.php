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
        Schema::create('risk_scores', function (Blueprint $table) {

            $table->id();

            $table->foreignId('country_id')
                ->constrained('countries')
                ->cascadeOnDelete();

            /*
            |--------------------------------------------------------------------------
            | Skor masing-masing indikator
            |--------------------------------------------------------------------------
            */

            $table->decimal('weather_score', 5, 2)->default(0);

            $table->decimal('inflation_score', 5, 2)->default(0);

            $table->decimal('exchange_score', 5, 2)->default(0);

            $table->decimal('news_score', 5, 2)->default(0);

            /*
            |--------------------------------------------------------------------------
            | Hasil akhir
            |--------------------------------------------------------------------------
            */

            $table->decimal('total_score', 6, 2)->default(0);

            $table->enum('risk_level', [
                'Rendah',
                'Sedang',
                'Tinggi'
            ])->default('Rendah');

            /*
            |--------------------------------------------------------------------------
            | Waktu Perhitungan
            |--------------------------------------------------------------------------
            */

            $table->timestamp('calculated_at')->nullable();

            $table->timestamps();

            $table->unique('country_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('risk_scores');
    }
};