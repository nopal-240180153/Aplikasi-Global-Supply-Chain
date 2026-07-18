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
        Schema::create('positive_words', function (Blueprint $table) {
            $table->id();
            $table->string('word')->unique();
            $table->decimal('weight', 3, 2)->default(1.00); // Bobot kata (0.00 - 9.99)
            $table->string('category')->nullable(); // ekonomi, logistik, dll
            $table->timestamps();
            
            $table->index('word');
        });
        
        // Insert default positive words
        DB::table('positive_words')->insert([
            ['word' => 'growth', 'weight' => 1.50, 'category' => 'economy', 'created_at' => now(), 'updated_at' => now()],
            ['word' => 'increase', 'weight' => 1.20, 'category' => 'economy', 'created_at' => now(), 'updated_at' => now()],
            ['word' => 'profit', 'weight' => 1.50, 'category' => 'economy', 'created_at' => now(), 'updated_at' => now()],
            ['word' => 'stable', 'weight' => 1.30, 'category' => 'economy', 'created_at' => now(), 'updated_at' => now()],
            ['word' => 'improve', 'weight' => 1.40, 'category' => 'general', 'created_at' => now(), 'updated_at' => now()],
            ['word' => 'expansion', 'weight' => 1.50, 'category' => 'business', 'created_at' => now(), 'updated_at' => now()],
            ['word' => 'success', 'weight' => 1.50, 'category' => 'general', 'created_at' => now(), 'updated_at' => now()],
            ['word' => 'efficient', 'weight' => 1.30, 'category' => 'logistics', 'created_at' => now(), 'updated_at' => now()],
            ['word' => 'advance', 'weight' => 1.30, 'category' => 'general', 'created_at' => now(), 'updated_at' => now()],
            ['word' => 'boost', 'weight' => 1.40, 'category' => 'economy', 'created_at' => now(), 'updated_at' => now()],
            ['word' => 'recovery', 'weight' => 1.50, 'category' => 'economy', 'created_at' => now(), 'updated_at' => now()],
            ['word' => 'strength', 'weight' => 1.30, 'category' => 'general', 'created_at' => now(), 'updated_at' => now()],
            ['word' => 'opportunity', 'weight' => 1.20, 'category' => 'business', 'created_at' => now(), 'updated_at' => now()],
            ['word' => 'positive', 'weight' => 1.30, 'category' => 'general', 'created_at' => now(), 'updated_at' => now()],
            ['word' => 'progress', 'weight' => 1.40, 'category' => 'general', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('positive_words');
    }
};
