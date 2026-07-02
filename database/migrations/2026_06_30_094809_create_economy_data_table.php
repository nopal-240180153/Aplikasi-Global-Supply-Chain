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
        Schema::create('economy_data', function (Blueprint $table) {

            $table->id();

            $table->foreignId('country_id')
                ->constrained('countries')
                ->cascadeOnDelete();

            $table->year('year');

            $table->decimal('gdp',20,2)->nullable();

            $table->decimal('inflation',8,2)->nullable();

            $table->bigInteger('population')->nullable();

            $table->decimal('exports',20,2)->nullable();

            $table->decimal('imports',20,2)->nullable();

            $table->timestamps();

            $table->unique(['country_id','year']);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('economy_data');
    }
};