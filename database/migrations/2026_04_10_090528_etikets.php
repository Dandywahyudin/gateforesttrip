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
        Schema::create('etikets', function (Blueprint $table) {
            $table->id('tiketId');
            $table->foreignId('reservasiId')
                  ->constrained('reservasis', 'reservasiId')
                  ->cascadeOnDelete();
            $table->string('kode_tiket', 30)->unique();
            $table->string('file_path')->nullable();
            $table->enum('status', ['unused', 'used', 'expired'])->default('unused');
            $table->timestamp('created_at')->useCurrent();
            $table->softDeletes();
            $table->index('reservasiId');
            $table->index('kode_tiket');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('etikets');
    }
};
