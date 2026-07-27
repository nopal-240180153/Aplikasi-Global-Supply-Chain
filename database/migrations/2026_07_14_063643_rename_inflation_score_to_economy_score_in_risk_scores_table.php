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
        if (Schema::hasTable('risk_scores') && Schema::hasColumn('risk_scores', 'inflation_score')) {
            Schema::table('risk_scores', function (Blueprint $table) {
                $table->renameColumn('inflation_score', 'economy_score');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('risk_scores') && Schema::hasColumn('risk_scores', 'economy_score')) {
            Schema::table('risk_scores', function (Blueprint $table) {
                $table->renameColumn('economy_score', 'inflation_score');
            });
        }
    }
};