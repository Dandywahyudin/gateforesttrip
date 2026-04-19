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
        Schema::create('peserta_trips', function (Blueprint $table) {
            $table->id('pesertaId');
            $table->foreignId('reservasiId')
                  ->constrained('reservasis', 'reservasiId')
                  ->cascadeOnDelete();
            $table->string('nama');
            $table->string('email')->nullable();
            $table->enum('jenis_kelamin', ['laki-laki', 'perempuan']);
            $table->date('tanggal_lahir');
            $table->string('no_hp', 20)->nullable();
            $table->softDeletes();
            $table->timestamps();
            $table->index('reservasiId');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('peserta_trips');
    }
};
