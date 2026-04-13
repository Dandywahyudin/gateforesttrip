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
        Schema::create('jadwals', function (Blueprint $table) {
            $table->id('jadwalId');
            $table->foreignId('paketId')
                  ->constrained('paket_trips', 'paketId')
                  ->cascadeOnDelete();
            $table->date('tanggal_berangkat');
            $table->date('tanggal_kembali');
            $table->unsignedInteger('kuota_max');
            $table->unsignedInteger('kuota_terisi')->default(0);
            $table->enum('status', ['open', 'full', 'cancelled'])->default('open');
            $table->decimal('harga_override', 12, 2)->nullable();
            $table->dateTime('cutoff_booking')->nullable();
            $table->timestamps();
            $table->softDeletes('deleted_at');

            $table->index('paketId');
            $table->index('tanggal_berangkat');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwals');
    }
};
