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
        Schema::create('news_articles', function (Blueprint $table) {

            $table->id();

            $table->foreignId('country_id')
                ->nullable()
                ->constrained('countries')
                ->nullOnDelete();

            $table->string('title');

            $table->string('source');

            $table->text('url');

            $table->longText('summary')->nullable();

            $table->enum('sentiment', [

                'Positive',

                'Neutral',

                'Negative'

            ])->default('Neutral');

            $table->decimal('sentiment_score',5,2)
                ->default(0);

            $table->timestamp('published_at')->nullable();

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news_articles');
    }
};