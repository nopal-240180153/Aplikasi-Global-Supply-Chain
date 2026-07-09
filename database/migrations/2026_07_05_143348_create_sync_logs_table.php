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
        Schema::create('sync_logs', function (Blueprint $table) {

            $table->id();

            // Nama modul yang disinkronkan
            $table->string('module');

            // Success | Failed | Running
            $table->enum('status', [
                'Running',
                'Success',
                'Failed'
            ]);

            // Jumlah data berhasil diproses
            $table->integer('total_data')->default(0);

            // Jumlah data berhasil diperbarui
            $table->integer('updated_data')->default(0);

            // Jumlah data gagal
            $table->integer('failed_data')->default(0);

            // Lama proses (detik)
            $table->decimal('duration',8,2)->nullable();

            // Pesan error jika gagal
            $table->text('message')->nullable();

            // Waktu mulai
            $table->timestamp('started_at')->nullable();

            // Waktu selesai
            $table->timestamp('finished_at')->nullable();

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sync_logs');
    }
};