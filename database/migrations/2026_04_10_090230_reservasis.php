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
        Schema::create('reservasis', function (Blueprint $table) {
            $table->id('reservasiId');
            $table->string('kode_reservasi', 30)->unique();
            $table->foreignId('userId')
                  ->constrained('users', 'userId')
                  ->cascadeOnDelete();
            $table->foreignId('jadwalId')
                  ->constrained('jadwals', 'jadwalId')
                  ->cascadeOnDelete();
            $table->text('catatan')->nullable();
            $table->unsignedInteger('jml_peserta');
            $table->decimal('total_harga', 12, 2);
            $table->enum('status', ['unpaid', 'paid', 'cancelled'])->default('unpaid');
            $table->timestamps();
            $table->softDeletes();
            $table->index('userId');
            $table->index('jadwalId');
            $table->index('status');
            $table->index('kode_reservasi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservasis');
    }
};
