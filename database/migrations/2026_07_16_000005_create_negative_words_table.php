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
        Schema::create('negative_words', function (Blueprint $table) {
            $table->id();
            $table->string('word')->unique();
            $table->decimal('weight', 3, 2)->default(1.00); // Bobot kata (0.00 - 9.99)
            $table->string('category')->nullable(); // ekonomi, logistik, dll
            $table->timestamps();
            
            $table->index('word');
        });
        
        // Insert default negative words
        DB::table('negative_words')->insert([
            ['word' => 'war', 'weight' => 2.00, 'category' => 'conflict', 'created_at' => now(), 'updated_at' => now()],
            ['word' => 'crisis', 'weight' => 2.00, 'category' => 'economy', 'created_at' => now(), 'updated_at' => now()],
            ['word' => 'inflation', 'weight' => 1.50, 'category' => 'economy', 'created_at' => now(), 'updated_at' => now()],
            ['word' => 'delay', 'weight' => 1.30, 'category' => 'logistics', 'created_at' => now(), 'updated_at' => now()],
            ['word' => 'disaster', 'weight' => 2.00, 'category' => 'general', 'created_at' => now(), 'updated_at' => now()],
            ['word' => 'decline', 'weight' => 1.50, 'category' => 'economy', 'created_at' => now(), 'updated_at' => now()],
            ['word' => 'recession', 'weight' => 1.80, 'category' => 'economy', 'created_at' => now(), 'updated_at' => now()],
            ['word' => 'disruption', 'weight' => 1.60, 'category' => 'logistics', 'created_at' => now(), 'updated_at' => now()],
            ['word' => 'conflict', 'weight' => 1.80, 'category' => 'conflict', 'created_at' => now(), 'updated_at' => now()],
            ['word' => 'shortage', 'weight' => 1.50, 'category' => 'supply', 'created_at' => now(), 'updated_at' => now()],
            ['word' => 'failure', 'weight' => 1.60, 'category' => 'general', 'created_at' => now(), 'updated_at' => now()],
            ['word' => 'decrease', 'weight' => 1.30, 'category' => 'economy', 'created_at' => now(), 'updated_at' => now()],
            ['word' => 'loss', 'weight' => 1.50, 'category' => 'economy', 'created_at' => now(), 'updated_at' => now()],
            ['word' => 'risk', 'weight' => 1.20, 'category' => 'general', 'created_at' => now(), 'updated_at' => now()],
            ['word' => 'concern', 'weight' => 1.10, 'category' => 'general', 'created_at' => now(), 'updated_at' => now()],
            ['word' => 'threat', 'weight' => 1.70, 'category' => 'general', 'created_at' => now(), 'updated_at' => now()],
            ['word' => 'strike', 'weight' => 1.50, 'category' => 'logistics', 'created_at' => now(), 'updated_at' => now()],
            ['word' => 'blockade', 'weight' => 1.80, 'category' => 'logistics', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('negative_words');
    }
};
