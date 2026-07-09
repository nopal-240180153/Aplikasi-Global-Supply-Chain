<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('countries', function (Blueprint $table) {

            $table->id();

            // UUID dari REST Countries
            $table->uuid('uuid')->unique();

            // Nama negara
            $table->string('name');
            $table->string('official_name')->nullable();

            // Kode negara
            $table->string('iso2', 2)->nullable()->unique();
            $table->string('iso3', 3)->nullable()->unique();

            // Wilayah
            $table->string('region')->nullable();
            $table->string('subregion')->nullable();
            $table->string('continent')->nullable();
            // Ibu kota
            $table->string('capital')->nullable();

            // Koordinat
            $table->decimal('latitude', 10, 6)->nullable();
            $table->decimal('longitude', 10, 6)->nullable();

            // Populasi
            $table->bigInteger('population')->nullable();

            // Mata uang utama
            $table->string('currency_code',10)->nullable();
            $table->string('currency_name')->nullable();

            // Bahasa utama
            $table->string('language')->nullable();

            // URL bendera
            $table->text('flag')->nullable();

            // Digunakan nanti untuk Dashboard
            $table->decimal('risk_score',5,2)->default(0);
            $table->string('risk_level')->default('Low');
            $table->timestamp('last_synced_at')->nullable();
            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('countries');
    }
};